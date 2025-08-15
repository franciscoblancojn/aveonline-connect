<?php

class AVCONNECT_api_ave extends AVCONNECT_api_ave_base
{
    public AVCONNECT_api_ave_auth $auth;
    public AVCONNECT_api_ave_product $product;
    public AVCONNECT_api_ave_order $order;

    public function __construct() {
        $this->auth = new AVCONNECT_api_ave_auth();
        $this->product = new AVCONNECT_api_ave_product();
        $this->order = new AVCONNECT_api_ave_order();
        
        $this->auth();
    }
    
    private function auth()
    {
        $result = $this->auth->auth();
        $this->user = $this->auth->user;
        $this->product->user = $this->auth->user;
        $this->order->user = $this->auth->user;
        return $result;
    }
}
