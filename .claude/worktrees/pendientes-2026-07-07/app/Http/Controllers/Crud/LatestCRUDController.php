<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Crud\Concerns\BuildsCrudActions;
use App\Http\Requests\StoreLatestRequest;
use App\Models\Latest;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LatestCRUDController extends Controller
{
    use BuildsCrudActions;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Latest::latest()->get();

            return DataTables::of($data)
                ->addColumn('check', function ($row) {
                    return '<div class="form-check">
                        <input class="form-check-input" type="checkbox" name="chk_child" value="' . e($row->id) . '">
                    </div>';
                })
                ->addIndexColumn()
                ->addColumn('action', fn ($row) => $this->crudActions($row->id))
                ->rawColumns(['check', 'action'])
                ->make(true);
        }

        return view('latest.crud');
    }

    public function store(StoreLatestRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('path')) {
            $path = $request->file('path')->store('noticias', 'public');
            $validated['path'] = $path;
        } else {
            $validated['path'] = $validated['path'] ?? 'noticias/sin_imagen.jpg';
        }

        Latest::updateOrCreate(
            ['id' => $request->id],
            $validated,
        );

        return response()->json(['success' => 'Noticia guardada correctamente.']);
    }

    public function edit($id)
    {
        return response()->json(Latest::findOrFail($id));
    }

    public function destroy($id)
    {
        Latest::findOrFail($id)->delete();

        return response()->json(['success' => 'Noticia eliminada correctamente.']);
    }
}
