<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\FittingAssistantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FittingAssistantController extends Controller
{
    public function __construct(
        private FittingAssistantService $fittingService
    ) {}

    public function profile(Request $request): View
    {
        $profile = $this->fittingService->getProfile($request->user());
        return view('user.fitting-assistant.profile', compact('profile'));
    }

    public function saveProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:300',
            'body_type' => 'nullable|in:slim,average,athletic,plus,hourglass,pear,apple,rectangle',
            'preferred_size' => 'nullable|string|max:10',
            'shoulder_width' => 'nullable|numeric|min:20|max:80',
            'chest_circumference' => 'nullable|numeric|min:50|max:200',
            'waist_circumference' => 'nullable|numeric|min:40|max:200',
            'hip_circumference' => 'nullable|numeric|min:50|max:200',
            'inseam' => 'nullable|numeric|min:30|max:120',
        ]);

        $this->fittingService->getOrCreateProfile($request->user(), $validated);

        return redirect()->back()->with('success', 'Body profile saved successfully.');
    }

    public function photoPage(): View
    {
        return view('user.fitting-assistant.photo');
    }

    public function index(Request $request, string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->with(['sizes', 'colors', 'images', 'category', 'reviews.user'])
            ->firstOrFail();

        $profile = $this->fittingService->getProfile($request->user());
        $recommendations = null;

        if ($profile) {
            $recommendations = $this->fittingService->getRecommendation($request->user(), $product);
        }

        return view('user.product-detail', compact('product', 'profile', 'recommendations'));
    }

    public function getRecommendations(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $result = $this->fittingService->getRecommendation($request->user(), $product);

        return response()->json($result);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $photo = $this->fittingService->uploadPhoto($request->user(), $request->file('photo'));

        return response()->json([
            'id' => $photo->id,
            'url' => $photo->photo_path,
            'analysis' => $photo->body_analysis,
            'message' => 'Photo uploaded successfully.',
        ]);
    }

    public function history(Request $request)
    {
        $sessions = $request->user()->fittingSessions()
            ->with('product')
            ->latest()
            ->take(20)
            ->get();

        $recommendations = $this->fittingService->getUserRecommendations($request->user());
        $styleTips = $this->fittingService->getStyleSuggestions($request->user());

        return view('user.fitting-assistant.history', compact('sessions', 'recommendations', 'styleTips'));
    }

    public function dismissRecommendation(Request $request, int $id): RedirectResponse
    {
        $this->fittingService->dismissRecommendation($id);
        return redirect()->back()->with('success', 'Recommendation dismissed.');
    }

    public function feedback(Request $request, int $sessionId): RedirectResponse
    {
        $request->validate(['feedback' => 'required|string|max:500']);
        $this->fittingService->recordFeedback($sessionId, $request->input('feedback'));
        return redirect()->back()->with('success', 'Thank you for your feedback.');
    }
}
