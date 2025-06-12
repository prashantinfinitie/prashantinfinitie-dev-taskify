<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PwaSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pwaSettings = [
            'name' => 'Taskify',
            'short_name' => 'Taskify',
            'theme_color' => '#000000',
            'background_color' => '#ffffff',
            'logo' => ' /images/icons/logo-512x512.png ' ,
            'description' => 'A task management app to boost productivity'
        ];

        Setting::updateOrCreate(
            ['variable' => 'pwa_settings'],
            ['value' => json_encode($pwaSettings)]
        );
    }
}
