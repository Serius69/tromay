<?php

namespace App\Http\Controllers;

use App\Models\Latest;
use App\Models\Cash;

class LatestController extends Controller
{
    public function show(Latest $latest)
    {
        abort_if($latest->status !== 1, 404);

        return view('latest.show', [
            'latest'  => $latest,
            'related' => Latest::where('status', 1)
                ->where('id', '!=', $latest->id)
                ->orderByDesc('date_publication')
                ->limit(3)
                ->get(),
            'cashes'  => Cash::where('status', 1)->orderBy('id')->get(),
            'dollar'  => Cash::where('name', 'usd')->where('status', 1)->first(),
        ]);
    }
}
