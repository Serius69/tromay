<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function  buy(){
        $data = [
            'cashs'  => Cash::orderBy('id','asc')->paginate(10)
        ];

        return view('transaction.buy',$data);
   }


}
