<?php

namespace App\Http\Controllers;

use App\Models\CourseSchedule;
use App\Models\User;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseSchedule::with(['professor', 'studentClass', 'subject'])
            ->orderBy('day_of_week')
            ->orderBy('start_time');

        if ($request->filled('teacher_id')) {
            $query->where('user_id', $request->teacher_id);
        }
        if ($request->filled('class_id')) {
            $query->where('student_class_id', $request->class_id);
        }
        if ($request->filled('day')) {
            $query->where('day_of_week', $request->day);
        }

        $schedules  = $query->get();
        $professors = User::whereHas('role', fn($q) => $q->where('name', 'professor'))->orderBy('name')->get();
        $classes    = StudentClass::orderBy('class_name')->get();
        $subjects   = Subject::orderBy('subject_name')->get();

        return view('dashboard.admin.schedules', compact('schedules', 'professors', 'classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'user_id'          => 'required|exists:users,id',
            'student_class_id' => 'required|exists:student_classes,id',
            'subject_id'       => 'required|exists:subjects,id',
            'day_of_week'      => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:100',
        ]);

        CourseSchedule::create($request->all());

        // Notify professor
        \App\Models\Notification::create([
            'user_id' => $request->user_id,
            'title'   => 'Nouveau créneau ajouté',
            'message' => "Un nouveau créneau « {$request->title} » a été ajouté à votre emploi du temps.",
            'type'    => 'info',
            'link'    => route('dashboard.professor.schedule'),
            'is_read' => false,
        ]);

        return back()->with('success', 'Créneau créé avec succès !');
    }

    public function update(Request $request, $id)
    {
        $schedule = CourseSchedule::findOrFail($id);

        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'user_id'          => 'required|exists:users,id',
            'student_class_id' => 'required|exists:student_classes,id',
            'subject_id'       => 'required|exists:subjects,id',
            'day_of_week'      => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:100',
        ]);

        $schedule->update($request->all());

        // Notify professor of update
        \App\Models\Notification::create([
            'user_id' => $schedule->user_id,
            'title'   => 'Créneau modifié',
            'message' => "Votre créneau « {$schedule->title} » a été mis à jour.",
            'type'    => 'info',
            'link'    => route('dashboard.professor.schedule'),
            'is_read' => false,
        ]);

        return back()->with('success', 'Créneau mis à jour avec succès !');
    }

    public function destroy($id)
    {
        $schedule = CourseSchedule::findOrFail($id);
        $schedule->delete();
        return back()->with('success', 'Créneau supprimé avec succès !');
    }
}
