<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\City;
use App\Zones;
use App\Admin;
use App\Vehicles;
use DB;
use Validator;
use Redirect;
use IMS;
class vehiclesController extends Controller
{
	public $folder  = "admin/vehicles.";
    /*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$admin = new Admin;
        $city = Auth::guard('admin')->user()->city_id;

		if ($admin->hasperm('vehicles')) {
            $res = new Vehicles;

            return View($this->folder.'index',[
				'data' => $res->getAll(0),
				'link' => env('admin').'/vehicles/',
				'array'		=> [], 
                'form_url' => env('admin').'/exportData_staff',
                'admin'   => $admin
			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	} 

	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{
		$admin = new Admin;

		if ($admin->hasperm('vehicles')) { 
			return View($this->folder.'add',[
				'data' => new Vehicles,
				'form_url' => env('admin').'/vehicles', 
				'admin'  => $admin
			]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{
		$data = $Request->all();
 
        $lims_data['number_plate']  = $data['number_plate'];
        $lims_data['type_driver']   = $data['type_driver'];
        $lims_data['capacity']      = $data['capacity'];
        $lims_data['brand']         = $data['brand'];
        $lims_data['model']         = $data['model'];
        $lims_data['color']         = $data['color'];
        $lims_data['status']        = $data['status'];
 
        Vehicles::create($lims_data);

		return Redirect::to(env('admin').'/vehicles')->with('message','Vehiculo agregado con exito....');
	}

	/*
	|---------------------------------------
	|@Edit Page
	|---------------------------------------
	*/
	public function edit($id)
	{
		$admin = new Admin;

		if ($admin->hasperm('vehicles')) { 
			return View(
				$this->folder.'edit',
				[
					'data' => Vehicles::find($id),
					'form_url' => env('admin').'/vehicles/'.$id, 
                    'admin' => $admin
					]
				);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	} 

	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/

	public function update(Request $Request,$id)
	{
		$data = $Request->all();
 
        $lims_data['number_plate']  = $data['number_plate'];
        $lims_data['type_driver']   = $data['type_driver'];
        $lims_data['capacity']      = $data['capacity'];
        $lims_data['brand']         = $data['brand'];
        $lims_data['model']         = $data['model'];
        $lims_data['color']         = $data['color'];
        $lims_data['status']        = $data['status'];
  
        $lims_vehicles_data = Vehicles::findOrFail($id);
        $lims_vehicles_data->update($lims_data);
		return Redirect::to(env('admin').'/vehicles')->with('message','Vehiculo actualizado con exito....');
	}

	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Vehicles::where('id',$id)->delete();

		return redirect(env('admin').'/vehicles')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Vehicles::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('admin').'/vehicles')->with('message','Status Updated Successfully.');
	}  
}
