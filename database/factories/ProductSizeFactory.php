<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSizeFactory extends Factory
{
    protected $model = ProductSize::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'stock' => fake()->numberBetween(5, 30),
        ];
    }
}
