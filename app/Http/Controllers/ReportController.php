<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function dailyClose(Request $request)
    {
        $date = $request->input('date', today()->toDateString());

        $transactions = Transaction::with(['client', 'cash1', 'cash2', 'user'])
            ->whereDate('date', $date)
            ->orderBy('id')
            ->get();

        $buyTx   = $transactions->where('type', 2); // TYPE_SELL: exchange buys from client
        $sellTx  = $transactions->where('type', 1); // TYPE_BUY: exchange sells to client

        return view('admin.reports.daily_close', [
            'date'        => $date,
            'transactions'=> $transactions,
            'buy_count'   => $buyTx->count(),
            'sell_count'  => $sellTx->count(),
            'buy_volume'  => $buyTx->sum('amount2'),
            'sell_volume' => $sellTx->sum('amount2'),
            'total_count' => $transactions->count(),
        ]);
    }
}
