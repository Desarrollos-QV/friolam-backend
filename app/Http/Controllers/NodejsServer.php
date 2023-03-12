<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Twilio\Rest\Client;
use App\Admin;
use App\Language;
class NodejsServer extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


     // Realizamos la peticion de nuevo mandadito
     function NewOrderComm($data)
     {
         $fields = array(
             'id_order' => isset($data['id_order']) ? $data['id_order'] : ''
         );
        
         return $this->CurlGet($fields,"https://us-central1-boxi-driver-b1102.cloudfunctions.net/app/api/newOrderComm/");
     }

     // Cron para termino del pedido
     function notifyClient($data)
     {
        $fields = array(
            'id_order' => isset($data['order_id']) ? $data['order_id'] : 0
        );
       
        return $this->CurlGet($fields,"https://us-central1-boxi-driver-b1102.cloudfunctions.net/app/api/InitCronOrder/");
     }
    
    
    /**
     * Realizamos la peticion
     */
    function CurlGet($fields,$url)
    {
        $fields = json_encode($fields);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $req = json_decode($response,true);

        return $req;
    }
}
