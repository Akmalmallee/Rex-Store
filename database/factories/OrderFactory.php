<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100000, 1000000);
        $shippingCost = fake()->randomElement([10000, 15000, 20000, 25000, 30000]);
        $discount = fake()->randomElement([0, 0, 10000, 20000, 50000]);
        $total = $subtotal + $shippingCost - $discount;

        return [
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'paid', 'process', 'shipped', 'completed', 'cancelled']),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total' => $total,
            'address' => fake()->address(),
            'city' => fake()->city(),
            'phone' => fake()->phoneNumber(),
            'coupon_code' => fake()->optional()->randomElement(['WELCOME10', 'SALE50K', 'FREESHIP']),
            'notes' => fake()->optional()->sentence(),
            'shipping_courier' => fake()->randomElement(['JNE', 'TIKI', 'SiCepat', 'J&T', 'POS Indonesia']),
            'payment_method' => fake()->randomElement(['transfer_bank', 'cod', 'e_wallet']),
            'payment_status' => fake()->randomElement(['pending', 'success', 'failed']),
        ];
    }
}
