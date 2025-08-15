<?php

function AVCONNECT_action_create($product_id)
{
    $api = new AVCONNECT_api_ave();
    $result = $api->product->create(AVCONNECT_parseProductCreate($product_id));
    AVCONNECT_addLog([
        "type" => "AVCONNECT_action_create",
        "product_id" => $product_id,
        "result" => $result
    ]);
}
function AVCONNECT_action_update($product_id)
{
    $api = new AVCONNECT_api_ave();
    $id = get_post_meta($product_id, AVCONNECT_KEY_PRODUCT_REF);
    if ($id) {
        $result = $api->product->update(AVCONNECT_parseProductUpdate($product_id));
        AVCONNECT_addLog([
            "type" => "AVCONNECT_action_update",
            "product_id" => $product_id,
            "result" => $result
        ]);
    } else {
        $result = $api->product->create(AVCONNECT_parseProductCreate($product_id));
        AVCONNECT_addLog([
            "type" => "AVCONNECT_action_create",
            "product_id" => $product_id,
            "result" => $result
        ]);
    }
}
