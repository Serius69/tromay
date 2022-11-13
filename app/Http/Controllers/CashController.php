<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;

class CashController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function quote(){

        $data = [
            'exchanges'  => Cash::orderBy('id','asc')->paginate(10),
        ];

        return view('quote',$data);
   }
}
