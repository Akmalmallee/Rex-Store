<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->regexify('[A-Z0-9]{8}'),
            'type' => fake()->randomElement(['percentage', 'fixed']),
            'value' => fake()->randomFloat(2, 10, 100),
            'min_order' => fake()->randomElement([0, 50000, 100000, 200000]),
            'usage_limit' => fake()->numberBetween(50, 200),
            'used_count' => 0,
            'is_active' => true,
            'expires_at' => fake()->dateTimeBetween('+1 month', '+6 months'),
        ];
    }
}
