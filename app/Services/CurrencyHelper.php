<?php

namespace App\Services;

/**
 * Class CurrencyHelper
 *
 * @package App\Services
 */
class CurrencyHelper
{
    /**
     * @param float $amount
     * @return float
     */
    public static function calculateCommission(float $amount): float
    {
        return round($amount * (config('app.commission_percentage') / 100), 2);
    }
}
