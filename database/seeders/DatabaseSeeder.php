<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Offer;
use App\Models\Wallet;
use App\Enum\Currency;
use App\Enum\OfferStatus;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->has(Wallet::factory(['currency' => Currency::USD, 'balance' => 100]))
            ->has(Wallet::factory(['currency' => Currency::RUB, 'balance' => 500]))
            ->has(
                Offer::factory([
                    'status'        => OfferStatus::ACTIVE,
                    'currency_from' => Currency::USD,
                    'currency_to'   => Currency::RUB,
                    'amount_from'   => 20,
                    'amount_to'     => 2000,
                ])
            )
            ->create(['email' => 'userA@mail.com', 'password' => '12345']);

        User::factory()
            ->has(Wallet::factory(['currency' => Currency::USD, 'balance' => 10]))
            ->has(Wallet::factory(['currency' => Currency::RUB, 'balance' => 2500]))
            ->has(Wallet::factory(['currency' => Currency::EUR, 'balance' => 400]))
            ->create(['email' => 'userB@mail.com', 'password' => '12345']);
    }
}
