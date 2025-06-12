<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\PwaManifestService;

class PwaManifestController extends Controller
{
    protected $pwaManifestService;

    public function __construct(PwaManifestService $pwaManifestService)
    {
        $this->pwaManifestService = $pwaManifestService;
    }

    /**
     * Serve the PWA manifest as JSON.
     */
    public function manifest()
    {
        try {
            $manifest = $this->pwaManifestService->generateManifest();

            // Validation: ensure required fields exist
            $this->validateManifest($manifest);

            // Always return valid JSON with correct headers
            return response()->json($manifest, 200, [
                'Content-Type' => 'application/manifest+json',
                'Cache-Control' => 'public, max-age=3600'
            ]);
        } catch (\Throwable $th) {
            Log::error('PWA Manifest Error: ' . $th->getMessage());

            // Fallback minimal manifest
            $fallback = [
                'name' => 'Taskify',
                'short_name' => 'Taskify',
                'start_url' => '/home',
                'display' => 'standalone',
                'theme_color' => '#000000',
                'background_color' => '#ffffff',
                'icons' => [
                    [
                        'src' => '/storage/images/icons/logo-512x512.png',
                        'sizes' => '512x512',
                        'type' => 'image/png'
                    ]
                ]
            ];
            return response()->json($fallback, 200, [
                'Content-Type' => 'application/manifest+json'
            ]);
        }
    }

    /**
     * Validate the manifest structure. Throws if invalid.
     */
    private function validateManifest($manifest)
    {
        $requiredFields = ['name', 'short_name', 'start_url', 'display', 'theme_color', 'background_color', 'icons'];
        foreach ($requiredFields as $field) {
            if (!isset($manifest[$field])) {
                throw new \Exception("Manifest missing required field: $field");
            }
        }
        // Icons must be an array with at least one valid item
        if (!is_array($manifest['icons']) || empty($manifest['icons'])) {
            throw new \Exception("Manifest icons missing or not an array");
        }
        foreach ($manifest['icons'] as $icon) {
            if (!isset($icon['src'], $icon['sizes'], $icon['type'])) {
                throw new \Exception("Manifest icon missing required properties");
            }
        }
    }
}
