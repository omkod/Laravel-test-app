<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;
use App\Enum\OfferStatus;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class OfferPolicy
 *
 * @package App\Policies
 */
class OfferPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Offer $offer): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Offer $offer): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Offer $offer): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Offer $offer): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Offer $offer): bool
    {
        //
    }

    /**
     * @param User $user
     * @param Offer $offer
     *
     * @return Response
     */
    public function accept(User $user, Offer $offer): Response
    {
        if ($offer->user->is($user)) {
            return Response::deny('You cannot accept your offer');
        }

        if ($offer->status === OfferStatus::CLOSED) {
            return Response::deny('You cannot accept closed offer');
        }
        try {
            $wallet = $user->walletByCurrency($offer->currency_to);
            if ($wallet->balance < $offer->amount_to) {
                return Response::deny('You do not have enough money to accept this offer');
            }
        } catch (ModelNotFoundException $e) {
            return Response::deny($e->getMessage());
        }

        return Response::allow();
    }
}
