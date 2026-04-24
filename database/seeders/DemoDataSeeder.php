<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicLevel;
use App\Models\StudentClass;
use App\Models\StudentDetail;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();
        if (!$studentRole) {
            $studentRole = Role::create(['name' => 'student']);
        }

        $year = AcademicYear::first() ?: AcademicYear::create([
            'name' => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
            'is_active' => true,
        ]);

        // 1. Create 3 Levels
        $levelsData = [
            ['name' => '1ère année', 'code' => '1A'],
            ['name' => '2ème année', 'code' => '2A'],
            ['name' => '3ème année', 'code' => '3A'],
        ];

        $levels = [];
        foreach ($levelsData as $data) {
            $levels[] = AcademicLevel::firstOrCreate(['name' => $data['name']], $data);
        }

        // 2. Create 6 Classes (2 per level)
        $classes = [];
        foreach ($levels as $level) {
            for ($i = 1; $i <= 2; $i++) {
                $classes[] = StudentClass::create([
                    'class_name' => "Classe " . ($level->code) . "-" . $i,
                    'level_id' => $level->id,
                    'academic_year_id' => $year->id,
                    'capacity' => 30,
                ]);
            }
        }

        // 3. Create 10 Students
        $studentsNames = [
            ['first' => 'Jean', 'last' => 'Dupont'],
            ['first' => 'Marie', 'last' => 'Curie'],
            ['first' => 'Pierre', 'last' => 'Gasly'],
            ['first' => 'Alice', 'last' => 'Wonder'],
            ['first' => 'Bob', 'last' => 'Marley'],
            ['first' => 'Claire', 'last' => 'Chazal'],
            ['first' => 'David', 'last' => 'Guetta'],
            ['first' => 'Eva', 'last' => 'Longoria'],
            ['first' => 'Frank', 'last' => 'Sinatra'],
            ['first' => 'Grace', 'last' => 'Kelly'],
        ];

        foreach ($studentsNames as $index => $name) {
            $user = User::create([
                'name' => $name['first'] . ' ' . $name['last'],
                'first_name' => $name['first'],
                'last_name' => $name['last'],
                'email' => strtolower($name['first'] . '.' . $name['last'] . '@example.com'),
                'password' => Hash::make('password'),
                'role_id' => $studentRole->id,
                'status' => 'active',
                'profile_completed' => true,
            ]);

            // Assign to a class (rotating)
            $assignedClass = $classes[$index % count($classes)];

            StudentDetail::create([
                'user_id' => $user->id,
                'student_code' => 'STUD' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'academic_level_id' => $assignedClass->level_id,
                'student_class_id' => $assignedClass->id,
                'academic_year_id' => $year->id,
            ]);
        }
    }
}
