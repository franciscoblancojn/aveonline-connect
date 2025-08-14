<?php

class AVCONNECT_api_ave_auth extends AVCONNECT_api_ave_base
{
    public function auth()
    {
        $json_body = (array(
            "tipo" => "AuthProduct",
            "user" => $this->settings['user'],
            "password" => $this->settings['password'],
            "tokenTime" => "10000"
        ));
        $result = $this->request($this->API_URL_AUTHENTICATE, $json_body);
        if($result && $result['data'] && $result['data']['token']){
            $this->user = $result['data'];
        }
        return $result;
    }
}
