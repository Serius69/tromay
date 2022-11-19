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
        $data = Cash::latest()->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        // <td>
                        //                                     <ul class="list-inline hstack gap-2 mb-0">
                        //                                         <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                        //                                             <a href="#showModal" data-bs-toggle="modal" class="text-primary d-inline-block edit-item-btn">
                        //                                                 <i class="ri-pencil-fill fs-16"></i>
                        //                                             </a>
                        //                                         </li>
                        //                                         <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        //                                             <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#deleteRecordModal">
                        //                                                 <i class="ri-delete-bin-5-fill fs-16"></i>
                        //                                             </a>
                        //                                         </li>
                        //                                     </ul>
                        //                                 </td>
                        $btn = '
                        <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                            <a href="#showModal" data-bs-toggle="modal" data-id="'.$row->id.' class="text-primary d-inline-block edit-item-btn">
                                <i class="ri-pencil-fill fs-16"></i>
                            </a>
                        </li>
                        '

                        ;

                        $btn = $btn.'
                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                        <a class="text-danger d-inline-block remove-item-btn" data-id="'.$row->id.' data-bs-toggle="modal" href="#deleteRecordModal">
                        <i class="ri-delete-bin-5-fill fs-16"></i>
                        </a>
                        </li>
                        ';
                         return $btn;
                 })
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
