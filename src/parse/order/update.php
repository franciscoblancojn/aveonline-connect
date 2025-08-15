<?php
function AVCONNECT_parseOrderUpdate(int $order_id)
{
    $order = wc_get_order($order_id);

    if (!$order) {
        return null;
    }

    // Obtener el ID remoto de la orden almacenado en meta
    $remote_order_id = get_post_meta($order_id, AVCONNECT_KEY_ORDER_REF, true);

    // Datos base para actualización
    $data = [
        "orderId"                => $remote_order_id,
        "numeropedidoExterno"    => $order->get_order_number(),
        "bodegaName"             => null, // Si lo manejas en meta puedes reemplazarlo
        "idAgente"               => get_post_meta($order_id, AVCONNECT_KEY_AGENT_ID, true),
        "items"                  => [],
        "subTotalValue"          => (float) $order->get_subtotal(),
        "vatValue"               => (float) $order->get_total_tax(),
        "totalAmountValue"       => (float) $order->get_total(),
        "grandTotalValue"        => (float) $order->get_total(),
        "grandTotalVol"          => 0,
        "grandTotalPeso"         => 0,
        "grandTotalUnit"         => (float) $order->get_item_count(),
        "grandTotalDeclarado"    => 0,
        "grandTotalDeclaradoValue"=> 0,
        "paymentCliente"         => 1, // o 2 según corresponda
        "recaudo"                => 0,
        "recaudoValue"           => (float) $order->get_total(),
        "paymentAsumecosto"      => 1, // o 2 según corresponda
        "clientDestino"          => $order->get_shipping_city() . '(' . $order->get_shipping_state() . ')',
        "valorEnvio"             => 0,
        "valorEnvioValue"        => 0,
        "cadenaEnvio"            => "",
        "seloperadorEnvio"       => null,
        "clientContact"          => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
        "clientId"               => get_post_meta($order_id, '_billing_document', true), // si tienes documento en meta
        "clientDir"              => $order->get_shipping_address_1(),
        "clientTel"              => preg_replace('/\D/', '', $order->get_billing_phone()),
        "clientEmail"            => $order->get_billing_email(),
        "nroFactura"             => "",
        "plugin"                 => "aveonline",
        "noEditarEnvio"          => 0,
        "revisarCE"              => 0,
        "obs"                    => $order->get_customer_note(),
        "pagado"                 => $order->is_paid(),
    ];

    // Recorrer ítems del pedido
    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if ($product) {
            $data['items'][] = [
                "productRef"  => $product->get_sku() ?: "REF-" . $product->get_id(),
                "rateValue"   => (float) $product->get_price(),
                "ivaValue"    => 0,
                "quantity"    => (int) $item->get_quantity(),
                "peso"        => (float) $product->get_weight(),
                "vol"         => 0,
                "declarado"   => (float) $product->get_price(),
                "totalValue"  => (float) ($product->get_price() * $item->get_quantity()),
            ];

            // Sumar peso total
            $data['grandTotalPeso'] += ((float) $product->get_weight()) * $item->get_quantity();
        }
    }

    return $data;
}
