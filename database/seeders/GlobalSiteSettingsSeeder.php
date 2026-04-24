<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class GlobalSiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_logo', 'value' => null],
            ['key' => 'site_favicon', 'value' => null],
            ['key' => 'primary_color', 'value' => '#4338ca'],
            ['key' => 'secondary_color', 'value' => '#1e40af'],
            ['key' => 'font_family', 'value' => 'Inter, sans-serif'],
            ['key' => 'contact_email', 'value' => 'contact@educonnect.com'],
            ['key' => 'contact_phone', 'value' => '+1234567890'],
            ['key' => 'contact_address', 'value' => '123 Education St, Knowledge City'],
            ['key' => 'social_facebook', 'value' => ''],
            ['key' => 'social_twitter', 'value' => ''],
            ['key' => 'social_instagram', 'value' => ''],
            ['key' => 'social_linkedin', 'value' => ''],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
