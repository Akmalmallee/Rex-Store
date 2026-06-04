<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Rex Elite', 'Urban Wear', 'Classic Mode', 'Modern Fit', 'Street Soul']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'logo' => 'https://picsum.photos/seed/brand-' . Str::slug($name) . '/200/200',
            'is_active' => true,
        ];
    }
}
