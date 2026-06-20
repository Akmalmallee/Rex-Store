<?php

namespace App\Services;

use App\Models\BodyProfile;
use App\Models\FittingSession;
use App\Models\Product;
use App\Models\Recommendation;
use App\Models\User;
use App\Models\UserPhoto;
use App\Services\AiProviders\FittingAiInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FittingAssistantService
{
    public function __construct(
        private FittingAiInterface $aiProvider
    ) {}

    public function getOrCreateProfile(User $user, array $data): BodyProfile
    {
        return BodyProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
    }

    public function getProfile(User $user): ?BodyProfile
    {
        return $user->bodyProfile;
    }

    public function uploadPhoto(User $user, UploadedFile $photo): UserPhoto
    {
        $path = $photo->store('fitting-photos', 'public');

        $activePhotos = $user->fittingPhotos()->where('is_active', true);
        if ($activePhotos->count() >= 3) {
            $oldest = $activePhotos->orderBy('created_at')->first();
            if ($oldest) {
                Storage::disk('public')->delete($oldest->photo_path);
                $oldest->delete();
            }
        }

        $analysis = $this->aiProvider->analyzeBody(
            Storage::disk('public')->path($path),
            $user->bodyProfile
        );

        return $user->fittingPhotos()->create([
            'photo_path' => $path,
            'body_analysis' => $analysis,
            'is_active' => true,
        ]);
    }

    public function getRecommendation(User $user, Product $product): array
    {
        $profile = $user->bodyProfile;

        if (!$profile) {
            return [
                'requires_profile' => true,
                'message' => 'Please complete your body profile first.',
            ];
        }

        $sizeRec = $this->aiProvider->recommendSize($profile, $product);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(5)
            ->get();

        $outfits = $relatedProducts->isNotEmpty()
            ? $this->aiProvider->recommendOutfit($profile, $relatedProducts->prepend($product)->all())
            : [];

        $session = $user->fittingSessions()->create([
            'product_id' => $product->id,
            'size_recommended' => $sizeRec['recommended_size'] ?? null,
            'fit_score' => $sizeRec['fit_score'] ?? null,
            'recommendations' => ['size' => $sizeRec, 'outfits' => $outfits],
            'status' => 'completed',
        ]);

        if (isset($sizeRec['recommended_size'])) {
            $user->recommendations()->create([
                'product_ids' => [$product->id],
                'reason' => $sizeRec['reason'] ?? '',
                'score' => $sizeRec['fit_score'] ?? 0,
                'type' => 'size',
            ]);
        }

        return [
            'session_id' => $session->id,
            'size_recommendation' => $sizeRec,
            'outfit_recommendations' => $outfits,
            'related_products' => $relatedProducts,
        ];
    }

    public function getUserRecommendations(User $user, int $limit = 10)
    {
        return $user->recommendations()
            ->where('is_dismissed', false)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getStyleSuggestions(User $user): array
    {
        $profile = $user->bodyProfile;
        if (!$profile) {
            return [];
        }

        $sessions = $user->fittingSessions()
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        $viewedCategories = $sessions->pluck('product.category_id')->unique()->filter()->values()->toArray();

        return $this->aiProvider->suggestStyle($profile, [
            'color_preference' => 'neutral',
            'recent_categories' => $viewedCategories,
        ]);
    }

    public function dismissRecommendation(int $recommendationId): void
    {
        Recommendation::where('id', $recommendationId)->update(['is_dismissed' => true]);
    }

    public function recordFeedback(int $sessionId, string $feedback): void
    {
        FittingSession::where('id', $sessionId)->update(['user_feedback' => $feedback]);
    }
}
