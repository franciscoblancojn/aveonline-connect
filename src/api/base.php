<?php

class AVCONNECT_api_base
{
    public function request(String $url, array $json)
    {
        // var_dump([
        //     'url'=>$url,
        //     'json'=>$json
        // ]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: ".$json['token']
            ),

        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        $response =  json_decode($response,true);

        echo json_encode([
            "url"=>$url,
            "send"=>$json,
            "result"=>$response
        ]);
        return $response;
    }
}
