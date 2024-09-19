<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Offer;
use App\Enum\Currency;
use App\Enum\OfferStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class OfferFactory
 *
 * @package Database\Factories
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        $currencies = Currency::cases();

        shuffle($currencies);

        return [
            'user_id'       => User::factory(),
            'status'        => $this->faker->randomElement(OfferStatus::cases()),
            'currency_from' => array_shift($currencies),
            'currency_to'   => array_shift($currencies),
            'amount_from'   => $this->faker->randomFloat(2, 1, 1000),
            'amount_to'     => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
