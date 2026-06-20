<?php

namespace App\Services\AiProviders;

use App\Models\BodyProfile;
use App\Models\Product;

interface FittingAiInterface
{
    public function analyzeBody(string $photoPath, ?BodyProfile $profile): array;

    public function recommendSize(BodyProfile $profile, Product $product): array;

    public function recommendOutfit(BodyProfile $profile, array $products): array;

    public function suggestStyle(BodyProfile $profile, array $preferences): array;
}
