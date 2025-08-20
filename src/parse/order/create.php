<?php
function AVCONNECT_parseOrderCreate(int $order_id)
{
    $order = wc_get_order($order_id);

    if (!$order) {
        return null;
    }

    // Obtener items del pedido
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
            "productRef"     => $product_sku ?: "REF-" . $product_id,
            "rateValue"      => $product_price,
            "quantity"       => $qty,
            "peso"           => $product_weight,
            "vol"            => $product_length * $product_width * $product_height,
            "declarado"      => $product_price,
            "totalValue"     => $product_price * $qty,
            "subTotalValue"  => $product_price * $qty,
        ];
    }

    // Dirección de envío
    $shipping = [
        'name'     => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
        'address'  => $order->get_shipping_address_1(),
        'city'     => $order->get_shipping_city(),
        'postcode' => $order->get_shipping_postcode(),
        'phone'    => $order->get_billing_phone(),
        'email'    => $order->get_billing_email(),
    ];

    // Calcular totales
    $total_amount = (float) $order->get_total();
    $subtotal     = (float) $order->get_subtotal();

    $order_request_ave = json_decode(get_post_meta(197, 'AVSHME_generate_guia_request', true), true);

    // Construir el array final
    $data = [
        "order_id"             => (string) $order_id,
        "numeropedidoExterno"  => (string) $order_id,
        "items"                => $items,
        "subTotalValue"        => $subtotal,
        "totalAmountValue"     => $total_amount,
        "recaudo"              => $total_amount,
        "recaudoValue"         => $total_amount,
        "clientDestino"        => strtoupper($shipping['city']), // Formato según API
        "valorEnvio"           => $order->get_shipping_total(), // Puedes poner el costo real si lo tienes
        "clientContact"        => $shipping['name'],
        "clientId"             => AVSHME_get_options($order_id, '_cedula'), // Documento del cliente (añadir si lo tienes)
        "clientDir"            => $shipping['address'],
        "clientTel"            => preg_replace('/\D/', '', $shipping['phone']),
        "clientEmail"          => $shipping['email'],
        "valorEnvioValue"      =>  (float) $order->get_shipping_total(),
        "pagado"               => true,
        "enviopropio"          => false,
        "noGenerarEnvio"       => 0,
        "paymentCliente"       => 1, // 1=Sí paga cliente
        "paymentAsumecosto"    => 1, // 1=Sí
        "plugin"               => "aveonline",
        "seloperadorEnvio"     => $order_request_ave['idtransportador'], // Cambiar por valor real si aplica
    ];

    return $data;
}
