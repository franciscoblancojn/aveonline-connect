<?php
function AVCONNECT_parseOrderUpdate(string $product_id)
{
    $product = wc_get_product($product_id);

    if (!$product) {
        return null;
    }
    $remote_product_id = get_post_meta($product_id, AVCONNECT_KEY_PRODUCT_REF, true);

    // Datos básicos para actualización
    $data = [
        "productId"               => $remote_product_id,
        "editProductName"         => $product->get_name(),
        "editProductDesc"         => $product->get_description(),
        "editShortDesc"           => $product->get_short_description(),
        "editProductRef"          => $product->get_sku() ?: "REF-" . $product_id,
        "referenciaEquivalente"   => $product->get_sku() ?: "REF-" . $product_id,
        "editRate"                => (float) ($product->get_regular_price() ?? 0),
        "editSugerido"            => (float) ($product->get_price() ?? 0),
        "editProductStatus"       => $product->get_status() === 'publish' ? 1 : 2,
        "editProductImageUrlField"=> wp_get_attachment_url($product->get_image_id()) ?: "",
        "editPeso"                => (float) $product->get_weight(),
        "editAlto"                => (float) $product->get_height(),
        "editAncho"               => (float) $product->get_width(),
        "editLargo"               => (float) $product->get_length(),
        "editTax"                 => 0,
        "variants"                => []
    ];

    // Si es variable, obtener las variaciones
    if ($product->is_type('variable')) {
        $variation_ids = $product->get_children();
        foreach ($variation_ids as $variation_id) {
            $variation = wc_get_product($variation_id);
            $data['variants'][] = [
                "id"       => null, // Aquí podrías mapear el ID de la variante en AVEONLINE si lo tienes
                "name"     => $variation->get_name(),
                "sku"      => $variation->get_sku() ?: "VAR-" . $variation_id,
                "price"    => (float) $variation->get_price(),
                "status"   => $variation->get_status() === 'publish' ? 1 : 2,
                "stock"    => (int) $variation->get_stock_quantity(),
                "weight"   => (float) $variation->get_weight(),
                "length"   => (float) $variation->get_length(),
                "width"    => (float) $variation->get_width(),
                "height"   => (float) $variation->get_height(),
            ];
        }
    }

    return $data;
}
