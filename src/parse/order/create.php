<?php
function AVCONNECT_parseOrderCreate(int $order_id)
{
    $order = wc_get_order($order_id);

    if (!$order) {
        return null;
    }

    // Obtener items del pedido
    $items = [];
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
    $total_weight = array_sum(array_column($items, 'peso'));
    $total_units  = array_sum(array_column($items, 'quantity'));

    // Construir el array final
    $data = [
        "order_id"             => (string) $order_id,
        "numeropedidoExterno"  => (string) $order->get_id(),
        "idAgente"             => 10961, // Cambiar por el valor real
        "items"                => $items,
        "subTotalValue"        => $subtotal,
        "totalAmountValue"     => $total_amount,
        "paymentCliente"       => 1, // 1=Sí paga cliente
        "recaudo"              => $total_amount,
        "recaudoValue"         => $total_amount,
        "paymentAsumecosto"    => 1, // 1=Sí
        "clientDestino"        => strtoupper($shipping['city']), // Formato según API
        "valorEnvio"           => 0, // Puedes poner el costo real si lo tienes
        "valorEnvioValue"      => 0,
        "seloperadorEnvio"     => 29, // Cambiar por valor real si aplica
        "clientContact"        => $shipping['name'],
        "clientId"             => "00000000", // Documento del cliente (añadir si lo tienes)
        "clientDir"            => $shipping['address'],
        "clientTel"            => preg_replace('/\D/', '', $shipping['phone']),
        "clientEmail"          => $shipping['email'],
        "plugin"               => "aveonline",
        "noGenerarEnvio"       => 0,
        "pagado"               => false,
        "enviopropio"          => false,
    ];

    return $data;
}
