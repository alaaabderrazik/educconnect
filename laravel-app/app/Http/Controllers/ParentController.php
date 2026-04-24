<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Submission;

class ParentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get children (Students linked to this parent)
        $childrenDetails = $user->childrenDetails()->with(['user', 'studentClass'])->get();
        $childrenIds = $childrenDetails->pluck('user_id');

        // Stats basics
        $childrenCount = $childrenDetails->count();
        
        // For simplicity in this initial version, we'll focus on the first child or aggregate
        // Let's get the first child's data for the detailed tracking part
        $firstChild = $childrenDetails->first();
        $coursesCount = 0;
        $globalProgress = 0;
        $lastActivity = "N/A";
        $coursesData = collect();
        $performanceData = collect();
        $needsToStudyAlert = false;

        if ($firstChild) {
            $studentUser = $firstChild->user;
            
            // Enrollment count
            $enrollments = Enrollment::where('student_id', $studentUser->id)->with('course')->get();
            $coursesCount = $enrollments->count();

            // Mock progress calculation (since we don't have a full lesson tracking system yet)
            // We can base it on submissions vs assignments or just mock it for the UI demo
            $globalProgress = 65; // Mock

            // Last activity (latest submission)
            $lastSubmission = Submission::where('user_id', $studentUser->id)->latest('submitted_at')->first();
            if ($lastSubmission) {
                $lastActivity = $lastSubmission->submitted_at->diffForHumans();
                
                // Alert if no activity today
                if ($lastSubmission->submitted_at->lt(now()->startOfDay())) {
                    $needsToStudyAlert = true;
                }
            } else {
                $needsToStudyAlert = true;
            }

            // Courses for the list
            foreach ($enrollments as $enrollment) {
                $coursesData->push([
                    'title' => $enrollment->course->title ?? 'Cours',
                    'progress' => rand(30, 90), // Mock progress
                    'last_lesson' => 'Introduction'
                ]);
            }

            // Mock performance
            $performanceData = [
                'average' => 14.2,
                'last_quiz' => 85,
                'global_score' => 750
            ];
        }

        return view('dashboard.parent.index', compact(
            'childrenCount',
            'coursesCount',
            'globalProgress',
            'lastActivity',
            'firstChild',
            'coursesData',
            'performanceData',
            'needsToStudyAlert'
        ));
    }
}
