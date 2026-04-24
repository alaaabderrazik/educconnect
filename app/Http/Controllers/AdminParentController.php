<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicLevel;
use App\Models\StudentClass;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminParentController extends Controller
{
    public function create()
    {
        $levels = AcademicLevel::all();
        $classes = StudentClass::all();
        return view('dashboard.admin.parents.create', compact('levels', 'classes'));
    }

    public function searchStudents(Request $request)
    {
        $levelId = $request->level_id;
        $classId = $request->class_id;
        $search = $request->search;

        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'student');
        })->with(['studentDetails.academicLevel', 'studentDetails.studentClass']);

        if ($levelId) {
            $query->whereHas('studentDetails', function ($q) use ($levelId) {
                $q->where('academic_level_id', $levelId);
            });
        }

        if ($classId) {
            $query->whereHas('studentDetails', function ($q) use ($classId) {
                $q->where('student_class_id', $classId);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('studentDetails', function ($sq) use ($search) {
                      $sq->where('student_code', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->take(20)->get();

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_id' => 'required|exists:users,id',
        ]);

        $role = Role::where('name', 'parent')->first();
        $password = Str::random(10);

        $parent = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => $role->id,
            'status' => 'active',
            'profile_completed' => true,
        ]);

        // Link student to parent
        $student = User::findOrFail($request->student_id);
        if ($student->studentDetails) {
            $student->studentDetails->update(['parent_id' => $parent->id]);
        }

        return back()->with('success', "Parent créé avec succès ! Identifiants envoyés : Email: {$request->email}, Password: {$password}");
    }
}
