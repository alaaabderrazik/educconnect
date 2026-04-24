<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function showCompleteProfile()
    {
        $user = auth()->user();
        
        if ($user->profile_completed) {
            return redirect()->route('dashboard.student');
        }

        // Calculate progress
        $fields = ['email', 'phone', 'address', 'dob', 'gender'];
        $filledCount = 0;
        foreach ($fields as $field) {
            if ($user->$field) $filledCount++;
        }
        
        $progress = ($filledCount / count($fields)) * 100;

        return view('dashboard.student.complete_profile', compact('user', 'progress'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'dob' => 'required|date',
            'gender' => 'required|in:M,F',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'profile_completed' => true,
        ]);

        return redirect()->route('dashboard.student')
            ->with('success', 'Votre profil a été complété avec succès ! Bienvenue sur votre tableau de bord.');
    }
}
