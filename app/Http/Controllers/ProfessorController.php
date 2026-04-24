<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AttendanceEntry;
use App\Models\AttendanceRecord;
use App\Models\Notification;
use App\Models\OnlineCourse;
use App\Models\CourseMaterial;
use App\Models\CourseSchedule;
use App\Models\StudentClass;
use App\Models\StudentDetail;
use App\Models\Subject;
use App\Models\Submission;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfessorController extends Controller
{
    private function professor()
    {
        return Auth::user();
    }

    // ───── Dashboard ─────
    public function index()
    {
        $prof = Auth::user();

        // Classes taught by this professor
        $classes = StudentClass::with(['academicLevel', 'studentDetails'])
            ->where('professor_responsible', $prof->id)
            ->get();

        $assignments = Assignment::where('user_id', $prof->id)
            ->with(['studentClass', 'subject', 'submissions'])
            ->latest()
            ->take(5)
            ->get();

        $pendingGrading = Submission::whereHas('assignment', fn($q) => $q->where('user_id', $prof->id))
            ->whereNull('grade')
            ->count();

        $notifications = Notification::where('user_id', $prof->id)
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        // Today's schedule
        $dayOfWeek = strtolower(now()->englishDayOfWeek);
        $todaySchedules = CourseSchedule::where('user_id', $prof->id)
            ->where('day_of_week', $dayOfWeek)
            ->with(['studentClass', 'subject'])
            ->orderBy('start_time')
            ->get();

        $stats = [
            'classes'         => $classes->count(),
            'subjects'        => Subject::where('professor_id', $prof->id)->count(),
            'assignments'     => Assignment::where('user_id', $prof->id)->count(),
            'pending_grading' => $pendingGrading,
            'notifications'   => $notifications->count(),
            'total_students'  => $classes->sum('student_details_count'),
        ];

        // Upcoming Online Course for widget
        $nextOnlineCourse = OnlineCourse::where('professor_id', $prof->id)
            ->where('end_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->first();

        return view('dashboard.professor.index', compact('stats', 'assignments', 'notifications', 'classes', 'todaySchedules', 'nextOnlineCourse'));
    }

    // ───── Classes ─────
    public function classes()
    {
        $prof = Auth::user();

        $myCourses = Course::where('professor_id', $prof->id)
            ->withCount('enrollments')
            ->get();

        // Also keep the existing official classes assigned to him
        $assignedClasses = StudentClass::with(['academicLevel', 'studentDetails.user'])
            ->where('professor_responsible', $prof->id)
            ->withCount('studentDetails')
            ->get();

        return view('dashboard.professor.classes', compact('myCourses', 'assignedClasses'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:debutant,intermediaire,avance',
            'duration' => 'nullable|string|max:100',
        ]);

        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'level' => $request->level,
            'duration' => $request->duration,
            'professor_id' => Auth::id(),
        ]);

        return back()->with('success', 'Classe/Groupe créé avec succès !');
    }

    // ───── Courses ─────
    public function courses()
    {
        $prof = Auth::user();

        $classes = StudentClass::where('professor_responsible', $prof->id)->get();
        $subjects = Subject::where('professor_id', $prof->id)->with('academicLevel')->get();

        $materials = CourseMaterial::where('user_id', $prof->id)
            ->with(['subject', 'studentClass'])
            ->latest()
            ->get();

        return view('dashboard.professor.courses', compact('materials', 'classes', 'subjects'));
    }

    public function storeCourseMaterial(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'subject_id'       => 'required|exists:subjects,id',
            'student_class_id' => 'nullable|exists:student_classes,id',
            'description'      => 'nullable|string',
            'file'             => 'nullable|file|max:10240',
            'link'             => 'nullable|url|max:255',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('course_materials', 'public');
        }

        CourseMaterial::create([
            'user_id'          => Auth::id(),
            'subject_id'       => $request->subject_id,
            'student_class_id' => $request->student_class_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'file_path'        => $filePath,
            'link'             => $request->link,
        ]);

        return back()->with('success', 'Support de cours ajouté avec succès !');
    }

    // ───── Students ─────
    public function students(Request $request)
    {
        $prof = Auth::user();
        $classId = $request->get('class_id');

        // Get IDs of classes assigned to this professor
        $myClassIds = StudentClass::where('professor_responsible', $prof->id)->pluck('id');

        $query = User::whereHas('role', fn($q) => $q->where('name', 'student'))
            ->whereHas('studentDetails', fn($q) => $q->whereIn('student_class_id', $myClassIds))
            ->with(['studentDetails.studentClass', 'studentDetails.academicLevel']);

        if ($classId && $myClassIds->contains($classId)) {
            $query->whereHas('studentDetails', fn($q) => $q->where('student_class_id', $classId));
        }

        $students = $query->get();
        $classes  = StudentClass::whereIn('id', $myClassIds)->get();

        return view('dashboard.professor.students', compact('students', 'classes', 'classId'));
    }

    // ───── Assignments ─────
    public function assignments()
    {
        $prof = Auth::user();

        $assignments = Assignment::where('user_id', $prof->id)
            ->with(['studentClass', 'subject', 'submissions'])
            ->latest()
            ->get();

        $classes  = StudentClass::where('professor_responsible', $prof->id)->get();
        $subjects = Subject::where('professor_id', $prof->id)->get();

        return view('dashboard.professor.assignments', compact('assignments', 'classes', 'subjects'));
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'student_class_id' => 'required|exists:student_classes,id',
            'subject_id'       => 'required|exists:subjects,id',
            'due_date'         => 'nullable|date',
            'description'      => 'nullable|string',
            'file'             => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        Assignment::create([
            'user_id'          => Auth::id(),
            'title'            => $request->title,
            'description'      => $request->description,
            'student_class_id' => $request->student_class_id,
            'subject_id'       => $request->subject_id,
            'due_date'         => $request->due_date,
            'max_grade'        => $request->max_grade ?? 20,
            'file_path'        => $filePath,
        ]);

        return back()->with('success', 'Devoir créé avec succès !');
    }

    public function gradeSubmission(Request $request, $submissionId)
    {
        $request->validate(['grade' => 'required|numeric|min:0|max:20', 'feedback' => 'nullable|string']);

        $submission = Submission::whereHas('assignment', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($submissionId);
            
        $submission->update(['grade' => $request->grade, 'feedback' => $request->feedback]);

        return response()->json(['success' => true]);
    }

    public function showSubmissions($assignmentId)
    {
        $assignment = Assignment::where('user_id', Auth::id())
            ->with(['submissions.student', 'studentClass'])
            ->findOrFail($assignmentId);
            
        return view('dashboard.professor.submissions', compact('assignment'));
    }

    public function allSubmissions()
    {
        $submissions = Submission::whereHas('assignment', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->with(['student', 'assignment.subject', 'assignment.studentClass'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(15);
            
        return view('dashboard.professor.all_submissions', compact('submissions'));
    }

    // ───── Attendance ─────
    public function attendance()
    {
        $prof = Auth::user();
        $classes = StudentClass::where('professor_responsible', $prof->id)->get();

        $history = AttendanceRecord::where('user_id', $prof->id)
            ->with(['studentClass', 'entries'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.professor.attendance', compact('classes', 'history'));
    }

    public function getStudentsForAttendance(Request $request)
    {
        $prof = Auth::user();
        $classId = $request->class_id;
        
        // Ensure class belongs to professor
        $class = StudentClass::where('professor_responsible', $prof->id)->findOrFail($classId);

        $students = User::whereHas('role', fn($q) => $q->where('name', 'student'))
            ->whereHas('studentDetails', fn($q) => $q->where('student_class_id', $class->id))
            ->select('id', 'first_name', 'last_name', 'email')
            ->get();

        return response()->json($students);
    }

    public function saveAttendance(Request $request)
    {
        $request->validate([
            'student_class_id' => 'required|exists:student_classes,id',
            'session_date'     => 'required|date',
            'entries'          => 'required|array',
        ]);

        $record = AttendanceRecord::create([
            'user_id'          => Auth::id(),
            'student_class_id' => $request->student_class_id,
            'session_date'     => $request->session_date,
            'session_topic'    => $request->session_topic,
        ]);

        foreach ($request->entries as $studentId => $status) {
            AttendanceEntry::create([
                'attendance_record_id' => $record->id,
                'user_id'              => $studentId,
                'status'               => $status,
            ]);
        }

        return back()->with('success', 'Présence enregistrée pour le ' . $request->session_date . ' !');
    }

    // ───── Statistics ─────
    public function statistics()
    {
        $prof = Auth::user();

        // Attendance rate overall
        $totalEntries   = AttendanceEntry::whereHas('record', fn($q) => $q->where('user_id', $prof->id))->count();
        $presentEntries = AttendanceEntry::whereHas('record', fn($q) => $q->where('user_id', $prof->id))->where('status', 'present')->count();
        $attendanceRate = $totalEntries > 0 ? round(($presentEntries / $totalEntries) * 100) : 0;

        // Assignment completion
        $totalSubmissions   = Submission::whereHas('assignment', fn($q) => $q->where('user_id', $prof->id))->count();
        $gradedSubmissions  = Submission::whereHas('assignment', fn($q) => $q->where('user_id', $prof->id))->whereNotNull('grade')->count();
        $assignmentsDone    = Assignment::where('user_id', $prof->id)->count();

        // Class Average
        $averageGrade = Submission::whereHas('assignment', fn($q) => $q->where('user_id', $prof->id))
            ->whereNotNull('grade')
            ->avg('grade');
        $averageGrade = $averageGrade ? round((float) $averageGrade, 1) : 0;

        // Per-class attendance (for chart)
        $classes = StudentClass::where('professor_responsible', $prof->id)->get();
        $classAttendance = [];
        foreach ($classes as $class) {
            $total   = AttendanceEntry::whereHas('record', fn($q) => $q->where('user_id', $prof->id)->where('student_class_id', $class->id))->count();
            $present = AttendanceEntry::whereHas('record', fn($q) => $q->where('user_id', $prof->id)->where('student_class_id', $class->id))->where('status', 'present')->count();
            $classAttendance[] = [
                'name' => $class->class_name,
                'rate' => $total > 0 ? round(($present / $total) * 100) : 0,
            ];
        }

        return view('dashboard.professor.statistics', compact(
            'attendanceRate', 'totalEntries', 'presentEntries',
            'totalSubmissions', 'gradedSubmissions', 'assignmentsDone',
            'averageGrade', 'classes', 'classAttendance'
        ));
    }

    public function onlineCourses()
    {
        $courses = OnlineCourse::where('professor_id', Auth::id())
            ->orderBy('start_date', 'asc')
            ->paginate(10);
        
        return view('dashboard.professor.online_courses', compact('courses'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        Notification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);

        return view('dashboard.professor.notifications', compact('notifications'));
    }

    public function messages()
    {
        $prof = Auth::user();
        
        // Find distinct students the professor is conversing with
        $studentIds = \App\Models\Message::where('receiver_id', $prof->id)
            ->orWhere('sender_id', $prof->id)
            ->selectRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as peer_id', [$prof->id])
            ->distinct()
            ->pluck('peer_id');
            
        $conversations = User::whereIn('id', $studentIds)
            ->with(['studentDetails.studentClass'])
            ->get()
            ->map(function ($student) use ($prof) {
                $student->last_message = \App\Models\Message::where(function($q) use ($student, $prof) {
                        $q->where('sender_id', $prof->id)->where('receiver_id', $student->id);
                    })->orWhere(function($q) use ($student, $prof) {
                        $q->where('sender_id', $student->id)->where('receiver_id', $prof->id);
                    })->orderBy('created_at', 'desc')->first();
                    
                $student->unread_count = \App\Models\Message::where('sender_id', $student->id)
                    ->where('receiver_id', $prof->id)
                    ->whereNull('read_at')
                    ->count();
                    
                return $student;
            })
            ->sortByDesc(function ($student) {
                return optional($student->last_message)->created_at;
            });

        return view('dashboard.professor.messages', compact('conversations'));
    }

    public function chat($studentId)
    {
        $prof = Auth::user();
        $student = User::with('studentDetails.studentClass')->findOrFail($studentId);

        // Mark as read
        \App\Models\Message::where('sender_id', $studentId)
            ->where('receiver_id', $prof->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = \App\Models\Message::where(function($q) use ($studentId, $prof) {
                $q->where('sender_id', $prof->id)->where('receiver_id', $studentId);
            })->orWhere(function($q) use ($studentId, $prof) {
                $q->where('sender_id', $studentId)->where('receiver_id', $prof->id);
            })->orderBy('created_at', 'asc')->get();

        return view('dashboard.professor.chat', compact('student', 'messages'));
    }

    public function sendReply(Request $request, $studentId)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        \App\Models\Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $studentId,
            'content' => $request->content,
        ]);
        
        $profName = Auth::user()->name;

        \App\Models\Notification::create([
            'user_id' => $studentId,
            'title' => 'Nouveau message de votre professeur',
            'message' => "Le professeur {$profName} a répondu à votre message.",
            'type' => 'info',
            'link' => route('dashboard.student.messages', ['receiver_id' => Auth::id()]),
        ]);

        return back();
    }

    // ───── Profile ─────
    public function profile()
    {
        $prof   = Auth::user()->load('professorDetails.subject.academicLevel', 'role');
        return view('dashboard.professor.profile', compact('prof'));
    }

    public function updateProfile(Request $request)
    {
        $prof = Auth::user();

        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $prof->id,
            'phone'         => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['first_name', 'last_name', 'email', 'phone', 'address', 'city']);
        $data['name'] = $request->first_name . ' ' . $request->last_name;

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $prof->update($data);

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    // ───── Schedule ─────
    public function schedule()
    {
        $prof = Auth::user();

        $schedules = CourseSchedule::where('user_id', $prof->id)
            ->with(['studentClass', 'subject'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('dashboard.professor.schedule', compact('schedules'));
    }
}
