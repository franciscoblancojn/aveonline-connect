<?php

class AVCONNECT_api_ave
{
    private array $settings;

    private $API_URL_AUTHENTICATE = 'https://app.aveonline.co/api/auth/v3.0/index.php';

    public function __construct()
    {
        $zones = WC_Shipping_Zones::get_zones();
        foreach ($zones as $zone) {
            foreach ($zone['shipping_methods'] as $method) {
                if ($method->id === 'wc_aveonline_shipping') {
                    $this->settings = $method->settings;
                }
            }
        }
    }
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
                "Content-Type: application/json"
            ),

        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        $response =  json_decode($response);
        return $response;
    }
    public function auth()
    {
        $json_body = (array(
            "tipo" => "AuthProduct",
            "user" => $this->settings['user'],
            "password" => $this->settings['password'],
            // "acceso" => "ecommerce",
            "tokenTime" => "10000"
        ));
        // var_dump($json_body);
        return $this->request($this->API_URL_AUTHENTICATE, $json_body);
    }
}
