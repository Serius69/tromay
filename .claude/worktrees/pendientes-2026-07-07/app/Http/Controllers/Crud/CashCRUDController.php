<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Crud\Concerns\BuildsCrudActions;
use App\Http\Requests\UpdateCashRequest;
use App\Models\Cash;
use DataTables;
use Illuminate\Http\Request;

class CashCRUDController extends Controller
{
    use BuildsCrudActions;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Cash::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', fn ($row) => $this->crudActions($row->id))
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
