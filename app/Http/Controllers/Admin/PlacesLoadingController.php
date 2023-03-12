<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Admin; 
use App\PlaceLoading;
use DB;
use Validator;
use Redirect;
use IMS;
class PlacesLoadingController extends Controller
{
    public $folder  = "admin/places_loading.";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = new PlaceLoading;
		$admin = new Admin;
		return View($this->folder.'index',[
            'data' => $res->getAll(),
			'admin' => $admin,
            'link' => env('admin').'/places_loading/'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = new Admin;	
		return View($this->folder.'add',[
            'data' => new PlaceLoading,
			'admin' => $admin,
            'form_url' => env('admin').'/places_loading',
            'ApiKey'    => Admin::find(1)->ApiKey_google,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        try {
            $lims_places_loading_data = $request->all(); 
            PlaceLoading::create($lims_places_loading_data); 
            return redirect(env('admin').'/places_loading')->with('message','Nuevo elemento agregado.');
        } catch (\Excepetion $th) {
            return redirect(env('admin').'/places_loading')->with('error','Ha ocurrido un problema '.$th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = new Admin;	
		return View($this->folder.'edit',[
            'data' => PlaceLoading::find($id),
			'admin' => $admin,
            'form_url' => env('admin').'/places_loading/'.$id,
            'ApiKey'    => Admin::find(1)->ApiKey_google,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $lims_places_loading_data = PlaceLoading::find($id); 
            $input = $request->all(); 
            $lims_places_loading_data->update($input);
            return redirect(env('admin').'/places_loading')->with('message','Elemento actualizado.');
        } catch (\Excepetion $th) {
            return redirect(env('admin').'/places_loading')->with('error','Ha ocurrido un problema '.$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        PlaceLoading::where('id',$id)->delete();
		return redirect(env('admin').'/places_loading')->with('message','Elemento elominado con exito.');
    }

    /*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= PlaceLoading::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('admin').'/places_loading')->with('message','Estatus actualizado con exito.');
	}
}
