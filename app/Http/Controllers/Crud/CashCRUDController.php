<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCashRequest;
use App\Models\Cash;
use DataTables;
use Illuminate\Http\Request;

class CashCRUDController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Cash::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <ul class="list-inline hstack gap-2 mb-0">
                            <li class="list-inline-item edit">
                                <a href="#showModal" data-bs-toggle="modal" data-id="' . e($row->id) . '"
                                   class="text-primary d-inline-block edit-item-btn">
                                    <i class="ri-pencil-fill fs-16"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="text-danger d-inline-block remove-item-btn" data-id="' . e($row->id) . '"
                                   data-bs-toggle="modal" href="#deleteRecordModal">
                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                </a>
                            </li>
                        </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cash.crud');
    }

    public function store(UpdateCashRequest $request)
    {
        Cash::updateOrCreate(
            ['id' => $request->cash_id],
            $request->validated(),
        );

        return response()->json(['success' => 'Divisa guardada correctamente.']);
    }

    public function edit($id)
    {
        return response()->json(Cash::findOrFail($id));
    }

    public function destroy($id)
    {
        Cash::findOrFail($id)->delete();

        return response()->json(['success' => 'Divisa eliminada correctamente.']);
    }
}
