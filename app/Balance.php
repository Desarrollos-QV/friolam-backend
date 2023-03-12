<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
class Balance extends Authenticatable
{
    protected $table = "recharge_balance";


    /*
    |--------------------------------
    |Create/Update city
    |--------------------------------
    */

    public function addNew($user_id,$amount,$status,$payment_id)
    {
        $add                    = new Balance;
        $add->user_id          = $user_id;
        $add->amount           = $amount;
        $add->status           = $status;
        $add->payment_id       = $payment_id;
        $add->save();

        return 'done';
    }   
    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($type = null)
    {
        return Balance::where(function($query) use($type) {

            if($type)
            {
                $query->where('status',$type);
            }

        })->orderBy('id','DESC')->get();
    }


}
