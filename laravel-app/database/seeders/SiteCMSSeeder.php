<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageContent;
use App\Models\SiteService;
use App\Models\SiteSetting;
use App\Models\FAQ;
use App\Models\Testimonial;
use App\Models\TeamMember;

class SiteCMSSeeder extends Seeder
{
    public function run(): void
    {
        // --- Page Contents ---
        $pages = [
            'home' => [
                'hero_title' => 'L\'excellence académique au bout des doigts',
                'hero_description' => 'Une plateforme moderne pour gérer vos cours, vos examens et votre réussite professionnelle.',
                'mission_title' => 'Notre Mission',
                'mission_text' => 'Démocratiser l\'accès à une éducation d\'excellence en utilisant les technologies de pointe.',
                'vision_text' => 'Devenir le pont principal entre l\'apprentissage et l\'entreprise.',
                'values_text' => 'L\'excellence, l\'intégrité, l\'innovation et le respect.',
                'stats_students' => '5000',
                'stats_graduates' => '98',
            ],
            'about' => [
                'header_title' => 'À Propos de Nous',
                'header_subtitle' => 'Découvrez notre histoire, notre vision et l\'équipe passionnée qui se cache derrière EduConnect.',
                'story_title' => 'Former les leaders de demain',
                'story_p1' => 'Fondée en 2010, EduConnect est née d\'une idée simple : l\'éducation de qualité doit être accessible à tous, partout.',
                'story_p2' => 'Depuis plus d\'une décennie, nous innovons dans les méthodes pédagogiques pour offrir une expérience d\'apprentissage unique et efficace.',
            ],
            'contact' => [
                'header_title' => 'Contactez-nous',
                'header_subtitle' => 'Nous sommes à votre écoute pour toute question ou suggestion.',
            ]
        ];

        foreach ($pages as $page => $sections) {
            foreach ($sections as $section => $content) {
                PageContent::updateOrCreate(
                    ['page_name' => $page, 'section_name' => $section],
                    ['content' => $content, 'type' => 'text']
                );
            }
        }

        // --- Site Settings ---
        $settings = [
            'school_name' => 'EduConnect High School',
            'contact_address' => '123 Rue de l\'Éducation, Casablanca, Maroc',
            'contact_phone' => '+212 5 22 00 00 00',
            'contact_email' => 'contact@educonnect.ma',
            'google_maps_link' => 'https://www.google.com/maps/embed?pb=...',
            'facebook_url' => 'https://facebook.com/educonnect',
            'instagram_url' => 'https://instagram.com/educonnect',
            'linkedin_url' => 'https://linkedin.com/school/educonnect',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // --- Services ---
        $services = [
            [
                'title' => 'Informatique & Web',
                'description' => 'Apprenez les langages de programmation les plus demandés du marché.',
                'icon' => 'fa-laptop-code',
                'image' => 'services/computer.jpg',
                'related_course' => 'Fullstack Developer',
                'professor_name' => 'Dr. Ahmed Alami',
            ],
            [
                'title' => 'Design Graphique',
                'description' => 'Maîtrisez les outils de création visuelle et le design UI/UX.',
                'icon' => 'fa-palette',
                'image' => 'services/design.jpg',
                'related_course' => 'Graphic Design Pro',
                'professor_name' => 'Mme Sara Benani',
            ],
            [
                'title' => 'Business & Marketing',
                'description' => 'Développez des stratégies gagnantes pour le monde des affaires.',
                'icon' => 'fa-chart-line',
                'image' => 'services/business.jpg',
                'related_course' => 'Digital Marketing',
                'professor_name' => 'Mr. Yassine Tazi',
            ],
        ];

        foreach ($services as $service) {
            SiteService::updateOrCreate(['title' => $service['title']], $service);
        }

        // --- FAQs ---
        $faqs = [
            ['question' => 'Comment s\'inscrire ?', 'answer' => 'Vous pouvez vous inscrire directement via le bouton d\'inscription sur la page d\'accueil.'],
            ['question' => 'Quels sont les modes de paiement ?', 'answer' => 'Nous acceptons les virements bancaires, cartes de crédit et paiements en espèces.'],
        ];

        foreach ($faqs as $faq) {
            FAQ::updateOrCreate(['question' => $faq['question']], $faq);
        }

        // --- Testimonials ---
        $testimonials = [
            [
                'name' => 'Karim Idrissi',
                'position' => 'Étudiant en Informatique',
                'content' => 'Une expérience incroyable qui a changé ma vision de l\'apprentissage.',
                'stars' => 5,
            ],
            [
                'name' => 'Laila Mansouri',
                'position' => 'Diplômée en Design',
                'content' => 'Grâce à EduConnect, j\'ai pu décrocher mon premier emploi en moins de 3 mois.',
                'stars' => 5,
            ],
        ];

        foreach ($testimonials as $test) {
            Testimonial::updateOrCreate(['name' => $test['name']], $test);
        }
        
        // --- Team Members ---
        $team = [
            ['name' => 'Dr. Khalid Mansouri', 'position' => 'Directeur Général', 'order' => 1],
            ['name' => 'Mme Salma Alami', 'position' => 'Directrice Pédagogique', 'order' => 2],
        ];
        
        foreach($team as $member) {
            TeamMember::updateOrCreate(['name' => $member['name']], $member);
        }
    }
}
