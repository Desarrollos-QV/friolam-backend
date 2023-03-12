<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Delivery;
use App\User;
use App\City;
use App\Admin;
use App\Rate;
use App\Vehicles;
use DB;
use Validator;
use Redirect;
use IMS;
class deliveryController extends Controller {

	public $folder  = "admin/delivery.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$admin = new Admin;
        $city = Auth::guard('admin')->user()->city_id;

		if ($admin->hasperm('Repartidores')) {
            if(Auth::guard('admin')->user()->city_id == 0){
                $res = new Delivery; 
			    return View($this->folder.'index',[
				'data' => $res->getAll(0),
				'link' => env('admin').'/delivery/',
				'array'		=> [],
                'export' => env('admin').'/exportDboy/',
                'form_url' => env('admin').'/exportData_staff',
                'admin'   => $admin
			]);

            }else {

                $store = 0;

                $res = Delivery::where(function($query) use($store) {

                    if($store > 0)
                    {
                        $query->where('store_id',$store);
                    } 
                })->leftjoin('city','delivery_boys.city_id','=','city.id')
                  ->select('city.name as city','delivery_boys.*')
                  ->where('city_id', "$city")->paginate(10);


                return View($this->folder.'index',[
                    'data' => $res,
                    'link' => env('admin').'/delivery/',
                    'array'		=> [],
                    'export' => env('admin').'/exportDboy/',
                    'form_url' => env('admin').'/exportData_staff',
					'admin'   => $admin
                ]);

            }


		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

	public function report_dboy($id)
	{

		$admin = new Admin;

		if ($admin->hasperm('Repartidores')) {
			$res = new Delivery;
			return View($this->folder.'report',[
				'data' => $res->getReport($id),
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

		if ($admin->hasperm('Repartidores')) {
			$city = new City; 
			return View($this->folder.'add',[
				'data' => new Delivery,
				'form_url' => env('admin').'/delivery',
				'citys'    => $city->getAll(0),
				'vehicles' => Vehicles::get(),
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
		$data = new Delivery;

		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");

		return redirect(env('admin').'/delivery')->with('message','New Record Added Successfully.');
	}

	/*
	|---------------------------------------
	|@Edit Page
	|---------------------------------------
	*/
	public function edit($id)
	{
		$admin = new Admin;

		if ($admin->hasperm('Repartidores')) {
			$city = new City;
			return View(
				$this->folder.'edit',
				[
					'data' => Delivery::find($id),
					'form_url' => env('admin').'/delivery/'.$id,
					'citys'    => $city->getAll(0),
					'vehicles' => Vehicles::get(),
                    'admin' => $admin
					]
				);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

    public function pay($id)
    {
        $admin = new Admin;

		if ($admin->hasperm('Repartidores')) {

			return View(
				$this->folder.'pay',
				[
					'data' => Delivery::find($id),
					'form_url' => env('admin').'/delivery_pay/'.$id,
                    'admin' => $admin
					]
				);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Repartidores');
		}
	}

	public function rate($id)
    {
        $admin = new Admin;
		$rate  = new Rate;
		return View(
		$this->folder.'rate',
		[
			'data' 		=> Delivery::find($id),
			'rate_data' => $rate->GetRate($id),
            'admin' => $admin
			]
		);
	}

	public function delivery_pay(Request $Request,$id)
	{
		$staff = new Delivery;

		$req = $staff->add_comm($Request->All(),$id);

		return redirect(env('admin').'/delivery')->with('message','Pago realizado con exito.');


	}
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{
		$data = new Delivery;

		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id);

		return redirect(env('admin').'/delivery')->with('message','Record Updated Successfully.');
	}

	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		Delivery::where('id',$id)->delete();

		return redirect(env('admin').'/delivery')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= Delivery::find($id);
		$res->status 	= $res->status == 0 ? 1 : 0;
		$res->save();

		return redirect(env('admin').'/delivery')->with('message','Status Updated Successfully.');
	}

	public function getCity($id)
	{
		$res = User::find($id);
		return $res->name;
	}

	/*
	|---------------------------------------
	|@View Report
	|---------------------------------------
	*/
	public function exportDboy($id)
	{
		return Excel::download(new DeliveryExport($id), 'report.xlsx');
	}
}
