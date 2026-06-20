<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function getImageUrl($path, $model = null)
    {
        if (!$path) return '';
        if (str_starts_with($path, 'http')) return $path;
        
        // If path is already a full storage path, use Storage::url
        if (str_starts_with($path, 'products/') || str_starts_with($path, 'banners/') || str_starts_with($path, 'payments/')) {
            return Storage::disk('public')->url($path);
        }
        
        // Fallback for backward compatibility
        return '/uploads/' . ltrim($path, '/');
    }
}
