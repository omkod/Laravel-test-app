<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enum\Currency;
use App\Enum\OfferStatus;
use App\Services\CurrencyHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Offer
 *
 * @property OfferStatus $status
 * @property Currency $currency_from
 * @property float $amount_from
 * @property Currency $currency_to
 * @property float $amount_to
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 *
 * @method static Builder active()
 *
 * @mixin Builder
 *
 * @package App\Models
 */
class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'currency_from',
        'currency_to',
        'amount_from',
        'amount_to',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'status'        => OfferStatus::class,
            'amount_from'   => 'float',
            'amount_to'     => 'float',
            'currency_from' => Currency::class,
            'currency_to'   => Currency::class,
        ];
    }

    protected $appends = [
        'cost',
    ];

    public function getCostAttribute(): float
    {
        return $this->amount_to + CurrencyHelper::calculateCommission($this->amount_to);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', OfferStatus::ACTIVE);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
