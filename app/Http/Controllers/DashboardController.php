<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function analytics(){

        $data = [
            'cashs'  => Cash::orderBy('id','asc')->paginate(10),
        ];

        return view('admin.analytics_dashboard',$data);
   }
}
