<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // General
            ['key' => 'dashboard_access',   'label' => 'Accès Dashboard',               'group' => 'Général'],
            ['key' => 'profile_management', 'label' => 'Gestion du Profil',              'group' => 'Général'],

            // User Management
            ['key' => 'user_management',     'label' => 'Gestion des Utilisateurs',      'group' => 'Utilisateurs'],
            ['key' => 'student_management',  'label' => 'Gestion des Étudiants',         'group' => 'Utilisateurs'],
            ['key' => 'professor_management','label' => 'Gestion des Professeurs',       'group' => 'Utilisateurs'],
            ['key' => 'admin_management',    'label' => 'Gestion des Administrateurs',   'group' => 'Utilisateurs'],

            // Academic
            ['key' => 'levels_management',   'label' => 'Gestion des Niveaux',           'group' => 'Académique'],
            ['key' => 'subjects_management', 'label' => 'Gestion des Matières',          'group' => 'Académique'],
            ['key' => 'years_management',    'label' => 'Gestion des Années Scolaires',  'group' => 'Académique'],

            // Site
            ['key' => 'pages_management',    'label' => 'Gestion des Pages Site',        'group' => 'Site Vitrine'],
            ['key' => 'services_management', 'label' => 'Gestion des Services',          'group' => 'Site Vitrine'],

            // System
            ['key' => 'logs_access',        'label' => 'Accès aux Logs Système',        'group' => 'Système'],
            ['key' => 'settings_access',    'label' => 'Accès aux Paramètres',          'group' => 'Système'],
            ['key' => 'roles_management',   'label' => 'Gestion des Rôles & Permissions','group' => 'Système'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['key' => $perm['key']], $perm);
        }

        // Default Role Permissions
        $adminRole     = Role::where('name', 'admin')->first();
        $professorRole = Role::where('name', 'professor')->first();
        $studentRole   = Role::where('name', 'student')->first();

        $allPermissions = Permission::all()->pluck('id')->toArray();

        $professorPerms = Permission::whereIn('key', [
            'dashboard_access',
            'profile_management',
            'levels_management',
            'subjects_management',
        ])->pluck('id')->toArray();

        $studentPerms = Permission::whereIn('key', [
            'dashboard_access',
            'profile_management',
        ])->pluck('id')->toArray();

        if ($adminRole)     $adminRole->permissions()->syncWithoutDetaching($allPermissions);
        if ($professorRole) $professorRole->permissions()->syncWithoutDetaching($professorPerms);
        if ($studentRole)   $studentRole->permissions()->syncWithoutDetaching($studentPerms);
    }
}
