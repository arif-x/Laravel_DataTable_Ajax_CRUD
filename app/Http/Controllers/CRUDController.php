<?php

namespace App\Http\Controllers;

use App\Anggota;
use Illuminate\Http\Request;
use DataTables;

class CRUDController extends Controller{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(Request $request){  
		if ($request->ajax()) {
			$data = Anggota::latest()->get();
			return Datatables::of($data)
			->addIndexColumn()
			->addColumn('action', function($row){

				$btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editAnggota">Edit</a>';

				$btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAnggota">Delete</a>';

				return $btn;
			})
			->rawColumns(['action'])
			->make(true);
		}

		return view('crud-ajax');
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
    	Anggota::updateOrCreate(['id' => $request->anggota_id],
    		['nama' => $request->nama, 'alamat' => $request->alamat]);        

    	return response()->json(['success'=>'Anggota Disimpan.']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
    	$anggota = Anggota::find($id);
    	return response()->json($anggota);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Anggota  $anggota
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
    	Anggota::find($id)->delete();

    	return response()->json(['success'=>'Anggota Dihapus.']);
    }
}
