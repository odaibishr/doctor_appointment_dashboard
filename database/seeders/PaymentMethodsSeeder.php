<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    $methods = [
        [
            'name' => ' (Yemen Cash)',
            'logo' => 'images/payments/yemen-cash.png',
        ],
        [
            'name' => ' (EasyCash)',
            'logo' => 'images/payments/easycash.png',
        ],
        [
            'name' => ' (Mahfathati)',
            'logo' => 'images/payments/mahfathati.png',
        ],
        [
            'name' => '(Yemen Wallet)',
            'logo' => 'images/payments/yemen-wallet.png',
        ],
        [
            'name' => 'mFloos',
            'logo' => 'images/payments/mfloos.png',
        ],
        [
            'name' => ' (Kuraimi Jawwal)',
            'logo' => 'images/payments/kuraimi-jawwal.png',
        ],
        [
            'name' => ' (Jawwal Cash)',
            'logo' => 'images/payments/jawwal-cash.png',
        ],
        [
            'name' => ' (OneCash)',
            'logo' => 'images/payments/onecash.png',
        ]
    ];

    foreach ($methods as $method) {
        \App\Models\PaymentMethod::create($method);
    }
}
}
