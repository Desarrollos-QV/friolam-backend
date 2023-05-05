<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\SubUsers;
use App\Branchs;
use App\Admin;

use DB;
use Validator;
use Redirect;
use IMS; 
use Excel;

class SubUserController extends Controller {

	public $folder  = "admin/subuser.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					 
		$res = new SubUsers;

		return View($this->folder.'index',[
			'data' => $res->getAll(),
			'link' => env('admin').'/subusers/',
			'currency' => Admin::find(1)->currency,
			'admin' => new Admin
		]);
	}	
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{		
		return View($this->folder.'add',[
			'data' 		=> new SubUsers,  
			'form_url'  => env('admin').'/subusers',
			'admin' => new Admin
		]);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = $Request->all();
        $lims_data_create = new SubUsers;
	 
        $lims_data_create->create($data);
        
		return redirect(env('admin').'/subusers')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$user = new SubUsers;

		return View($this->folder.'edit',[
			'data' 		=> SubUsers::find($id),
			'form_url'  => env('admin').'/subusers/'.$id,
			'admin'		=> Admin::find(1),  
		]);
	}
 
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = $Request->all();
        $lims_data_update = SubUsers::find($id);

        $lims_data_update->update($data);

		// $data->addNew($Request->all(),$id);
		
		return redirect(env('admin').'/subusers')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		SubUsers::where('id',$id)->delete();
		
		return redirect(env('admin').'/subusers')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= SubUsers::find($id);
		
		if(isset($_GET['type']) && $_GET['type'] == "trend")
		{
			$res->trending 	= $res->trending == 0 ? 1 : 0;
		}
		elseif(isset($_GET['type']) && $_GET['type'] == "open")
		{
			$res->open 	= $res->open == 0 ? 1 : 0;
		}else {
			$res->status = $res->status == 0 ? 1 : 0;
		}

		$res->save();

		return redirect(env('admin').'/subusers')->with('message','Status Updated Successfully.');
	}


    /*
	|---------------------------------------------
	|@Import DATA
	|---------------------------------------------
	*/
    public function import()
	{
		return View($this->folder.'import',[
            'admin' => Admin::find(1)
        ]);
	}

	public function _import(Request $Request)
	{
        $res = new SubUsers;
		$res->import($Request->all()); 
        return redirect(env('admin').'/subusers')->with('message','Status Updated Successfully.');
		// $data = $Request->all();
		// $array = Excel::toArray(new SubUsers, $data['file']); 

		// return response()->json(['data' => $array]);
	}
}