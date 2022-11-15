<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;
use App\Models\Latest;
use App\Models\Transaction;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __invoke(){
        $data = [
            'cashs'  => Cash::orderBy('id','asc')->paginate(10),
            'latests'   => Latest::orderBy('date_publication','desc')->paginate(3)
        ];

        return view('index',$data);
   }
   /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function admin(){
        $data = [
            'cashs'  => Cash::orderBy('id','asc')->paginate(3),
            'latests'   => Latest::orderBy('id','asc')->paginate(5),
            // 'transactions' => Transaction::orderBy('id','asc')->paginate(3)
        ];

        return view('admin.home',$data);
        }
}
