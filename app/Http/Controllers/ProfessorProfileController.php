<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfessorProfileController extends Controller
{
    public function showCompleteProfile()
    {
        $user = auth()->user()->load('professorDetails');
        
        if ($user->profile_completed) {
            return redirect()->route('dashboard.professor');
        }

        // Calculate progress
        $fields = ['email', 'phone', 'address', 'bio'];
        $filledCount = 0;
        foreach ($fields as $field) {
            if ($field === 'bio') {
                if ($user->professorDetails && $user->professorDetails->bio) $filledCount++;
            } else {
                if ($user->$field) $filledCount++;
            }
        }
        
        $progress = ($filledCount / count($fields)) * 100;

        return view('dashboard.professor.complete_profile', compact('user', 'progress'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'bio' => 'required|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'profile_completed' => true,
        ]);

        $user->professorDetails()->update([
            'bio' => $request->bio,
        ]);

        return redirect()->route('dashboard.professor')
            ->with('success', 'Votre profil a été complété avec succès ! Bienvenue sur votre tableau de bord.');
    }
}
