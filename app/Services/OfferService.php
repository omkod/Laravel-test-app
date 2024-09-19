<?php

namespace App\Services;

use App\Models\User;
use App\Models\Offer;
use App\Enum\OfferStatus;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

/**
 * Class OfferService
 *
 * @package App\Services
 */
class OfferService
{

    /**
     * @param User $buyer
     * @param Offer $offer
     *
     * @return void
     */
    public function process(User $buyer, Offer $offer): void
    {
        DB::transaction(function () use ($buyer, $offer) {
            $seller = $offer->user;

            $commission = CurrencyHelper::calculateCommission($offer->amount_to);

            $sellerWalletFrom = $seller->walletByCurrency($offer->currency_from);
            $sellerWalletTo = $seller->walletByCurrency($offer->currency_to);
            $buyerWalletFrom = $buyer->walletByCurrency($offer->currency_from);
            $buyerWalletTo = $buyer->walletByCurrency($offer->currency_to);

            $sellerWalletFrom->remove($offer->amount_from);
            $sellerWalletTo->add($offer->amount_to);

            $buyerWalletFrom->add($offer->amount_from);
            $buyerWalletTo->remove($offer->amount_to);
            $buyerWalletTo->remove($commission);

            $offer->update(['status' => OfferStatus::CLOSED]);

            Transaction::create([
                'user_id'    => $buyer->getKey(),
                'offer_id'   => $offer->getKey(),
                'commission' => $commission,
            ]);
        });
    }
}
