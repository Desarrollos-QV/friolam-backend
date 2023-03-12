<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth; 
use App\Admin; 
use App\Materials;
use DB;
use Validator;
use Redirect;
use IMS;
class MaterialsController extends Controller
{
    public $folder  = "admin/materials.";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = new Materials;
		$admin = new Admin;
		return View($this->folder.'index',[
            'data' => $res->getAll(),
			'admin' => $admin,
            'link' => env('admin').'/materials/'
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
            'data' => new Materials,
			'admin' => $admin,
            'form_url' => env('admin').'/materials'
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
            $lims_materials_data = $request->all(); 
            Materials::create($lims_materials_data); 
            return redirect(env('admin').'/materials')->with('message','Nuevo elemento agregado.');
        } catch (\Excepetion $th) {
            return redirect(env('admin').'/materials')->with('error','Ha ocurrido un problema '.$th->getMessage());
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
            'data' => Materials::find($id),
			'admin' => $admin,
            'form_url' => env('admin').'/materials/'.$id
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
            $lims_materials_data = Materials::find($id); 
            $input = $request->all(); 
            $lims_materials_data->update($input);
            return redirect(env('admin').'/materials')->with('message','Elemento actualizado.');
        } catch (\Excepetion $th) {
            return redirect(env('admin').'/materials')->with('error','Ha ocurrido un problema '.$th->getMessage());
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
        Materials::where('id',$id)->delete();
		return redirect(env('admin').'/materials')->with('message','Elemento elominado con exito.');
    }

    /*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Materials::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('admin').'/materials')->with('message','Estatus actualizado con exito.');
	}
}
