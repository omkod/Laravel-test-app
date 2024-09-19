<?php

namespace App\Http\Controllers;


/**
 * Class WalletController
 *
 * @package App\Http\Controllers
 */
class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => auth()->user()->wallets
        ]);
    }
}
