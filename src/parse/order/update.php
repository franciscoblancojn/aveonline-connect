<?php
function AVCONNECT_parseOrderUpdate(int $order_id)
{
    $order = wc_get_order($order_id);

    if (!$order) {
        return null;
    }

    $items = [];
    $grandTotalVol      = 0;
    $grandTotalPeso     = 0;
    $grandTotalDeclarado = 0;

    /** @var WC_Order_Item_Product $item */
    foreach ($order->get_items() as $item) {
        $product        = $item->get_product();
        $product_id     = $product ? $product->get_id() : 0;
        $product_sku    = $product ? $product->get_sku() : null;
        $product_price  = $product ? (float) $product->get_price() : 0;
        $product_weight = $product ? (float) $product->get_weight() : 0;
        $product_length = $product ? (float) $product->get_length() : 0;
        $product_width  = $product ? (float) $product->get_width() : 0;
        $product_height = $product ? (float) $product->get_height() : 0;

        $qty = (int) $item->get_quantity();
        $vol = $product_length * $product_width * $product_height;
        $declared = (float) get_post_meta($product_id, '_custom_valor_declarado', true) ?: $product_price;

        $items[] = [
            "productRef"     => $product_sku ?: "REF-" . $product_id,
            "rateValue"      => $product_price,
            "ivaValue"       => 0, // ajusta si tienes IVA en meta
            "quantity"       => $qty,
            "peso"           => $product_weight,
            "vol"            => $vol,
            "declarado"      => $declared,
            "totalValue"     => $product_price * $qty,
            "subTotalValue"  => $product_price * $qty,
        ];

        $grandTotalVol      += ($vol * $qty);
        $grandTotalPeso     += ($product_weight * $qty);
        $grandTotalDeclarado += ($declared * $qty);
    }

    // Obtener el ID remoto de la orden almacenado en meta
    $remote_order_id = get_post_meta($order_id, AVCONNECT_KEY_ORDER_REF, true);

    // Cotización previa (si existe)
    $order_request_ave = json_decode(get_post_meta($order_id, 'AVSHME_generate_guia_request', true), true);

    // Dirección destino
    $city  = $order->get_shipping_city();
    $state = $order->get_shipping_state();
    $destino = AVSHME_reajuste_code_aveonline(strtoupper($city . " (" . $state . ")"));

    // Totales
    $total_amount = (float) $order->get_total();
    $subtotal     = (float) $order->get_subtotal();
    $tax_total    = (float) $order->get_total_tax();

    $data = [
        "orderId"               => $remote_order_id,
        "numeropedidoExterno"   => $order->get_order_number(),
        "items"                 => $items,
        "subTotalValue"         => $subtotal,
        "vatValue"              => $tax_total,
        "totalAmountValue"      => $total_amount,
        "grandTotalValue"       => $total_amount,
        "grandTotalVol"         => $grandTotalVol,
        "grandTotalPeso"        => $grandTotalPeso,
        "grandTotalUnit"        => (float) $order->get_item_count(),
        "grandTotalDeclarado"   => $grandTotalDeclarado,
        "grandTotalDeclaradoValue" => $grandTotalDeclarado,
        "paymentCliente"        => 1, // 1=SI paga cliente, 2=NO
        "recaudo"               => $total_amount,
        "recaudoValue"          => $total_amount,
        "paymentAsumecosto"     => 1, // 1=SI, 2=NO
        "clientDestino"         => $destino,
        "valorEnvio"            => (float) $order->get_shipping_total(),
        "valorEnvioValue"       => (float) $order->get_shipping_total(),
        "cadenaEnvio"           => "",
        "seloperadorEnvio"      => $order_request_ave['idtransportador'] ?? null,
        "clientContact"         => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
        "clientId"              => AVSHME_get_options($order_id, '_cedula'),
        "clientDir"             => $order->get_shipping_address_1(),
        "clientTel"             => preg_replace('/\D/', '', $order->get_billing_phone()),
        "clientEmail"           => $order->get_billing_email(),
        "plugin"                => "aveonline",
        "obs"                   => $order->get_customer_note(),
        "pagado"                => true,
    ];

    return $data;
}
