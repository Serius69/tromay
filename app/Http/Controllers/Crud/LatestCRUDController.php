<?php

namespace App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Latest;
use DataTables;
class LatestCRUDController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)   {
        
        if ($request->ajax()) {            
            $data = Latest::latest()->get();
            return DataTables::of($data)
                    ->addColumn('check', function($row){
                        $check ='
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                        </div>
                        ';
                        return $check;
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){                        
                        $btn ='
                        <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                            <a href="#showModal" a-bs-toggle="modal" data-id="'.$row->id.' class="text-primary d-inline-block edit-item-btn">
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
                    // ->addColumn('action', function($row){

                    //     $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editRoom">Editar</a>';

                    //     $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteRoom">Eliminar</a>';

                    //      return $btn;
                    // })
                    // ->rawColumns(['action'])
                    ->make(true);
        }
        return view('latest.crud');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)    {
        $request->validate([
            'name' => 'required',
            'author' => 'required',
            'description' => 'required',
            'date_publication' => 'required',
            'path' => 'required'
            ]);

        if($request->file('path')!=null)
         {
             $file = $request->file('path');
             $file->move('assets2/img/noticias', $file->getClientOriginalName());
             $imagen=$file->getClientOriginalName();
         }
         else
         {
             $imagen="sin_imagen.jpg";
         }
        $photo = new Photo;
        $photo->path = $imagen;
        $photo->save();

        latest::updateOrCreate([
                    'id' => $request->id
                ],
                [
                    'name' => $request->name,
                    'author' => $request->author,
                    'description' => $request->description,
                    'date_publication' => $request->date_publication,
                    'url' => $request->url,
                    'path' => $photo->id
                ]);

        return response()->json(['success'=>'latest saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\latest  $latest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)    {
        $latest = Latest::find($id);
        return response()->json($latest);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\latest  $latest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)    {
        Latest::find($id)->delete();
        return response()->json(['success'=>'latest deleted successfully.']);
    }
}
