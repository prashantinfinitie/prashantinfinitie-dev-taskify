<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class PwaManifestService
{
    /**
     * Generate the manifest array by merging static config with DB settings.
     */
    public function generateManifest()
    {
        // Pull DB settings (cached for 1 hour)
        $pwaSettings = Cache::remember('pwa_settings', 3600, function () {
            $setting = Setting::where('variable', 'pwa_settings')->first();
            return $setting ? json_decode($setting->value, true) : [];
        });

        // Static defaults from config
        $static = config('laravelpwa.manifest', []);

        // Merge DB over static (DB wins)
        $manifest = array_merge($static, $pwaSettings);

        // Ensure required fields are present and correct types
        $manifest['name'] = (string) Arr::get($manifest, 'name', 'Taskify');
        $manifest['short_name'] = (string) Arr::get($manifest, 'short_name', 'Taskify');
        $manifest['theme_color'] = (string) Arr::get($manifest, 'theme_color', '#000000');
        $manifest['background_color'] = (string) Arr::get($manifest, 'background_color', '#ffffff');
        $manifest['description'] = (string) Arr::get($manifest, 'description', 'A task management app to boost productivity');
        $manifest['display'] = (string) Arr::get($manifest, 'display', 'standalone');
        $manifest['start_url'] = (string) Arr::get($manifest, 'start_url', '/home');

        // Handle icons from logo
        $logo = Arr::get($manifest, 'logo', '/storage/images/icons/logo-512x512.png');
        if (!str_starts_with($logo, '/')) $logo = '/' . ltrim($logo, '/');
        $manifest['icons'] = [
            [
                'src' => $logo,
                'sizes' => '192x192',
                'type' => 'image/png'
            ],
            [
                'src' => $logo,
                'sizes' => '512x512',
                'type' => 'image/png'
            ]
        ];

        // Remove properties not in manifest spec
        unset($manifest['logo'], $manifest['splash'], $manifest['status_bar'], $manifest['custom']);

        // Remove nulls and trim strings
        return $this->sanitizeManifest($manifest);
    }

    private function sanitizeManifest($manifest)
    {
        foreach ($manifest as $key => $value) {
            if (is_array($value)) {
                $manifest[$key] = $this->sanitizeManifest($value);
            } elseif (is_null($value)) {
                unset($manifest[$key]);
            } elseif (is_string($value)) {
                $manifest[$key] = trim($value);
            }
        }
        return $manifest;
    }
}
