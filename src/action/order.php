<?php

// function AVCONNECT_action_order_create($order_id)
// {
//     $api = new AVCONNECT_api_ave();
//     $result = $api->order->create(AVCONNECT_parseorderCreate($order_id));
//     AVCONNECT_addLog([
//         "type" => "AVCONNECT_action_order_create",
//         "order_id" => $order_id,
//         "result" => $result
//     ]);
// }
// function AVCONNECT_action_order_update($order_id)
// {
//     $api = new AVCONNECT_api_ave();
//     $id = get_post_meta($order_id, AVCONNECT_KEY_order_REF);
//     if ($id) {
//         $result = $api->order->update(AVCONNECT_parseorderUpdate($order_id));
//         AVCONNECT_addLog([
//             "type" => "AVCONNECT_action_order_update",
//             "order_id" => $order_id,
//             "result" => $result
//         ]);
//     } else {
//         $result = $api->order->create(AVCONNECT_parseorderCreate($order_id));
//         AVCONNECT_addLog([
//             "type" => "AVCONNECT_action_order_create",
//             "order_id" => $order_id,
//             "result" => $result
//         ]);
//     }
// }
