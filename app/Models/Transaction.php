<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @property int $id
 * @property float $commission
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $buyer
 * @property Offer $offer
 *
 * @mixin Builder
 *
 * @package App\Models
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'offer_id',
        'commission',
    ];

    /**
     * @return BelongsTo
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
