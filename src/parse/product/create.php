<?php
function AVCONNECT_parseProductCreate(string $product_id)
{
    $product = wc_get_product($product_id);

    if (!$product) {
        return null;
    }

    // Datos básicos del producto
    $data = [
        "productName"            => $product->get_name(),
        "productDesc"            => $product->get_description(),
        "shortDesc"              => $product->get_short_description(),
        "productRef"             => $product->get_sku() ?: "REF-" . $product_id,
        "referenciaEquivalente"  => $product->get_sku() ?: "REF-" . $product_id,
        "rate"                   => (float) $product->get_regular_price(),
        "sugerido"               => (float) $product->get_price(),
        "productStatus"          => $product->get_status() === 'publish' ? 1 : 2,
        "productImageUrl"        => wp_get_attachment_url($product->get_image_id()) ?: "",
        "peso"                   => (float) $product->get_weight(),
        "alto"                   => (float) $product->get_height(),
        "ancho"                  => (float) $product->get_width(),
        "largo"                  => (float) $product->get_length(),
        "variants"               => []
    ];

    // Si es variable, obtener las variaciones
    if ($product->is_type('variable')) {
        $variation_ids = $product->get_children();
        foreach ($variation_ids as $variation_id) {
            $variation = wc_get_product($variation_id);
            $data['variants'][] = [
                "name"    => $variation->get_name(),
                "sku"     => $variation->get_sku() ?: "VAR-" . $variation_id,
                "price"   => (float) $variation->get_price(),
                "status"  => $variation->get_status() === 'publish' ? 1 : 2,
                "weight"  => (float) $variation->get_weight(),
                "length"  => (float) $variation->get_length(),
                "width"   => (float) $variation->get_width(),
                "height"  => (float) $variation->get_height(),
            ];
        }
    } 

    return $data;
}
