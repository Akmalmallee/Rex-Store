<?php

namespace Database\Factories;

use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromoFactory extends Factory
{
    protected $model = Promo::class;

    public function definition(): array
    {
        $titles = ['Flash Sale 50%', 'Buy 1 Get 1'];

        return [
            'title' => fake()->randomElement($titles),
            'description' => fake()->sentence(),
            'image' => 'https://picsum.photos/seed/promo-' . fake()->word . '/1920/800',
            'discount_percent' => fake()->numberBetween(10, 50),
            'is_active' => true,
            'start_at' => fake()->dateTimeBetween('-1 month'),
            'end_at' => fake()->dateTimeBetween('+1 month', '+2 months'),
        ];
    }
}
