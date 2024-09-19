<?php

namespace Tests\Unit;

use App\Services\OfferService;
use Tests\TestCase;
use App\Services\CurrencyHelper;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Class CCurrencyHelperTest
 *
 * @package Tests\Unit
 */
class CurrencyHelperTest extends TestCase
{

    #[DataProvider('commission_data_provider')]
    public function test_calculate_commission($amount, $percent, $expected): void
    {
        Config::set('app.commission_percentage', $percent);

        $service = new OfferService();

        $this->assertEquals($expected, CurrencyHelper::calculateCommission($amount));
    }

    public static function commission_data_provider(): array
    {
        return [
            [1000, 10, 100],
            [1, 100, 1],
            [0, 100, 0],
            [0, 0, 0],
            [200.50, 20, 40.1],
            [33.33, 3, 1],
        ];
    }
}
