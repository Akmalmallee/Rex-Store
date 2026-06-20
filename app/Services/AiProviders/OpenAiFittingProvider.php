<?php

namespace App\Services\AiProviders;

use App\Models\BodyProfile;
use App\Models\Product;

class OpenAiFittingProvider implements FittingAiInterface
{
    private ?string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('fitting.openai.api_key');
        $this->model = config('fitting.openai.model', 'gpt-4o');
    }

    public function analyzeBody(string $photoPath, ?BodyProfile $profile): array
    {
        $this->ensureApiKey();
        // TODO: Implement OpenAI vision API call for body analysis
        throw new \RuntimeException('OpenAI body analysis not yet implemented.');
    }

    public function recommendSize(BodyProfile $profile, Product $product): array
    {
        $this->ensureApiKey();
        // TODO: Implement OpenAI API call for size recommendation
        throw new \RuntimeException('OpenAI size recommendation not yet implemented.');
    }

    public function recommendOutfit(BodyProfile $profile, array $products): array
    {
        $this->ensureApiKey();
        // TODO: Implement OpenAI API call for outfit matching
        throw new \RuntimeException('OpenAI outfit recommendation not yet implemented.');
    }

    public function suggestStyle(BodyProfile $profile, array $preferences): array
    {
        $this->ensureApiKey();
        // TODO: Implement OpenAI API call for style suggestions
        throw new \RuntimeException('OpenAI style suggestion not yet implemented.');
    }

    private function ensureApiKey(): void
    {
        if (!$this->apiKey) {
            throw new \RuntimeException(
                'OpenAI API key not configured. Set OPENAI_API_KEY in your .env file or switch to the mock provider via FITTING_AI_PROVIDER=mock.'
            );
        }
    }
}
