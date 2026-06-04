<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductColorFactory extends Factory
{
    protected $model = ProductColor::class;

    public function definition(): array
    {
        $colors = [
            'Hitam' => '#000000',
            'Putih' => '#FFFFFF',
            'Abu' => '#808080',
            'Navy' => '#000080',
            'Maroon' => '#800000',
            'Hijau Army' => '#4B5320',
        ];
        $name = fake()->randomElement(array_keys($colors));

        return [
            'product_id' => Product::factory(),
            'color' => $name,
            'color_code' => $colors[$name],
            'stock' => fake()->numberBetween(5, 30),
        ];
    }
}
