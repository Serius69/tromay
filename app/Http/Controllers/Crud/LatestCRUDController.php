<?php

namespace App\Http\Controllers\Crud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Latest;
use DataTables;


class LatestCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Latest::latest()->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editlatest">Edit</a>';

                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletelatest">Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
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
    public function store(Request $request)
    {
        latest::updateOrCreate([
                    'id' => $request->latest_id
                ],
                [
                    'name' => $request->name,
                    'author' => $request->author,
                    'description' => $request->description,
                    'date_publication' => $request->date_publication,
                    'url' => $request->url,
                    'photo_id' => $request->photo_id
                ]);

        return response()->json(['success'=>'latest saved successfully.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\latest  $latest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $latest = Latest::find($id);
        return response()->json($latest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\latest  $latest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Latest::find($id)->delete();
        return response()->json(['success'=>'latest deleted successfully.']);
    }
}
