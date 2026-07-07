<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use DataTables;
use Illuminate\Http\Request;

class ClientCRUDController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Use query builder (not ->get()) so Datatables handles server-side pagination
            $query = Client::query()->select(['id', 'ci', 'name', 'lastname', 'status', 'created_at']);

            return Datatables::of($query)
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

        return view('client.crud');
    }

    public function store(StoreClientRequest $request)
    {
        Client::updateOrCreate(
            ['id' => $request->client_id],
            $request->validated(),
        );

        return response()->json(['success' => 'Cliente guardado correctamente.']);
    }

    public function search(Request $request)
    {
        $term = $request->string('q')->trim()->toString();

        if (mb_strlen($term) < 2) {
            return response()->json(['error' => 'Mínimo 2 caracteres para buscar.'], 422);
        }

        return response()->json(
            Client::searchByCi($term)->limit(10)->get(['id', 'ci', 'name', 'lastname'])
        );
    }

    public function edit($id)
    {
        return response()->json(Client::findOrFail($id));
    }

    public function destroy($id)
    {
        Client::findOrFail($id)->delete();

        return response()->json(['success' => 'Cliente eliminado correctamente.']);
    }
}
