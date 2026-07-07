<?php

namespace App\Http\Controllers;

use App\Models\Latest;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    public function rss(): Response
    {
        $items = Latest::published()
            ->orderByDesc('date_publication')
            ->limit(20)
            ->get();

        $content = view('feed', ['items' => $items])->render();

        return response($content, 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8',
        ]);
    }
}
