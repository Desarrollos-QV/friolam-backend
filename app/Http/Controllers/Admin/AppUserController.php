<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Offer;
use App\Admin;
use App\AppUser;
use DB;
use Validator;
use Redirect;
use IMS;
class AppUserController extends Controller {

	public $folder  = "admin/appUser.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$admin = new Admin;
		if($admin->hasperm('Subaccount')){

			return View(
				$this->folder.'index',
				[
					'data' => AppUser::orderBy('id','DESC')->paginate(60),
					'link' => env('admin').'/appUser/',
                    'admin' => $admin

				]
			);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Usuarios Registrados');
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

		if ($admin->hasperm('Ofertas de descuento')) {
		
			return View($this->folder.'add',[

				'data' 		=> new AppUser,
				'form_url' 	=> env('admin').'/appUser',
				'array'		=> [],
				'admin'     => $admin

			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Ofertas de descuento');
		}
	}

	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{
		$data = new AppUser;

		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		return redirect(env('admin').'/appUser')->with('message','New Record Added Successfully.');
	}

	/*
	|---------------------------------------
	|@Edit Page
	|---------------------------------------
	*/
	public function edit($id)
	{


		$admin = new Admin;

		if ($admin->hasperm('Ofertas de descuento')) {
			return View($this->folder.'edit',[
				'data' 		=> AppUser::find($id),
				'form_url' 	=> env('admin').'/appUser/'.$id,
				'admin'     => $admin
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Ofertas de descuento');
		}
	}

	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{
		$data = new AppUser;

		$data->addNew($Request->all(),$id);

		return redirect(env('admin').'/appUser')->with('message','Usuario Actualizado con exito.');
	}

	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		AppUser::where('id',$id)->delete();

		return redirect(env('admin').'/appUser')->with('message','Usuario Eliminado.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res = AppUser::find($id);
		if ($res) {
			$res->status = $res->status == 0 ? 1 : 0;
			$res->save();
			return redirect(env('admin').'/appUser')->with('message','Status del usuario Actualizado');
		}else {
			return redirect(env('admin').'/appUser')->with('error','Usuarios No Encontrado');
		}
	}

}
