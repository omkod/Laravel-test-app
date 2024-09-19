<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enum\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 *
 * @property Currency $currency
 * @property float $balance
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 *
 * @mixin Builder
 *
 * @package App\Models
 */
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance',
    ];

    protected function casts(): array
    {
        return [
            'currency' => Currency::class,
        ];
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param float $amount
     *
     * @return void
     */
    public function add(float $amount): void
    {
        $this->update(['balance' => $this->balance + $amount]);
    }

    /**
     * @param float $amount
     *
     * @return void
     */
    public function remove(float $amount): void
    {
        $this->update(['balance' => $this->balance - $amount]);
    }
}
