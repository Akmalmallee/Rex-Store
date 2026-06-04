<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'method' => fake()->randomElement(['transfer_bank', 'cod', 'e_wallet']),
            'status' => fake()->randomElement(['pending', 'success', 'failed']),
            'proof_image' => fake()->optional()->imageUrl(),
            'paid_at' => fake()->optional()->dateTimeBetween('-1 month'),
        ];
    }
}
