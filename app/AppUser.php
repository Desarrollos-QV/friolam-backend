<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Mail;
class AppUser extends Authenticatable
{
   protected $table = 'app_user';

   /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */
    
    public function rules($type)
    {
        return [
            'name'      => 'required',
            'email'     => 'required|unique:app_user,email,'.$type,
            'phone'      => 'required|unique:app_user,phone,',
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

   public function addNew($data,$type)
   {
       if ($type == 'add') {
            $count = AppUser::where('email',$data['email'])->count();

            if($count == 0)
            {
                if (isset($data['phone']) && $data['phone'] != 'null') {
                    $count_p = AppUser::where('phone',$data['phone'])->count();

                    if ($count_p == 0) {
                        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                            $add                = new AppUser;
                            $add->name          = $data['name'];
                            $add->lastname      = $data['lastname'];
                            $add->email         = $data['email'];
                            $add->phone         = isset($data['phone']) ? $data['phone'] : 'null';
                            $add->rut           = isset($data['rut']) ? $data['rut'] : '';
                            $add->password      = $data['password'];
                            $add->pswfacebook   = isset($data['pswfb']) ? $data['pswfb'] : 0;
                            $add->date_of_hire  = isset($data['date_of_hire']) ? $data['date_of_hire'] : 'null';
                            if(isset($data['pic']))
                            {
                                $filename   = time().rand(111,699).'.' .$data['pic']->getClientOriginalExtension(); 
                                $data['pic']->move("upload/workers/", $filename);   
                                $add->pic = $filename;   
                            }

                            $add->save();
    
                            return ['msg' => 'done','user_id' => $add->id];
                        }else {
                            return ['msg' => 'Opps! El Formato del Email es invalido'];
                        }
                    }else {
                        return ['msg' => 'Opps! Este número telefonico ya existe.'];
                    }
                }else {
                    if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $add                = new AppUser;
                        $add->name          = $data['name'];
                        $add->lastname      = $data['lastname'];
                        $add->email         = $data['email'];
                        $add->phone         = isset($data['phone']) ? $data['phone'] : 'null';
                        $add->rut           = isset($data['rut']) ? $data['rut'] : '';
                        $add->password      = $data['password'];
                        $add->pswfacebook   = isset($data['pswfb']) ? $data['pswfb'] : 0;
                        $add->date_of_hire  = isset($data['date_of_hire']) ? $data['date_of_hire'] : 'null';
                        if(isset($data['pic']))
                        {
                            $filename   = time().rand(111,699).'.' .$data['pic']->getClientOriginalExtension(); 
                            $data['pic']->move("upload/workers/", $filename);   
                            $add->pic = $filename;   
                        }
                        $add->save();

                        return ['msg' => 'done','user_id' => $add->id];
                    }else {
                        return ['msg' => 'Opps! El Formato del Email es invalido'];
                    }
                }
            
            }
            else
            {
                return ['msg' => 'Opps! Este correo electrónico ya existe.'];
            }
        }else {
            
            if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $add                = AppUser::find($type);
                $add->name          = $data['name'];
                $add->lastname      = $data['lastname'];
                $add->email         = $data['email'];
                $add->phone         = isset($data['phone']) ? $data['phone'] : 'null';
                $add->rut           = isset($data['rut']) ? $data['rut'] : '';
                $add->password      = $data['password'];
                $add->pswfacebook   = isset($data['pswfb']) ? $data['pswfb'] : 0;
                $add->date_of_hire  = isset($data['date_of_hire']) ? $data['date_of_hire'] : 'null';

                if(isset($data['pic']))
                {
                    $filename   = time().rand(111,699).'.' .$data['pic']->getClientOriginalExtension(); 
                    $data['pic']->move("upload/workers/", $filename);   
                    $add->pic = $filename;   
                }
                $add->save();

                return ['msg' => 'done','user_id' => $add->id];
            }else {
                return ['msg' => 'Opps! El Formato del Email es invalido'];
            }
        }
   }

   public function SignPhone($data) 
   {
        $res = AppUser::where('id',$data['user_id'])->first();

        if(isset($res->id))
        {
            $res->phone = $data['phone'];
            $res->save();

            $return = ['msg' => 'done','user_id' => $res->id];
        }
        else
        {
            $return = ['msg' => 'error','error' => '¡Lo siento! Algo salió mal.'];
        }

        return $return;
   }

   public function chkUser($data)
   {
    if (isset($data['role']) && $data['role'] != 'null') {
        if ($data['role'] == 'user') {
            if (isset($data['user_id']) && $data['user_id'] != 'null') {
                // Intentamos con el id
                $res = AppUser::find($data['user_id']);
    
                if ($res) {
                    return ['msg' => 'user_exist','role' => 'user', 'data' => $res];
                }
            }
        }else {
            /**
             * Checamos si el ID coincide con el administrador
            */
            $chk_admin = Admin::find($data['user_id']);
            if ($chk_admin) {
                return ['msg' => 'user_exist','role' => "admin", 'data' => $chk_admin];
            }else {
                return ['msg' => 'not_exist'];
            }
        }
    }else {
        return ['msg' => 'error','req' => 'Opps! Detalles de acceso incorrectos'];
    }
       
   }

   public function login($data)
   {
        $flag = false;
        $dat = [];
        // Verificamos el email 
        $chkmail = AppUser::where('email',$data['email'])->first();
        if(isset($chkmail->id))
        { 
            // Verificamos la contrasena 
            if ($data['password'] == $chkmail->password) {
                $flag = true;
                $dat = ['msg' => 'done','role' => 'user','data' => $chkmail];
            } 
        }else { // Validamos si es un administrador
            $chkAdmin = Admin::where('email',$data['email'])->first();
            if (isset($chkAdmin->id)) { 
                // Verificamos la contrasena 
                if ($data['password'] == $chkAdmin->shw_password) {
                    $flag = true;
                    $dat = ['msg' => 'done','role' => 'admin','data' => $chkAdmin];
                } 
            }
        }

        if ($flag == true) {
            return $dat;
        }else {
            return ['msg' => 'error','req' => 'Opps! Detalles de acceso incorrectos'];
        }
   }

   public function Newlogin($data) 
   {
    $chk = AppUser::where('phone',$data['phone'])->first();

    if(isset($chk->id))
    {
       return ['msg' => 'done','user_id' => $chk->id];
    }
    else
    {
       return ['msg' => 'Opps! El usuario no existe...'];
    }
   }

   public function loginfb($data) 
   {
    $chk = AppUser::where('email',$data['email'])->where('pswfacebook',$data['password'])->first();

    if(isset($chk->id))
    {
       return ['msg' => 'done','user_id' => $chk->id];
    }
    else
    {
       return ['msg' => 'Opps! Detalles de acceso incorrectos'];
    }
   }

   public function updateInfo($data,$id)
   {
      $count = AppUser::where('id','!=',$id)->where('email',$data['email'])->count();

     if($count == 0)
     {
        $add                = AppUser::find($id);
        $add->name          = $data['name'];
        $add->email         = $data['email'];
        $add->phone         = $data['phone'];
        
        if(isset($data['password']))
        {
          $add->password    = $data['password'];
        }

        if(isset($data['pic']))
        {
            $filename   = time().rand(111,699).'.' .$data['pic']->getClientOriginalExtension(); 
            $data['pic']->move("upload/workers/", $filename);   
            $add->pic = $filename;   
        }

        $add->save();

        return ['msg' => 'done','user_id' => $add->id,'data' => $add];
     }
     else
     {
        return ['msg' => 'Opps! Este correo electrónico ya existe.'];
     }
   }

    public function forgot($data)
    {
        $res = AppUser::where('email',$data['email'])->first();

        if(isset($res->id))
        {
            $otp = rand(1111,9999);

            $res->otp = $otp;
            $res->save();

            $para       =   $data['email'];
            $asunto     =   'Codigo de acceso - FrioLam';
            $mensaje    =   "Hola ".$res->name." Un gusto saludarte, se ha pedido un codigo de recuperacion para acceder a tu cuenta en FrioLam";
            $mensaje    .=  ' '.'<br>';
            $mensaje    .=  "Tu codigo es: <br />";
            $mensaje    .=  '# '.$otp;
            $mensaje    .=  "<br /><hr />Recuerda, si no lo has solicitado tu has caso omiso a este mensaje y te recomendamos hacer un cambio en tu contrasena.";
            $mensaje    .=  "<br/ ><br /><br /> Te saluda el equipo de  FrioLam";
        
            $cabeceras = 'From: friolam@gmail.com' . "\r\n";
            
            $cabeceras .= 'MIME-Version: 1.0' . "\r\n";
            
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($para, $asunto, utf8_encode($mensaje), $cabeceras);
            $return = ['msg' => 'done','user_id' => $res->id];
        }
        else
        {
            $return = ['msg' => 'error','error' => '¡Lo siento! Este correo electrónico no está registrado con nosotros.'];
        }

        return $return;
    }

    public function verify($data)
    {
        $res = AppUser::where('id',$data['user_id'])->where('otp',$data['otp'])->first();

        if(isset($res->id))
        {
            $return = ['msg' => 'done','user_id' => $res->id];
        }
        else
        {
            $return = ['msg' => 'error','error' => '¡Lo siento! OTP no coincide.'];
        }

        return $return;
    }

    public function updatePassword($data)
    {
        $res = AppUser::where('id',$data['user_id'])->first();

        if(isset($res->id))
        {
            $res->password = $data['password'];
            $res->save();

            $return = ['msg' => 'done','user_id' => $res->id];
        }
        else
        {
            $return = ['msg' => 'error','error' => '¡Lo siento! Algo salió mal.'];
        }

        return $return;
    }

    public function countOrder($id)
    {
        return Commaned::where('user_id',$id)->where('status','>',0)->count();
    }
}
