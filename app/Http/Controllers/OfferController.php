<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Enum\OfferStatus;
use App\Services\OfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreOfferRequest;

/**
 * Class OfferController
 *
 * @package App\Http\Controllers
 */
class OfferController extends Controller
{

    public function __construct(private readonly OfferService $offerService)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        $offers = Offer::active()
            ->where('user_id', '!=', auth()->id())
            ->get();

        return response()->json(['success' => true, 'data' => $offers]);
    }

    /**
     * @param StoreOfferRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreOfferRequest $request): JsonResponse
    {
        Gate::authorize('create', Offer::class);

        $offer = Offer::create(
            $request->all() + [
                'user_id' => auth()->id(),
                'status'  => OfferStatus::ACTIVE,
            ]
        );

        return response()->json(['success' => true, 'data' => $offer], 201);
    }

    /**
     * @param Offer $offer
     *
     * @return JsonResponse
     */
    public function accept(Offer $offer): JsonResponse
    {
        Gate::authorize('accept', $offer);

        try {
            $this->offerService->process(auth()->user(), $offer);

            return response()->json(['success' => true, 'data' => []]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return response()->json(['success' => false, 'message' => "An error has occurred"], 500);
        }
    }
}
