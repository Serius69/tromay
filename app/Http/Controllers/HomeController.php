<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;
use App\Models\Latest;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$currentTime = Carbon::now();
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __invoke(){
        $data = [
            'cashes'  => Cash::orderBy('id','asc')->paginate(10),
            'latests'   => Latest::orderBy('date_publication','desc')->paginate(3),
            'dollars' =>  DB::select('select * from cashes where id = 1'),
            // 'euro' =>  DB::select('select sell from cashes where id = 2')
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
            'cashes'  => Cash::orderBy('id','asc')->paginate(10),
            'latests'   => Latest::orderBy('date_publication','desc')->paginate(3),
            'query1' => DB::table('transactions')->count(),
            'query2' => DB::table('transactions')->count(),
            'query3' => DB::table('transactions')->count(),
            'query4' => DB::table('transactions')->count(),
            'query5' => DB::table('transactions')->count(),
            // 
            'query5' => DB::table('transactions')->count(),
            'query5' => DB::table('transactions')->count(),
            'query5' => DB::table('transactions')->count(),
            'query5' => DB::table('transactions')->count(),
            // 
            'query5' => DB::table('transactions')->count(),
            'query5' => DB::table('transactions')->count(),
            // 
            'transactions' => Transaction::orderBy('id','desc')->paginate(10),
            // 'monthtransactions' =>  DB::select('select count(id) from transactions where id = 1')
        ];

        return view('admin.home',$data);
        }
        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function about(){
        $data = [
            'cashes'  => Cash::orderBy('id','asc')->paginate(3),
            'dollars' =>  DB::select('select * from cashes where id = 1'),
            // 'latests'   => Latest::orderBy('id','asc')->paginate(5),
            // 'transactions' => Transaction::orderBy('id','asc')->paginate(3)
        ];

        return view('about',$data);
        }
        /**
     * Show the application terms.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function privacy(){
        $data = [
            'cashes'  => Cash::orderBy('id','asc')->paginate(3),
            'dollars' =>  DB::select('select * from cashes where id = 1'),
            // 'latests'   => Latest::orderBy('id','asc')->paginate(5),
            // 'transactions' => Transaction::orderBy('id','asc')->paginate(3)
        ];

        return view('privacy',$data);
        }
    /**
     * Show the application terms.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function terms(){
        $data = [
            'cashes'  => Cash::orderBy('id','asc')->paginate(3),
            'dollars' =>  DB::select('select * from cashes where id = 1'),
            // 'latests'   => Latest::orderBy('id','asc')->paginate(5),
            // 'transactions' => Transaction::orderBy('id','asc')->paginate(3)
        ];

        return view('terms',$data);
        }
         /**
     * Show the application terms.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contact(){
        $data = [
            'cashes'  => Cash::orderBy('id','asc')->paginate(3),
            'dollars' =>  DB::select('select * from cashes where id = 1'),
            // 'latests'   => Latest::orderBy('id','asc')->paginate(5),
            // 'transactions' => Transaction::orderBy('id','asc')->paginate(3)
        ];

        return view('contact',$data);
        }
}
