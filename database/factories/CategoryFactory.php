<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Baju', 'Celana', 'Jacket', 'Topi']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => 'Koleksi ' . strtolower($name) . ' fashion terbaru dengan kualitas terbaik dan desain modern.',
            'image' => 'https://picsum.photos/seed/category-' . Str::slug($name) . '/400/400',
            'is_active' => true,
        ];
    }
}
