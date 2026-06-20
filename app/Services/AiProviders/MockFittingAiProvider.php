<?php

namespace App\Services\AiProviders;

use App\Models\BodyProfile;
use App\Models\Product;

class MockFittingAiProvider implements FittingAiInterface
{
    public function analyzeBody(string $photoPath, ?BodyProfile $profile): array
    {
        $estimatedProportions = [
            'shoulder_to_hip_ratio' => 0.85,
            'torso_length' => 'average',
            'leg_proportion' => 'balanced',
            'confidence' => 0.72,
        ];

        if ($profile) {
            $bmi = $profile->weight / (($profile->height / 100) ** 2);
            $estimatedProportions['estimated_bmi'] = round($bmi, 1);
            $estimatedProportions['body_type_detected'] = $profile->body_type ?? 'average';
            $estimatedProportions['confidence'] = 0.78;
        }

        return $estimatedProportions;
    }

    public function recommendSize(BodyProfile $profile, Product $product): array
    {
        $sizeChart = config('fitting.size_chart', []);
        $recommended = 'M';
        $confidence = 0.65;
        $reason = 'Based on your height and weight profile.';

        foreach ($sizeChart as $size) {
            if ($profile->height <= $size['height_max'] && $profile->weight <= $size['weight_max']) {
                $recommended = $size['label'];
                break;
            }
        }

        if ($profile->preferred_size) {
            $prefWeight = 0.3;
            if ($profile->preferred_size === $recommended) {
                $confidence = min(1, $confidence + 0.2);
                $reason = 'Matches your preferred size and body measurements.';
            } else {
                $confidence = min(1, $confidence + 0.1);
                $reason = "Our suggestion ({$recommended}) differs from your preference ({$profile->preferred_size}). Consider trying both.";
            }
        }

        return [
            'recommended_size' => $recommended,
            'alternative_size' => $this->getAlternativeSize($recommended),
            'fit_score' => round($confidence, 2),
            'reason' => $reason,
            'details' => [
                'height_cm' => $profile->height,
                'weight_kg' => $profile->weight,
                'body_type' => $profile->body_type ?? 'not_specified',
            ],
        ];
    }

    public function recommendOutfit(BodyProfile $profile, array $products): array
    {
        $outfits = [];
        $categories = [];

        foreach ($products as $product) {
            $cat = $product->category->name ?? 'uncategorized';
            $categories[$cat][] = $product;
        }

        $topCats = ['Shirt', 'T-Shirt', 'Blouse', 'Top', 'Jacket', 'Outerwear'];
        $bottomCats = ['Pant', 'Pants', 'Jeans', 'Shorts', 'Skirt'];
        $footwearCats = ['Shoes', 'Sneakers', 'Boots', 'Sandals'];

        $tops = [];
        $bottoms = [];
        $footwear = [];

        foreach ($products as $product) {
            $cat = $product->category->name ?? '';
            foreach ($topCats as $tc) {
                if (stripos($cat, $tc) !== false) { $tops[] = $product; break; }
            }
            foreach ($bottomCats as $bc) {
                if (stripos($cat, $bc) !== false) { $bottoms[] = $product; break; }
            }
            foreach ($footwearCats as $fc) {
                if (stripos($cat, $fc) !== false) { $footwear[] = $product; break; }
            }
        }

        if (count($tops) > 0 && count($bottoms) > 0) {
            $outfits[] = [
                'items' => [$tops[0]->id, $bottoms[0]->id],
                'score' => 0.88,
                'reason' => 'Classic combination that balances your proportions.',
                'style' => 'casual',
            ];
        }

        if (count($tops) > 0 && count($bottoms) > 0 && count($footwear) > 0) {
            $outfits[] = [
                'items' => [$tops[0]->id, $bottoms[0]->id, $footwear[0]->id],
                'score' => 0.92,
                'reason' => 'Complete head-to-toe look tailored to your body type.',
                'style' => 'complete',
            ];
        }

        if (count($bottoms) > 0 && count($footwear) > 0) {
            $outfits[] = [
                'items' => [$bottoms[0]->id, $footwear[0]->id],
                'score' => 0.75,
                'reason' => 'Bottoms and footwear that complement your silhouette.',
                'style' => 'minimal',
            ];
        }

        if (empty($outfits)) {
            $outfits = [
                [
                    'items' => array_slice(array_map(fn($p) => $p->id, $products), 0, 2),
                    'score' => 0.60,
                    'reason' => 'Selected items that suit your style preferences.',
                    'style' => 'default',
                ],
            ];
        }

        return $outfits;
    }

    public function suggestStyle(BodyProfile $profile, array $preferences): array
    {
        $bodyTypeStyles = [
            'slim' => ['Layered looks', 'Tailored fits', 'Vertical patterns'],
            'athletic' => ['Structured pieces', 'V-necks', 'Stretch fabrics'],
            'average' => ['Classic cuts', 'Balanced proportions', 'Mid-weight fabrics'],
            'plus' => ['Empire waistlines', 'A-line silhouettes', 'Dark solids'],
            'hourglass' => ['Wrap styles', 'Belted waists', 'Fitted knits'],
            'pear' => ['A-line skirts', 'Wide leg pants', 'Structured tops'],
            'apple' => ['V-necklines', 'Empire waists', 'Flowing fabrics'],
            'rectangle' => ['Belted styles', 'Peplum details', 'Layering pieces'],
        ];

        $type = $profile->body_type ?? 'average';
        $styleTips = $bodyTypeStyles[$type] ?? $bodyTypeStyles['average'];

        return [
            'body_type' => $type,
            'recommended_styles' => $styleTips,
            'avoid' => $this->getAvoidStyles($type),
            'colors' => $this->getColorPalette($preferences['color_preference'] ?? 'neutral'),
        ];
    }

    private function getAlternativeSize(string $size): string
    {
        $map = ['XS' => 'S', 'S' => 'M', 'M' => 'L', 'L' => 'XL', 'XL' => 'L'];
        return $map[$size] ?? 'M';
    }

    private function getAvoidStyles(string $bodyType): array
    {
        $avoidMap = [
            'slim' => ['Oversized cuts', 'Heavy layering'],
            'athletic' => ['Tight necklines', 'Thin straps'],
            'plus' => ['Horizontal stripes', 'Boxy cuts'],
            'hourglass' => ['Shapeless dresses', 'Boxy blazers'],
            'pear' => ['Skinny pants', 'Cropped tops'],
            'apple' => ['Tight waistbands', 'High necklines'],
            'rectangle' => ['Straight shifts', 'Unstructured pieces'],
        ];
        return $avoidMap[$bodyType] ?? ['Oversized fits'];
    }

    private function getColorPalette(string $preference): array
    {
        $palettes = [
            'warm' => ['#C8A951', '#D2691E', '#8B4513', '#CD853F'],
            'cool' => ['#2F4F4F', '#4682B4', '#708090', '#B0C4DE'],
            'neutral' => ['#1a1a1a', '#C8A951', '#ffffff', '#808080'],
            'bold' => ['#8B0000', '#2F4F4F', '#C8A951', '#000000'],
        ];
        return $palettes[$preference] ?? $palettes['neutral'];
    }
}
