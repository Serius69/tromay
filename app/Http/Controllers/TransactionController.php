<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function  buy(){
        $data = [
            'cash1'  => Cash::orderBy('id','asc')->paginate(10),
            'cash2'  => Cash::orderBy('id','asc')->paginate(10)
        ];

        return view('transaction.buy',$data);
   }

       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function  sell(){
        $data = [
            'cash1'  => Cash::orderBy('id','asc')->paginate(10),
            'cash2'  => Cash::orderBy('id','asc')->paginate(10)
        ];

        return view('transaction.sell',$data);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'ci' => 'required|unique:clients|max:255',
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'ammount' => 'required',
            'finalammount' => 'required',
        ]);

    try {

                    $transaction = new Transaction;
                    $transaction->name = $request->name;
                    $transaction->media = $request->media;
                    $transaction->description = $request->description;
                    $transaction->date = $request->date;

                    $clients = DB::table('clients')->get();


                    foreach ($clients as $client)
                    {
                        if(($request->ci)==$client->ci){
                            $foo = False;
                            $client = $client->ci;
                        }
                    }
                    // client

                    if ($foo){
                        $client = new client;
                        $client->ci = $request->ci;
                        $client->name = $request->name;
                        $client->lastname = $request->lastname;
                        $client->save();
                    }
                    $transaction->client_id = $client->id;
                    $transaction->save();
                    $transactions = transaction::orderBy('id','asc')->paginate(10);

                } catch (\Exception $e) {
                    $message = $this->sendError($e->getMessage(), ['Entries could not be registered'], 500);
                }

    return view('transaction.crud',compact('transactions'));
    }


}
