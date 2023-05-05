<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
use Excel;
class SubUsers extends Authenticatable
{
    protected $table = "sub_users";

    protected $fillable =[
        "client_basis",
        "client_sap",
        "rut",
        "razon_social",
        "direccion",
        "numero",
        'phone',
        "comuna",
        "canal",
        "subcanal",
        "canal2",
        "subcanal2",
        "ciclo"
    ];

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        return SubUsers::orderBy('sub_users.id','DESC')->paginate(50);
    }

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$field][$id]) ? $data[$field][$id] : null;
    }

    public function import($data)
    {
        $array = Excel::toArray(new SubUsers, $data['file']); 

        $i = 0;
        foreach($array[0] as $a)
        {
            $i++;

            if($i > 1)
            {
                if ($a[1] != null) {
                    $add                    = new SubUsers;
                    
                    $add->client_basis      =   $a[0];
                    $add->client_sap        =   $a[1];
                    $add->rut               =   $a[6];
                    $add->razon_social      =   $a[7];
                    $add->direccion         =   $a[8];
                    $add->numero            =   $a[9];
                    $add->comuna            =   $a[12];
                    $add->canal             =   $a[13];
                    $add->subcanal          =   $a[14];
                    $add->canal2            =   $a[15];
                    $add->subcanal2         =   $a[16];
                    $add->ciclo             =   $a[27];

                    $add->save();
                }
            }
        }
    }
}