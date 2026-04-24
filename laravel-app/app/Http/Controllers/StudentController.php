<?php

namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $classId = $user->studentDetails->student_class_id ?? null;

        $nextOnlineCourse = null;
        $assignmentsCount = 0;
        $enrolledCoursesCount = 0;
        $todaySchedules = collect();
        $recentAssignments = collect();
        $recentNotifications = collect();

        if ($classId) {
            $nextOnlineCourse = \App\Models\OnlineCourse::where('class_id', $classId)
                ->where('end_date', '>', now())
                ->orderBy('start_date', 'asc')
                ->first();

            $assignmentsCount = Assignment::where('student_class_id', $classId)->count();
            $enrolledCoursesCount = Subject::whereHas('studentClasses', function($q) use ($classId) {
                $q->where('student_classes.id', $classId);
            })->count();

            $dayOfWeek = strtolower(now()->englishDayOfWeek);
            $todaySchedules = CourseSchedule::where('student_class_id', $classId)
                ->where('day_of_week', $dayOfWeek)
                ->with(['subject', 'user']) // 'user' is the professor
                ->orderBy('start_time')
                ->get();

            $recentAssignments = Assignment::where('student_class_id', $classId)
                ->with(['subject', 'submissions' => function($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->where('due_date', '>', now())
                ->orderBy('due_date', 'asc')
                ->take(3)
                ->get();

            $recentNotifications = \App\Models\Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.student.index', compact(
            'nextOnlineCourse', 
            'assignmentsCount', 
            'enrolledCoursesCount',
            'todaySchedules',
            'recentAssignments',
            'recentNotifications'
        ));
    }

    public function onlineCourses()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $classId = $user->studentDetails->student_class_id ?? null;

        $courses = collect();
        if ($classId) {
            $courses = \App\Models\OnlineCourse::where('class_id', $classId)
                ->with('professor')
                ->orderBy('start_date', 'asc')
                ->paginate(10);
        }

        return view('dashboard.student.online_courses', compact('courses'));
    }

    public function assignments()
    {
        $user = Auth::user();
        $classId = $user->studentDetails->student_class_id ?? null;

        $assignments = collect();
        if ($classId) {
            $assignments = Assignment::where('student_class_id', $classId)
                ->with(['subject.professor', 'submissions' => function($q) use ($user) {
                    $q->where('user_id', $user->id); // Use user_id
                }])
                ->orderBy('due_date', 'asc')
                ->paginate(10);
        }

        return view('dashboard.student.assignments', compact('assignments'));
    }

    public function submitAssignment(Request $request, $id)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:pdf,doc,docx,zip|max:10240',
            'message'    => 'nullable|string|max:1000',
        ]);

        $assignment = Assignment::with(['subject', 'professor'])->findOrFail($id);
        $user = Auth::user();

        // Check if student belongs to the class
        if ($user->studentDetails->student_class_id != $assignment->student_class_id) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à soumettre ce devoir.');
        }

        $path = $request->file('attachment')->store('submissions', 'public');

        Submission::updateOrCreate(
            ['assignment_id' => $id, 'user_id' => $user->id], // Use user_id
            [
                'file_path'    => $path,
                'notes'        => $request->message,
                'submitted_at' => now(),
            ]
        );

        // Notify the professor
        if ($assignment->user_id) {
            \App\Models\Notification::create([
                'user_id' => $assignment->user_id,
                'title'   => 'Nouvelle soumission de devoir',
                'message' => "L'étudiant {$user->name} a soumis le devoir \"{$assignment->title}\".\n\nCours: {$assignment->subject->subject_name}",
                'type'    => 'success',
                'link'    => route('dashboard.professor.all-submissions'),
            ]);
        }

        return back()->with('success', 'Votre devoir a été soumis avec succès.');
    }

    public function courses()
    {
        $user = Auth::user();
        $classId = $user->studentDetails->student_class_id ?? null;

        $subjects = collect();
        if ($classId) {
            $subjects = Subject::whereHas('studentClasses', function($q) use ($classId) {
                $q->where('student_classes.id', $classId);
            })->with('professor')->get();
        }

        return view('dashboard.student.courses', compact('subjects'));
    }

    public function schedule()
    {
        $user = Auth::user();
        $classId = $user->studentDetails->student_class_id ?? null;
        
        $schedules = collect();
        if ($classId) {
            $schedules = CourseSchedule::where('student_class_id', $classId)
                ->with(['subject.professor', 'room'])
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();
        }
        
        return view('dashboard.student.schedule', compact('schedules'));
    }

    public function grades()
    {
        $user = Auth::user();
        
        // Fetch grades for the student
        $grades = \App\Models\Grade::where('user_id', $user->id)
            ->with(['course'])
            ->orderBy('date', 'desc')
            ->get();
            
        return view('dashboard.student.grades', compact('grades'));
    }

    public function history()
    {
        $user = Auth::user();
        
        // We'll mock history as past completed courses/assignments for now
        $pastAssignments = Assignment::whereHas('submissions', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('due_date', '<', now())
            ->with('subject')
            ->orderBy('due_date', 'desc')
            ->paginate(10);
            
        return view('dashboard.student.history', compact('pastAssignments'));
    }

    public function messages()
    {
        $user = Auth::user();
        
        // Get professors mapping
        $professors = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'professor');
        })->get();
        
        $activeUserId = request('receiver_id', $professors->first()->id ?? null);
        
        $messages = \App\Models\Message::where(function($q) use ($user, $activeUserId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $activeUserId);
            })->orWhere(function($q) use ($user, $activeUserId) {
                $q->where('receiver_id', $user->id)->where('sender_id', $activeUserId);
            })->orderBy('created_at', 'asc')->get();
            
        \App\Models\Message::where('receiver_id', $user->id)
            ->where('sender_id', $activeUserId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return view('dashboard.student.messages', compact('messages', 'professors', 'activeUserId'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        
        $message = \App\Models\Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->message,
            'read_at' => null
        ]);
        
        \App\Models\Notification::create([
            'user_id' => $request->receiver_id,
            'title' => 'Nouveau Message',
            'message' => 'Vous avez reçu un nouveau message de ' . Auth::user()->name,
            'type' => 'info',
            'link' => route('dashboard.professor.messages')
        ]);
        
        return back()->with('success', 'Message envoyé avec succès.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.student.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        $user->save();
        
        return back()->with('success', 'Profil mis à jour.');
    }

    public function payments()
    {
        // View for payments (stub)
        return view('dashboard.student.payments');
    }

    public function notifications()
    {
        $notifications = \App\Models\Notification::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->latest()
            ->get();

        \App\Models\Notification::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('dashboard.student.notifications', compact('notifications'));
    }
}
