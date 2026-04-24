<?php

namespace App\Http\Controllers;

use App\Models\OnlineCourse;
use App\Models\User;
use App\Models\StudentClass;
use App\Models\Subject;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OnlineCourseController extends Controller
{
    public function index()
    {
        $courses = OnlineCourse::with(['professor', 'studentClass', 'subject', 'creator'])->latest()->paginate(10);
        $professors = User::whereHas('role', function($q) { $q->where('name', 'professor'); })->get();
        $classes = StudentClass::all();
        $subjects = Subject::all();

        return view('dashboard.admin.online_courses.index', compact('courses', 'professors', 'classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'professor_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:student_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'course_link' => 'required|url',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('online_courses', 'public');
        }

        $course = OnlineCourse::create($data);

        // Notify Professor
        $prof = User::find($request->professor_id);
        Notification::create([
            'user_id' => $prof->id,
            'title' => 'Nouveau cours en ligne assigné',
            'message' => "Vous avez été assigné pour enseigner le cours : \n\nCours : {$course->title}\nClasse : {$course->studentClass->class_name}\nDate : {$course->start_date->format('d/m/Y H:i')}",
            'type' => 'info',
            'link' => route('dashboard.professor.online-courses'), 
        ]);

        // Notify Students in the class
        $students = User::whereHas('studentDetails', function($q) use ($request) {
            $q->where('student_class_id', $request->class_id);
        })->get();

        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->id,
                'title' => 'Nouveau cours en ligne disponible',
                'message' => "Un nouveau cours en ligne a été programmé.\n\nCours : {$course->title}\nProfesseur : {$prof->name}\nDate : {$course->start_date->format('d/m/Y H:i')}",
                'type' => 'info',
                'link' => route('dashboard.student.online-courses'),
            ]);
        }

        return redirect()->back()->with('success', 'Le cours en ligne a été publié et les notifications ont été envoyées.');
    }

    public function destroy($id)
    {
        $course = OnlineCourse::findOrFail($id);
        if ($course->attachment) {
            Storage::disk('public')->delete($course->attachment);
        }
        $course->delete();

        return redirect()->back()->with('success', 'Cours en ligne supprimé.');
    }
}
