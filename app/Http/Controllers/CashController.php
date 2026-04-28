<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use Illuminate\Http\Request;

class CashController extends Controller
{
    public function show(Cash $cash)
    {
        abort_if($cash->status !== 1, 404);

        return view('cash.show', [
            'cash'   => $cash,
            'cashes' => Cash::where('status', 1)->orderBy('id')->get(),
            'dollar' => Cash::find(1),
        ]);
    }
}
