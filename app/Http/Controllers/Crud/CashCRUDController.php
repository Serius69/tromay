<?php

namespace App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cash;
use DataTables;

class CashCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cash = cash::latest()->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
        return view('cash.crud',$cash);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        cash::updateOrCreate([
                    'id' => $request->cash_id
                ],
                [
                    'name' => $request->name,
                    'buy' => $request->buy,
                    'sell' => $request->sell,
                    'oficial' => $request->oficial,
                    'status' => $request->status
                ]);

        return response()->json(['success'=>'cash saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cash = cash::find($id);
        return response()->json($cash);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        cash::find($id)->delete();

        return response()->json(['success'=>'cash deleted successfully.']);
    }
}
