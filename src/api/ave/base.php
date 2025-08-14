<?php

class AVCONNECT_api_ave_base extends AVCONNECT_api_base
{
    protected array $settings;
    protected array $user;

    protected $API_URL_AUTHENTICATE = 'https://app.aveonline.co/api/auth/v3.0/index.php';
    protected $API_URL_PRODUCT_GET_URL = 'https://app.aveonline.co/avestock/api/fetchProducts.php';
    protected $API_URL_PRODUCT_CREATE_URL = 'https://app.aveonline.co/avestock/api/createProduct.php';
    protected $API_URL_PRODUCT_UPDATE_URL = 'https://app.aveonline.co/avestock/api/editProduct.php';

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
    
}
