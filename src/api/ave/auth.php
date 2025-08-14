<?php

class AVCONNECT_api_ave_auth extends AVCONNECT_api_base
{
    private array $settings;
    protected array $user;

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
    public function auth()
    {
        $json_body = (array(
            "tipo" => "AuthProduct",
            "user" => $this->settings['user'],
            "password" => $this->settings['password'],
            "tokenTime" => "10000"
        ));
        // var_dump($json_body);
        $result = $this->request($this->API_URL_AUTHENTICATE, $json_body);
        if($result && $result['data'] && $result['data']['token']){
            $this->user = $result['data'];
        }
        return $result;
    }
    
}
