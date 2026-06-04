<?php

namespace Database\Seeders;

use App\Models\PaymentAccount;
use Illuminate\Database\Seeder;

class PaymentAccountSeeder extends Seeder
{
    public function run(): void
    {
        $paymentAccounts = [
            [
                'method' => 'Bank Transfer',
                'account_name' => 'Bank BCA a.n. Rex Fashion',
                'account_number' => '1234567890',
                'instructions' => 'Mohon transfer sesuai dengan nominal di atas. Pesanan akan diproses setelah pembayaran dikonfirmasi.',
                'active' => true,
            ],
            [
                'method' => 'GCash',
                'account_name' => 'GCash Account',
                'account_number' => '09123456789',
                'instructions' => 'Silakan kirim pembayaran melalui aplikasi GCash',
                'active' => true,
            ],
            [
                'method' => 'PayMaya',
                'account_name' => 'PayMaya Account',
                'account_number' => '09987654321',
                'instructions' => 'Silakan kirim pembayaran melalui aplikasi PayMaya',
                'active' => true,
            ],
        ];

        foreach ($paymentAccounts as $account) {
            PaymentAccount::updateOrCreate(
                ['method' => $account['method']],
                $account
            );
        }
    }
}
