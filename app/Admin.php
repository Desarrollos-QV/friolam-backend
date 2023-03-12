<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Validator;
use Carbon\Carbon;
class Admin extends Authenticatable
{
    protected $table = "admin";

    public function rules($type)
    {
        if($type === 'add')
        {
            return [

            'username' => 'required|unique:admin',

            ];
        }
        else
        {
            return [

            'username'     => 'required|unique:admin,username,'.$type,

            ];
        }
    }

    public function validate($data,$type)
    {

        $validator = Validator::make($data,$this->rules($type));
        if($validator->fails())
        {
            return $validator;
        }
    }
    /*
	|------------------------------------------------------------------
	|Checking for current admin password
	|@password = admin password
	|------------------------------------------------------------------
	*/
	public function matchPassword($password)
	{
	  if(auth()->guard('admin')->attempt(['username' => Auth()->guard('admin')->user()->username, 'password' => $password]))
	  {
		  return false;
	  }
	  else
	  {
		  return true;
	  }
	}

	/*
	|---------------------------------
	|Update Account Data
	|---------------------------------
	*/
	public function updateData($data)
	{
        $a                  	   = isset($data['lid']) ? array_combine($data['lid'], $data['l_store_type']) : [];
		$update 					= Admin::find(Auth::guard('admin')->user()->id);
		$update->name 				= isset($data['name']) ? $data['name'] : null;
		$update->email 				= isset($data['email']) ? $data['email'] : null;
		$update->username 			= isset($data['username']) ? $data['username'] : null;
		$update->fb 				= isset($data['fb']) ? $data['fb'] : null;
		$update->insta 				= isset($data['insta']) ? $data['insta'] : null;
		$update->twitter 			= isset($data['twitter']) ? $data['twitter'] : null;
		$update->youtube 			= isset($data['youtube']) ? $data['youtube'] : null;
		$update->currency 			= isset($data['currency']) ? $data['currency'] : null;
		$update->paypal_client_id 	= isset($data['paypal_client_id']) ? $data['paypal_client_id'] : null;
		$update->stripe_client_id 	= isset($data['stripe_client_id']) ? $data['stripe_client_id'] : null;
		$update->stripe_api_id 		= isset($data['stripe_api_id']) ? $data['stripe_api_id'] : null;
		$update->ApiKey_google   	= isset($data['ApiKey_google']) ? $data['ApiKey_google'] : null;
		$update->accessToken_mapbox = isset($data['accessToken_mapbox']) ? $data['accessToken_mapbox'] : null;
		$update->comm_stripe   	    = isset($data['comm_stripe']) ? $data['comm_stripe'] : null;
		$update->send_terminal      = isset($data['send_terminal']) ? $data['send_terminal'] : 0;
		$update->s_data 			= serialize($a);

		if(isset($data['new_password']))
		{
			$update->password = bcrypt($data['new_password']);
			$update->shw_password = $data['new_password'];
		}

		if(isset($data['logo']))
        {
            $filename   = time().rand(111,699).'.' .$data['logo']->getClientOriginalExtension();
            $data['logo']->move("upload/admin/", $filename);
            $update->logo = $filename;
        }

		$update->save();

	}

	public function getAll()
	{
		return Admin::where('id','!=',1)->get();
	}

	public function addNew($data,$type)
    {
        $add                    = $type === 'add' ? new Admin : Admin::find($type);
       	$add->username 			= isset($data['username']) ? $data['username'] : null;
       	$add->name 				= isset($data['name']) ? $data['name'] : null;
		$add->branch_id         = isset($data['branch_id']) ? implode(",", $data['branch_id']) : null;
		$add->phone 			= isset($data['phone']) ? $data['phone'] : null;
		$add->email 			= isset($data['email']) ? $data['email'] : null;
		$add->cedula		    = isset($data['cedula']) ? $data['cedula'] : null;
       	$add->perm 				= isset($data['perm']) ? implode(",", $data['perm']) : null;
		$add->city_id           = isset($data['city_id']) ? $data['city_id'] : 0;

        if(isset($data['password']))
        {
            $add->password      = bcrypt($data['password']);
            $add->shw_password  = $data['password'];
        }

        $add->save();
    }

	public function overview()
	{
		return [
			'd_boy'     => Delivery::count(),
			'order'		=> Commaned::count(),
			'complete'  => Commaned::where('status',6)->whereDate('created_at','LIKE',date('Y').'%')->count(),
			'cancel'    => Commaned::where('status',2)->count(),
			'month'  	=> Commaned::whereDate('created_at','LIKE',date('Y').'%')->count(),
			'user'  	=> AppUser::count(),
		];
	}

	public function getMonthName($type)
	{
		 $month = date('m') - $type;

		 return $type == 0 ? date('F') : date('F',strtotime(date('Y').'-'.$month));
	}

	public function getDayName($type)
	{
		$day = date('d') - $type;

		return $type == 0 ? date('l') : date('l',strtotime(date('Y').'- '.$type.' day'));
	}

	public function chart($type,$sid = 0)
	{
		$month      = date('Y-m',strtotime(date('Y-m').' - '.$type.' month'));

		$order   = Commaned::where(function($query) use($sid){

			// if($sid > 0)
			// {
			// 	$query->where('user_id',Auth::user()->id);
			// }

		})->where('status',6)->whereDate('created_at','LIKE',$month.'%')->count();


		$cancel  = Commaned::where(function($query) use($sid){

			// if($sid > 0)
			// {
			// 	$query->where('store_id',Auth::user()->id);
			// }

		})->where('status',2)->whereDate('created_at','LIKE',$month.'%')->count();

		return ['order' => $order,'cancel' => $cancel];
	}

	public function RankingDboy()
	{
		$res  = Delivery::where('status',0)->get();
		$data = [];
		foreach ($res as $row) {
		
			$totalRate    = Rate::where('staff_id',$row->id)->count();
			$totalRateSum = Rate::where('staff_id',$row->id)->sum('star');
			
			if($totalRate > 0)
			{
				$avg          = $totalRateSum / $totalRate;
			}
			else
			{
				$avg           = 0 ;
			}

			$data[] = [
				'name' 			=> $row->name,
				'phone'         => $row->phone,
				'rating'        => $avg > 0 ? number_format($avg, 1) : '0.0',
			];
		}

		
		if (count($data) > 0) {
			foreach ($data as $key => $row)
			{
				$count[$key] = $row['rating'];
			}

			array_multisort($count, SORT_DESC, $data);
		}
		

		// return $data->orderBy('rating', 'desc');
		// $array = collect($data)->sortBy('rating')->reverse()->toArray();
		return $data;
	}

	public function storeChart()
	{
		$storeID = Commaned::where('status',5)->pluck('user_id')->toArray();


		$data = [];

		foreach(array_unique($storeID) as $sid)
		{
			$user = AppUser::find($sid);

			if(isset($user->id))
			{
				$data[] = ['name' => $user->name,'order' => Commaned::where('user_id',$sid)->where('status',5)->count()];
			}
		}

		 arsort($data);

		 return $data;
	}

	public function getStoreData($data,$index,$type)
	{

		if(isset($data[$index]))
		{
			return $data[$index][$type];
		}
		else
		{
			return null;
		}
	}

	public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$id]) ? $data[$id] : null;
    }

    public function hasPerm($perm)
	{
		if (Auth::guard('admin')->user()) {
			$array = explode(",", Auth::guard('admin')->user()->perm);

			if(in_array($perm,$array) || in_array("All",$array))
			{
				return true;
			}
			else
			{
				return false;
			}
		}else {
			return false;
		}
	}

	public function getBranch($id)
	{
		$branch_id = Admin::find($id)->branch_id;
		
		if ($branch_id == 0) {
			return 'Global';
		}else {
			$array = explode(",", $branch_id);
			$branchs = " ";
			for ($i=0; $i < count($array); $i++) { 
			
				$branch = Branchs::find($array[$i]);
				$branchs .=  $branch->name." - ";
			}

			return $branchs;
		}
	}

}
