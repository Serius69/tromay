<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class DashboardController extends Controller
{

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function analytics(){

        $data = [
            'cashes'  => Cash::orderBy('id','asc')->paginate(10),
        ];

        return view('admin.analytics_dashboard',$data);
   }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function datanumber(){

       

        return view('admin.analytics_dashboard',$data);
   }

   
}
