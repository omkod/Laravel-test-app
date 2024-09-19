<?php

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\WalletController;

Route::post('/login', function (LoginRequest $request) {
    $user = auth()->attempt($request->only(['email', 'password']));
    if ($user) {
        $token = $request->user()->createToken('apiToken');

        return [
            'success' => true,
            'data' => [
                'token' => $token->plainTextToken,
            ]
        ];
    }

    return response()->json(['error' => 'Invalid credentials'], 401);
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/user', function (Request $request) {
        return [
            'success' => true,
            'data' => $request->user()
        ];
    });

    Route::post('offer/accept/{offer}', [OfferController::class, 'accept'])->name('offer.accept');
    Route::resource('offer', OfferController::class)->only(['index', 'store']);
    Route::resource('wallet', WalletController::class)->only(['index']);
});





