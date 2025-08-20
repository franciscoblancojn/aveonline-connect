<?php
function AVCONNECT_parseOrderUpdate(int $order_id)
{
    $order = wc_get_order($order_id);

    if (!$order) {
        return null;
    }
    $items = [];
    /** @var WC_Order_Item_Product $item */
    foreach ($order->get_items() as $item) {
        $product       = $item->get_product();
        $product_id    = $product ? $product->get_id() : 0;
        $product_sku   = $product ? $product->get_sku() : null;
        $product_price = $product ? (float) $product->get_price() : 0;
        $product_weight = $product ? (float) $product->get_weight() : 0;
        $product_length = $product ? (float) $product->get_length() : 0;
        $product_width  = $product ? (float) $product->get_width() : 0;
        $product_height = $product ? (float) $product->get_height() : 0;

        $qty = (int) $item->get_quantity();

        $items[] = [
            "id"     => $product_sku ?: "REF-" . $product_id,
            "price"      => $product_price,
            "stock"       => $qty,
            "peso"           => $product_weight,
            "length"           => $product_length,
            "width"           => $product_width,
            "height"           => $product_height,
            "declared_value"      => get_post_meta($product_id, '_custom_valor_declarado', true),
            "totalValue"     => $product_price * $qty,
            "subTotalValue"  => $product_price * $qty,
        ];
    }

    // Obtener el ID remoto de la orden almacenado en meta
    $remote_order_id = get_post_meta($order_id, AVCONNECT_KEY_ORDER_REF, true);

    // Datos base para actualización
    $city  = $order->get_shipping_city();   // Ciudad de envío
    $state = $order->get_shipping_state();  // Estado / Departamento de envío
    $destino = AVSHME_reajuste_code_aveonline(strtoupper($city . " (" . $state . ")"));
    $order_request_ave = json_decode(get_post_meta(197, 'AVSHME_generate_guia_request', true), true);
    $data = [
        "orderId"                => $remote_order_id,
        "numeropedidoExterno"    => $order->get_order_number(),
        "items"                  => $items,
        "subTotalValue"          => (float) $order->get_subtotal(),
        "vatValue"               => (float) $order->get_total_tax(),
        "totalAmountValue"       => (float) $order->get_total(),
        "grandTotalValue"        => (float) $order->get_total(),
        "grandTotalUnit"         => (float) $order->get_item_count(),
        "paymentCliente"         => 1, // o 2 según corresponda
        "paymentAsumecosto"      => 1, // o 2 según corresponda
        "clientDestino"          => $destino,
        "valorEnvio"             => (float) $order->get_shipping_total(),
        "valorEnvioValue"        => (float) $order->get_shipping_total(),
        "cadenaEnvio"            => "",
        "seloperadorEnvio"       => $order_request_ave['idtransportador'],
        "clientContact"          => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
        "clientId"               => AVSHME_get_options($order_id, '_cedula'), // si tienes documento en meta
        "clientDir"              => $order->get_shipping_address_1(),
        "clientTel"              => preg_replace('/\D/', '', $order->get_billing_phone()),
        "clientEmail"            => $order->get_billing_email(),
        "plugin"                 => "aveonline",
        "obs"                    => $order->get_customer_note(),
        "pagado"                 => $order->is_paid(),
        "recaudo"                => (float) $order->get_total(),
        "recaudoValue"           => (float) $order->get_total(),
    ];

    return $data;
}
