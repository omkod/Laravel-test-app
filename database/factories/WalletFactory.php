<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class WalletFactory
 *
 * @package Database\Factories
 */
class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'user_id'  => User::factory(),
            'currency' => $this->faker->randomElement(['USD', 'RUB', 'EUR']),
            'balance'  => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
