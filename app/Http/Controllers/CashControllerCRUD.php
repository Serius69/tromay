<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cash;
use DataTables;

class CashControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = cash::latest()->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editcash">Edit</a>';

                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletecash">Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('cash.crud');
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
                    'fullname' => $request->name,
                    'nationality' => $request->price,
                    'city' => $request->inventory,
                    'maritalstatus' => $request->inventory,
                    'ocupation' => $request->inventory
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
