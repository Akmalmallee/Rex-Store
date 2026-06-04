<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        $titles = ['New Collection 2025', 'Summer Sale', 'Premium Fashion'];
        $title = fake()->randomElement($titles);

        return [
            'title' => $title,
            'subtitle' => fake()->sentence(),
            'image' => 'https://picsum.photos/seed/banner-' . str_replace(' ', '-', $title) . '/1920/800',
            'link' => fake()->optional()->url(),
            'is_active' => true,
        ];
    }
}
