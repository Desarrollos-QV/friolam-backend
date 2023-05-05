<?php

namespace App; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DB;
use Stripe;
 
class User extends Authenticatable
{
    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        if($type === "add")
        {
            return [
                'name'      => 'required',
                'phone'     => 'required',
                'email'     => 'required|unique:users',
                'password'  => 'required|min:6',
            ];
        }
        else
        {
            return [
                'name'      => 'required',
                'phone'    => 'required',
                'email'     => 'required|unique:users,email,'.$type,
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
    |--------------------------------
    |Create/Update user 
    |--------------------------------
    */

    public function addNew($data,$type)
    {

        $a                          = isset($data['lid']) ? array_combine($data['lid'], $data['l_name']) : [];
        $b                          = isset($data['lid']) ? array_combine($data['lid'], $data['l_address']) : [];
        $add                        = $type === 'add' ? new User : User::find($type);
        $add->name                  = isset($data['name']) ? $data['name'] : null;
        $add->phone                 = isset($data['phone']) ? $data['phone'] : null;
        $add->email                 = isset($data['email']) ? $data['email'] : null;
        $add->status                = isset($data['status']) ? $data['status'] : 0;
        $add->lat                   = isset($data['lat']) ? $data['lat'] : null;
        $add->lng                   = isset($data['lng']) ? $data['lng'] : null;
        
        $add->s_data                = serialize([$a,$b]);
 
        if(isset($data['img']))
        {
            $filename   = time().rand(111,699).'.' .$data['img']->getClientOriginalExtension(); 
            $data['img']->move("upload/user/", $filename);   
            $add->img = $filename;   
        }
        if(isset($data['logo']))
        {
            $filename   = time().rand(111,699).'.' .$data['logo']->getClientOriginalExtension(); 
            $data['logo']->move("upload/user/logo/", $filename);   
            $add->logo = $filename;
        }

        
        if(isset($data['password']))
        {
            $add->password      = bcrypt($data['password']);
            $add->shw_password  = $data['password'];
        }

        // Sucursales
        $add->subusers = isset($data['subusers']) ? json_encode($data['subusers']) : [];
        
        $add->save();

        // Creamos el QR https://{{$add->dominio}}.kiibo.mx
        $link_qr        = $add->nombre.$add->id;
        $codeQR         = base64_encode(QrCode::format('png')->size(200)->generate($link_qr));

        $add->qr_code   = $codeQR;
        $add->save();
        
    }

    public function updateMap($data,$id)
    {
        $store = User::find($id);

        if ($store) {
            $store->address               = isset($data['address']) ? $data['address'] : 0;
            $store->lat                   = isset($data['lat']) ? $data['lat'] : null;
            $store->lng                   = isset($data['lng']) ? $data['lng'] : null;

            $store->save();
        }

        return true;
    }

    public function getAssignedBranch($id)
    {
        return json_decode(User::find($id)->subusers);
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll()
    {
        return User::orderBy('users.id','DESC')->paginate(50);
    }
   

    public function getSData($data,$id,$field)
    {
        $data = unserialize($data);

        return isset($data[$field][$id]) ? $data[$field][$id] : null;
    }

   public function login($data)
   {
    if (isset($data['spdmin'])) {
        # login admin
        if (auth()->guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']]))
        {
             return ['msg' => 'done','user_id' => 1];
         }else {
             # Login SubAccounts
             $chk = Admin::where('username',$data['email'])->where('shw_password',$data['password'])->first();
             if(isset($chk->id))
             {
                 return ['msg' => 'done','user_id' => $chk->id];
             }
             else
             {
                 return ['msg' => 'Opps! Detalles de acceso incorrectos '];
             }
             
         }
    }else {
         $chk = User::where('status',0)->where('email',$data['email'])->where('shw_password',$data['password'])->first();

         if(isset($chk->id))
         {
             return ['msg' => 'done','user_id' => $chk->id];
         }
         else
         {
             return ['msg' => 'Opps! Detalles de acceso incorrectos'];
         }
     }
   }
 

    
}
