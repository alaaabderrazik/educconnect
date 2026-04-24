<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\StudentClass;
use App\Models\Enrollment;
use App\Models\AcademicYear;
use App\Models\AcademicLevel;
use App\Models\Subject;
use App\Models\SiteSetting;
use App\Models\PageContent;
use App\Models\SiteService;
use App\Models\TeamMember;
use App\Models\FAQ;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::create(['name' => 'admin']);
        $profRole = Role::create(['name' => 'professor']);
        $studentRole = Role::create(['name' => 'student']);
        $parentRole = Role::create(['name' => 'parent']);

        // 2. Users
        $admin = User::create([
            'name' => 'Marc Dubois',
            'email' => 'admin@educonnect.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $prof = User::create([
            'name' => 'Dr. Alex Martin',
            'email' => 'professor@educonnect.com',
            'password' => Hash::make('password'),
            'role_id' => $profRole->id,
        ]);
        
        $prof2 = User::create([
            'name' => 'Prof. Sarah Connor',
            'email' => 'sarah@educonnect.com',
            'password' => Hash::make('password'),
            'role_id' => $profRole->id,
        ]);

        $student = User::create([
            'name' => 'Emma Blanc',
            'email' => 'student@educonnect.com',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id,
        ]);

        $parent = User::create([
            'name' => 'Lucas Blanc (Parent)',
            'email' => 'parent@educonnect.com',
            'password' => Hash::make('password'),
            'role_id' => $parentRole->id,
        ]);

        // 3. Classes
        $masterWeb = StudentClass::create([
            'name' => 'Master 2 - Dév Web',
            'year' => '2025-2026',
            'capacity' => 30,
        ]);

        $licenceData = StudentClass::create([
            'name' => 'Licence 3 - Data Science',
            'year' => '2025-2026',
            'capacity' => 40,
        ]);

        // 4. Courses
        $course1 = Course::create([
            'title' => 'Développeur Fullstack React & Laravel',
            'description' => 'Devenez un expert du développement web moderne.',
            'level' => 'debutant',
            'duration' => '12 semaines',
            'professor_id' => $prof->id,
            'total_students' => 42,
        ]);

        $course2 = Course::create([
            'title' => 'Machine Learning & Python Avancé',
            'description' => 'Création d\'algorithmes prédictifs.',
            'level' => 'intermediaire',
            'duration' => '16 semaines',
            'professor_id' => $prof2->id,
            'total_students' => 85,
        ]);

        // 5. Enrollments
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course1->id,
            'student_class_id' => $masterWeb->id,
            'status' => 'active',
        ]);
        
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course2->id,
            'student_class_id' => $licenceData->id,
            'status' => 'active',
        ]);
        // 6. Academic Years
        AcademicYear::create([
            'name' => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date' => '2026-06-30',
            'is_active' => true,
        ]);

        // 7. Academic Levels
        $l1 = AcademicLevel::create(['name' => 'Licence 1', 'code' => 'L1']);
        $l2 = AcademicLevel::create(['name' => 'Licence 2', 'code' => 'L2']);
        $l3 = AcademicLevel::create(['name' => 'Licence 3', 'code' => 'L3']);
        $m1 = AcademicLevel::create(['name' => 'Master 1', 'code' => 'M1']);
        $m2 = AcademicLevel::create(['name' => 'Master 2', 'code' => 'M2']);

        // 8. Subjects
        Subject::create(['name' => 'Algorithmique', 'code' => 'ALG101', 'academic_level_id' => $l1->id]);
        Subject::create(['name' => 'Base de données', 'code' => 'DB202', 'academic_level_id' => $l2->id]);
        Subject::create(['name' => 'Développement Web', 'code' => 'WEB303', 'academic_level_id' => $l3->id]);

        // 9. Site Settings
        SiteSetting::create(['key' => 'site_name', 'value' => 'EduConnect']);
        SiteSetting::create(['key' => 'site_email', 'value' => 'contact@educonnect.com']);
        SiteSetting::create(['key' => 'site_phone', 'value' => '+33 (0)1 23 45 67 89']);
        SiteSetting::create(['key' => 'site_address', 'value' => '123 Innovation Drive, Tech City, France']);
        SiteSetting::create(['key' => 'primary_color', 'value' => '#2563eb']);
        SiteSetting::create(['key' => 'secondary_color', 'value' => '#fbbf24']);
        SiteSetting::create(['key' => 'accent_color', 'value' => '#ec4899']);
        SiteSetting::create(['key' => 'logo_path', 'value' => 'logo.png']);
        SiteSetting::create(['key' => 'facebook_url', 'value' => '#']);
        SiteSetting::create(['key' => 'twitter_url', 'value' => '#']);
        SiteSetting::create(['key' => 'linkedin_url', 'value' => '#']);
        SiteSetting::create(['key' => 'instagram_url', 'value' => '#']);

        // 10. Page Content - HOME
        PageContent::create(['page_name' => 'home', 'section_name' => 'hero_title', 'content' => 'L\'excellence académique au bout des doigts', 'type' => 'text']);
        PageContent::create(['page_name' => 'home', 'section_name' => 'hero_description', 'content' => 'Une plateforme moderne pour gérer vos cours, vos examens et votre réussite professionnelle dans un environnement innovant.', 'type' => 'text']);
        
        PageContent::create(['page_name' => 'home', 'section_name' => 'mission_title', 'content' => 'Notre Mission', 'type' => 'text']);
        PageContent::create(['page_name' => 'home', 'section_name' => 'mission_text', 'content' => 'Démocratiser l\'accès à une éducation d\'excellence et doter nos étudiants des compétences clés.', 'type' => 'text']);
        
        PageContent::create(['page_name' => 'home', 'section_name' => 'stats_students', 'content' => '5000+', 'type' => 'text']);
        PageContent::create(['page_name' => 'home', 'section_name' => 'stats_graduates', 'content' => '98%', 'type' => 'text']);

        // 11. Page Content - ABOUT
        PageContent::create(['page_name' => 'about', 'section_name' => 'header_title', 'content' => 'À Propos de Nous', 'type' => 'text']);
        PageContent::create(['page_name' => 'about', 'section_name' => 'header_subtitle', 'content' => 'Découvrez notre histoire, notre vision et l\'équipe passionnée qui se cache derrière EduConnect.', 'type' => 'text']);
        
        PageContent::create(['page_name' => 'about', 'section_name' => 'story_title', 'content' => 'Former les leaders de demain', 'type' => 'text']);
        PageContent::create(['page_name' => 'about', 'section_name' => 'story_p1', 'content' => 'Fondée en 2010, EduConnect est née d\'une idée simple : l\'éducation de qualité doit être accessible.', 'type' => 'text']);

        // 12. Site Services
        SiteService::create(['title' => 'Développement Web', 'description' => 'Apprenez à construire des applications web modernes.', 'icon' => 'fa-code', 'is_active' => true]);
        SiteService::create(['title' => 'Intelligence Artificielle', 'description' => 'Explorez le monde passionnant de l\'IA.', 'icon' => 'fa-brain', 'is_active' => true]);
        SiteService::create(['title' => 'Data Analytics', 'description' => 'Maîtrisez l\'analyse de données.', 'icon' => 'fa-chart-line', 'is_active' => true]);

        // 13. Team Members
        TeamMember::create(['name' => 'Marc Dubois', 'position' => 'Directeur Général', 'photo_path' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400', 'linkedin_url' => '#', 'order' => 1]);
        TeamMember::create(['name' => 'Valérie Leroy', 'position' => 'Responsable Pédagogique', 'photo_path' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400', 'linkedin_url' => '#', 'order' => 2]);

        // 14. FAQs
        FAQ::create(['question' => 'Comment s\'inscrire ?', 'answer' => 'Il suffit de remplir le formulaire en ligne.', 'order' => 1]);
        FAQ::create(['question' => 'Quels sont les prérequis ?', 'answer' => 'Chaque cours a ses propres prérequis détaillés sur sa page.', 'order' => 2]);

        // 15. Testimonials
        Testimonial::create(['name' => 'Jean Martin', 'position' => 'Étudiant en L3', 'content' => 'Une expérience incroyable qui a changé ma vision du développement.', 'stars' => 5]);
        Testimonial::create(['name' => 'Sophie Bernard', 'position' => 'Alumni Master', 'content' => 'Grâce à EduConnect, j\'ai trouvé un job en moins d\'un mois.', 'stars' => 5]);
    }
}
