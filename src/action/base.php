<?php
function AVCONNECT_action($type, $data)  {
    if($type == "product_update"){
        $product_id = $data['id'];
        AVCONNECT_action_product_update($product_id);
    }
}