<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            // Home Page
            [
                'page_name' => 'home',
                'section' => 'hero',
                'title' => 'L\'excellence académique au bout des doigts',
                'content' => 'Une plateforme moderne pour gérer vos cours, vos examens et votre réussite professionnelle.',
                'image' => null,
            ],
            [
                'page_name' => 'home',
                'section' => 'mission',
                'title' => 'Notre Mission',
                'content' => 'Démocratiser l\'accès à une éducation d\'excellence.',
                'image' => null,
            ],
            [
                'page_name' => 'home',
                'section' => 'stats',
                'title' => 'Statistiques',
                'content' => json_encode([
                    'students' => '5000',
                    'experts' => '150',
                    'courses' => '45',
                    'graduates' => '98'
                ]),
                'image' => null,
            ],
            
            // About Page
            [
                'page_name' => 'about',
                'section' => 'header',
                'title' => 'À Propos de Nous',
                'content' => 'Découvrez notre histoire, notre vision et l\'équipe passionnée qui se cache derrière EduConnect.',
                'image' => null,
            ],
            [
                'page_name' => 'about',
                'section' => 'story',
                'title' => 'Former les leaders de demain',
                'content' => 'Fondée en 2010, EduConnect est née d\'une idée simple : l\'éducation de qualité doit être accessible.',
                'image' => null,
            ],
            [
                'page_name' => 'about',
                'section' => 'mission',
                'title' => 'Notre Mission',
                'content' => 'Démocratiser l\'accès à une éducation d\'excellence et doter nos étudiants des compétences clés pour une réussite professionnelle pérenne.',
                'image' => null,
            ],
            [
                'page_name' => 'about',
                'section' => 'vision',
                'title' => 'Notre Vision',
                'content' => 'Devenir le pont principal entre l\'apprentissage académique et le monde de l\'entreprise, en bâtissant une communauté mondiale de talents.',
                'image' => null,
            ],
            [
                'page_name' => 'about',
                'section' => 'values',
                'title' => 'Nos Valeurs',
                'content' => 'L\'excellence au quotidien, l\'intégrité dans nos actions, l\'innovation pédagogique et le respect de la diversité de nos apprenants.',
                'image' => null,
            ],

            // Services Page
            [
                'page_name' => 'services',
                'section' => 'header',
                'title' => 'Nos Formations',
                'content' => 'Explorez notre catalogue de formations intensives et trouvez celle qui propulsera votre carrière.',
                'image' => null,
            ],

            // Contact Page
            [
                'page_name' => 'contact',
                'section' => 'header',
                'title' => 'Contactez-Nous',
                'content' => 'Une question sur nos formations ? Besoin d\'aide pour votre inscription ? Notre équipe est à votre écoute.',
                'image' => null,
            ]
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
