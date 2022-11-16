<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function quote(){

        $data = [
            'cashs'  => Cash::orderBy('id','asc')->paginate(10),
            'quotation'  => Cash::orderBy('id','asc')->paginate(10),
            'dollars' =>  DB::select('select * from cashs where id = 1'),
        ];

        return view('quote',$data);
   }
}
