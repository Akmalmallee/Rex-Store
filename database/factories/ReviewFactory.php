<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $comments = [
            'Produknya bagus banget! Sesuai dengan deskripsi. Recommended buat kalian yang cari fashion berkualitas.',
            'Kualitasnya mantap, pengiriman cepat. Saya sangat puas dengan pembelian ini.',
            'Bahannya nyaman dipakai, ukurannya pas di badan. Bakal belanja lagi di sini.',
            'Warnanya sesuai dengan gambar. Puas banget sama kualitas jahitannya.',
            'Sudah beli beberapa kali, selalu konsisten kualitasnya. Pelayanan juga ramah.',
            'Produk original dan berkualitas. Pengiriman sangat cepat, packing rapi.',
            'Desainnya kekinian banget, cocok buat anak muda. Recommended seller!',
            'Bahan tebal dan nyaman, jahitan rapi. Worth it dengan harga segitu.',
        ];

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'comment' => fake()->randomElement($comments),
        ];
    }
}
