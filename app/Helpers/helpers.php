<?php

if (!function_exists('image_url')) {
    function image_url($imagePath, $model = null)
    {
        return \App\Helpers\ImageHelper::getImageUrl($imagePath, $model);
    }
}
