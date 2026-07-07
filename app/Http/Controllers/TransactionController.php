<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Cash;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private TransactionService $txService) {}

    public function buy()
    {
        return view('transaction.buy', [
            'cashes' => Cash::active()->orderBy('id')->get(),
        ]);
    }

    public function sell()
    {
        return view('transaction.sell', [
            'cashes' => Cash::active()->orderBy('id')->get(),
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = $this->txService->register($request->validated());

        return response()->json([
            'success'     => 'Transacción registrada correctamente.',
            'transaction' => $transaction->load(['client', 'cash1', 'cash2']),
        ]);
    }
}
