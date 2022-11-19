<?php

namespace App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use DataTables;


class TransactionCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)   {
        if ($request->ajax()) {
            $data = Transaction::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn ='
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                        /div>
                        ';
                        $btn = $btn.'
                        <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                            <a href="#showModal" data-bs-toggle="modal" data-id="'.$row->id.' class="text-primary d-inline-block edit-item-btn">
                                <i class="ri-pencil-fill fs-16"></i>
                            </a>
                        </li>
                        '       ;
                        $btn = $btn.'
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <a class="text-danger d-inline-block remove-item-btn" data-id="'.$row->id.' data-bs-toggle="modal" href="#deleteRecordModal">
                        <i class="ri-delete-bin-5-fill fs-16"></i>
                        </a>
                        </li>
                        ';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('transaction.crud');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)   {
        transaction::updateOrCreate([
                    'id' => $request->transaction_id
                ],
                [
                    'fullname' => $request->name,
                    'nationality' => $request->price,
                    'city' => $request->inventory,
                    'maritalstatus' => $request->inventory,
                    'ocupation' => $request->inventory
                ]);

        return response()->json(['success'=>'transaction saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit($id)    {
        $transaction = Transaction::find($id);
        return response()->json($transaction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)    {
        transaction::find($id)->delete();
        return response()->json(['success'=>'transaction deleted successfully.']);
    }
}
