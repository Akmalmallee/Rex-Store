<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(50000, 500000),
            'discount' => fake()->randomElement([0, 0, 0, 10, 15, 20, 25, 30, 50]),
            'stock' => fake()->numberBetween(10, 100),
            'thumbnail' => 'https://picsum.photos/seed/product-' . Str::slug($name) . '/800/1000',
            'rating' => fake()->randomFloat(2, 3, 5),
            'is_active' => true,
        ];
    }
}
