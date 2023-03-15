<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Branchs extends Authenticatable
{
    protected $table = "branchs";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [

        'name'      => 'required',

        ];
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
    |--------------------------------
    |Create/Update city
    |--------------------------------
    */

    public function addNew($data,$type)
    {
        $a                  = isset($data['lid']) ? array_combine($data['lid'], $data['l_name']) : [];
        $add                = $type === 'add' ? new Branchs : Branchs::find($type);
        $add->name          = isset($data['name']) ? $data['name'] : null;
        $add->name_contact  = isset($data['name_contact']) ? $data['name_contact'] : null;
        $add->email_contact = isset($data['email_contact']) ? $data['email_contact'] : null;
        $add->address       = isset($data['address']) ? $data['address'] : null;
        $add->lat           = isset($data['lat']) ? $data['lat'] : null;
        $add->lng           = isset($data['lng']) ? $data['lng'] : null;  
        $add->observations  = isset($data['observations']) ? $data['observations'] : null;
        $add->status        = isset($data['status']) ? $data['status'] : null;
        $add->s_data        = serialize($a);
        $add->save();

    }   
    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($type = null)
    {
        return Branchs::where(function($query) use($type) {

            if($type)
            {
                $query->where('status',$type);
            }

        })->orderBy('id','DESC')->get();
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$id]) ? $data[$id] : null;
    }
}
