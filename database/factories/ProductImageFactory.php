<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image' => 'https://picsum.photos/seed/product-' . fake()->numberBetween(1, 9999) . '-img-1/800/1000',
            'is_primary' => false,
        ];
    }
}
