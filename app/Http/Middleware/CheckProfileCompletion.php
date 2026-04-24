<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->profile_completed && !$request->is('logout')) {
            
            // Student logic
            if (auth()->user()->hasRole('student') && !$request->is('dashboard/student/complete-profile*')) {
                return redirect()->route('student.complete_profile')
                    ->with('warning', 'Veuillez compléter votre profil avant de continuer.');
            }

            // Professor logic
            if (auth()->user()->hasRole('professor') && !$request->is('dashboard/professor/complete-profile*')) {
                return redirect()->route('dashboard.professor.complete_profile')
                    ->with('warning', 'Veuillez compléter votre profil avant de continuer.');
            }
        }

        return $next($request);
    }
}
