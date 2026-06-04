<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function getImageUrl($imagePath, $model = null)
    {
        if (!$imagePath) {
            return 'https://picsum.photos/seed/default/800/1000';
        }

        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }

        $url = Storage::url($imagePath);

        // Add cache buster if model is provided
        if ($model && method_exists($model, 'getUpdatedAtColumn')) {
            $timestamp = $model->updated_at->timestamp ?? time();
            $url .= '?t=' . $timestamp;
        }

        return $url;
    }
}
