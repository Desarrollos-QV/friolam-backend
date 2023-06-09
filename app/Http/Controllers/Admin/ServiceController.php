<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Commaned;
use App\Admin;
use App\AppUser;
use App\User;
use App\Materials;
use App\Services;
use App\PlaceLoading;
use App\Delivery;

use DB;
use Validator;
use Redirect;
use IMS;
class ServiceController extends Controller {

	public $folder  = "admin/services.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$res = new Services;
		$comm = new Commaned;
        $admin = new Admin;
        $status = isset($_GET['status']) ? $_GET['status'] : 0;
        $title = 'Listado de Servicios'; 

		if ($admin->hasperm('Servicios')) {

			// return response()->json([
            //     'data' 		=> $res->getAll($status), 
            //     'link' 		=> env('admin').'/Services/',
            //     'form_url'	=> env('admin').'/Services/assign',
            //     'admin'     => $admin,
			// 	'comm'		=> $comm,
			// 	'service'   => $res
			// ]);

            return View($this->folder.'index',[
                'data' 		=> $res->getAll($status), 
                'link' 		=> env('admin').'/Services/',
                'form_url'	=> env('admin').'/Services/assign',
                'admin'     => $admin,
				'comm'		=> $comm,
				'service'   => $res
			]); 
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Servicios');
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
		$res   = new Services;

		if ($admin->hasperm('Servicios')) {
		
			return View($this->folder.'add',[ 
				'data' 		=> new Services,
				'clients'   => User::get(),
				'dboys'     => AppUser::where('status',0)->get(),
				'form_url' 	=> env('admin').'/Services',
				'array'		=> [],
				'admin'     => Admin::find(1),
				'res'       => $res 
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Servicios');
		}
	}

	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{  
			$data = new Services;
			$data->addNew($Request->all(),"add");
			return redirect(env('admin').'/Services')->with('message','Nuevo servicio creado con éxito.');
	}

	/*
	|---------------------------------------
	|@Edit Page
	|---------------------------------------
	*/
	public function edit($id)
	{
		$admin = new Admin;
		$res   = new Services;
		 
		if ($admin->hasperm('Servicios')) {
			return View($this->folder.'edit',[
				'data' 		=> $res->getElement($id),
				'dboys'     => AppUser::where('status',0)->get(),
				'form_url' 	=> env('admin').'/Services/'.$id,
				'admin'     => Admin::find(1),
				'res'       => $res 
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Servicios');
		}
	}

	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{
		$data = new Services;
		$data->addNew($Request->all(),$id);

		return redirect(env('admin').'/Services')->with('message','Servicio actualizado con exito.');
	}

	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Services::where('id',$id)->delete(); 
		return redirect(env('admin').'/Services')->with('message','Elemento eliminado');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id,$status = 0)
	{
		$res 			= Services::find($id);
		if ($status == 0) {
			$res->status 	= $res->status == 0 ? 1 : 0;
		}else {
			$res->status    = $status;
		}

		$res->save();

		return redirect(env('admin').'/Services?status='.$res->status)->with('message','Status Updated Successfully.');
	}

}
