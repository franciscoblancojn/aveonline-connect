<?php

class AVCONNECT_api_ave_auth extends AVCONNECT_api_ave_base
{
    public function create()
    {
        $json_body = (array(
            "tipo" => "authave",
            "user" => $this->user['token'],
            "empresa" => $this->user['idEnterprise'],
        ));
        $result = $this->request($this->API_URL_CREATE_URL, $json_body);
        return $result;
    }
    
}
