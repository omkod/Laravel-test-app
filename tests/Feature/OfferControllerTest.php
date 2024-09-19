<?php

namespace Tests\Feature;

use App\Enum\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Offer;
use App\Enum\OfferStatus;
use Laravel\Sanctum\Sanctum;

/**
 * Class OfferControllerTest
 *
 * @package Tests\Feature
 */
class OfferControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (file_exists(__DIR__.'/../../bootstrap/cache/config.php')) {
            unlink(__DIR__.'/../../bootstrap/cache/config.php');
        }

        parent::setUp();
    }

    public function test_user_can_create_offer(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->post(route('offer.store'), [
            'currency_from' => 'RUB',
            'currency_to'   => 'USD',
            'amount_from'   => 1000,
            'amount_to'     => 10,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('offers', [
            'currency_from' => 'RUB',
            'currency_to'   => 'USD',
            'amount_from'   => 1000,
            'amount_to'     => 10,
            'user_id'       => $user->id,
        ]);
    }

    public function test_user_can_view_offers(): void
    {
        $user = User::factory()->create();

        Offer::factory(10)->create(['status' => OfferStatus::ACTIVE]);

        Sanctum::actingAs($user);

        $response = $this->get(route('offer.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'status',
                    'currency_from',
                    'amount_from',
                    'currency_to',
                    'amount_to',
                    'cost',
                ],
            ],
        ]);

        $response->assertJsonCount(10, 'data');
    }

    public function test_user_cant_view_his_own_offers(): void
    {
        $user = User::factory()->has(Offer::factory(5))->create();

        Offer::factory(1)->create(['status' => OfferStatus::ACTIVE]);

        Sanctum::actingAs($user);

        $response = $this->get(route('offer.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'status',
                    'currency_from',
                    'amount_from',
                    'currency_to',
                    'amount_to',
                    'cost',
                ],
            ],
        ]);

        $response->assertJsonCount(1, 'data');

        $response->assertJsonMissing(['user_id' => $user->id]);
    }

    public function test_user_can_accept_offer(): void
    {
        $this->seed();

        $seller = User::where('email', 'userA@mail.com')->first();
        $buyer = User::where('email', 'userB@mail.com')->first();

        Sanctum::actingAs($buyer);

        $offer = $seller->offers()->first();

        $response = $this->post(route('offer.accept', ['offer' => $offer->getKey()]));

        $response->assertStatus(200);

        $this->assertDatabaseHas('wallets', [
            'user_id'  => $seller->getKey(),
            'currency' => Currency::USD,
            'balance'  => 80,
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id'  => $seller->getKey(),
            'currency' => Currency::RUB,
            'balance'  => 2500,
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id'  => $buyer->getKey(),
            'currency' => Currency::USD,
            'balance'  => 30,
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id'  => $buyer->getKey(),
            'currency' => Currency::RUB,
            'balance'  => 460,
        ]);

        $this->assertDatabaseHas('wallets', [
            'user_id'  => $buyer->getKey(),
            'currency' => Currency::EUR,
            'balance'  => 400,
        ]);

        $this->assertDatabaseHas('transactions', [
            'offer_id'   => $offer->getKey(),
            'user_id'    => $buyer->getKey(),
            'commission' => 40,
        ]);
    }
}
