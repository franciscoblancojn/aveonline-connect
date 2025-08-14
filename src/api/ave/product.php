<?php

class AVCONNECT_api_ave_product extends AVCONNECT_api_ave_base
{
    public function create($data)
    {
        try {
            // Esquema para las variantes
            $variantSchema = AVCONNECT_Validator('variant')->isObject([
                'name' => (AVCONNECT_Validator('name'))->isRequired()->isString(),
                'sku' => (AVCONNECT_Validator('sku'))->isRequired()->isString(),
                // 'cost' => (AVCONNECT_Validator('cost'))->isRequired()->isNumber(),
                'price' => (AVCONNECT_Validator('price'))->isRequired()->isNumber(),
                // 'suggested_price' => (AVCONNECT_Validator('suggested_price'))->isNumber(),
                'status' => (AVCONNECT_Validator('status'))->isRequired()->isNumber(),
                // 'iva' => (AVCONNECT_Validator('iva'))->isRequired()->isNumber(),
                // 'stock' => (AVCONNECT_Validator('stock'))->isRequired()->isNumber(),
                'weight' => (AVCONNECT_Validator('weight'))->isNumber(),
                'length' => (AVCONNECT_Validator('length'))->isNumber(),
                'width' => (AVCONNECT_Validator('width'))->isNumber(),
                'height' => (AVCONNECT_Validator('height'))->isNumber(),
                // 'warehouse' => (AVCONNECT_Validator('warehouse'))->isNumber(),
                // 'negative_inventory' => (AVCONNECT_Validator('negative_inventory'))->isBoolean(),
                // 'min_price' => (AVCONNECT_Validator('min_price'))->isNumber(),
                // 'max_price' => (AVCONNECT_Validator('max_price'))->isNumber(),
                // 'declared_value' => (AVCONNECT_Validator('declared_value'))->isNumber(),
                // 'dropshipping_price' => (AVCONNECT_Validator('dropshipping_price'))->isNumber(),
                // 'additional_references' => (AVCONNECT_Validator('additional_references'))->isArray(
                //     (AVCONNECT_Validator('ref'))->isString()
                // ),
            ]);

            // Esquema principal
            $schema = AVCONNECT_Validator('product')->isObject([
                'product_id' => (AVCONNECT_Validator('product_id'))->isRequired()->isString(),
                'productName' => (AVCONNECT_Validator('productName'))->isRequired()->isString(),
                'productRef' => (AVCONNECT_Validator('productRef'))->isRequired()->isString(),
                'shortDesc' => (AVCONNECT_Validator('shortDesc'))->isString(),
                // 'warrantyTime' => (AVCONNECT_Validator('warrantyTime'))->isNumber(),
                // 'warranty' => (AVCONNECT_Validator('warranty'))->isString(),
                // 'sendConditions' => (AVCONNECT_Validator('sendConditions'))->isString(),
                // 'costo' => (AVCONNECT_Validator('costo'))->isRequired()->isNumber(),
                'rate' => (AVCONNECT_Validator('rate'))->isRequired()->isNumber(),
                'sugerido' => (AVCONNECT_Validator('sugerido'))->isNumber(),
                'productStatus' => (AVCONNECT_Validator('productStatus'))->isNumber(),
                // 'categoryName' => (AVCONNECT_Validator('categoryName'))->isNumber(),
                // 'tax' => (AVCONNECT_Validator('tax'))->isNumber(),
                // 'productImage' => (AVCONNECT_Validator('productImage')), // puede ser null
                'productImageUrl' => (AVCONNECT_Validator('productImageUrl'))->isString(),
                // 'productVideoUrl' => (AVCONNECT_Validator('productVideoUrl'))->isString(),
                'peso' => (AVCONNECT_Validator('peso'))->isNumber(),
                'alto' => (AVCONNECT_Validator('alto'))->isNumber(),
                'ancho' => (AVCONNECT_Validator('ancho'))->isNumber(),
                'largo' => (AVCONNECT_Validator('largo'))->isNumber(),
                // 'ubicacion' => (AVCONNECT_Validator('ubicacion'))->isString(),
                // 'ubicacioncliente' => (AVCONNECT_Validator('ubicacioncliente'))->isString(),
                // 'minimo' => (AVCONNECT_Validator('minimo'))->isNumber(),
                // 'brandName' => (AVCONNECT_Validator('brandName'))->isNumber(),
                // 'marcaName' => (AVCONNECT_Validator('marcaName'))->isNumber(),
                // 'tallaName' => (AVCONNECT_Validator('tallaName'))->isNumber(),
                // 'colorName' => (AVCONNECT_Validator('colorName'))->isNumber(),
                // 'presentacionName' => (AVCONNECT_Validator('presentacionName'))->isNumber(),
                // 'etiquetasName' => (AVCONNECT_Validator('etiquetasName'))->isString(),
                // 'declarado' => (AVCONNECT_Validator('declarado'))->isNumber(),
                // 'priceMin' => (AVCONNECT_Validator('priceMin'))->isNumber(),
                // 'priceMax' => (AVCONNECT_Validator('priceMax'))->isNumber(),
                // 'dropshipperPrice' => (AVCONNECT_Validator('dropshipperPrice'))->isNumber(),
                // 'prepTime' => (AVCONNECT_Validator('prepTime'))->isNumber(),
                // 'returnConditions' => (AVCONNECT_Validator('returnConditions'))->isString(),
                // 'bodegaName' => (AVCONNECT_Validator('bodegaName'))->isNumber(),
                // 'unidades' => (AVCONNECT_Validator('unidades'))->isNumber(),
                // Aquí variants es un string JSON, lo decodificamos antes de validar
                'variants' => (AVCONNECT_Validator('variants_array'))
                    ->isArray($variantSchema),
            ]);
            $schema->validate($data);

            if ($data['variants']) {
                $data['variants'] = json_encode($data['variants']);
            }

            $json_body = array_merge(array(
                "tipo" => "authave",
                "token" => $this->user['token'],
                "empresa" => $this->user['idEnterprise'],
            ), $data);
            $result = $this->request($this->API_URL_PRODUCT_CREATE_URL, $json_body);
            if ($result['createdProductId']) {
                update_post_meta($data['product_id'], AVCONNECT_KEY_PRODUCT_REF, $result['createdProductId']);
            }
            if ($result['idproducto']) {
                update_post_meta($data['product_id'], AVCONNECT_KEY_PRODUCT_REF, $result['idproducto']);
            }

            return $result;
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'message' =>  $th->getMessage(),
                'error' => $th
            ];
        }
    }
    public function update($data)
    {
        try {
            // Esquema para variantes (según la doc)
            $variantSchema = AVCONNECT_Validator('variant')->isObject([
                'id'                  => (AVCONNECT_Validator('id'))->isNumber(), // opcional
                'name'                => (AVCONNECT_Validator('name'))->isRequired()->isString(),
                'sku'                 => (AVCONNECT_Validator('sku'))->isRequired()->isString(),
                // 'cost'                => (AVCONNECT_Validator('cost'))->isNumber(),
                'price'               => (AVCONNECT_Validator('price'))->isRequired()->isNumber(),
                'suggested_price'     => (AVCONNECT_Validator('suggested_price'))->isNumber(),
                'status'              => (AVCONNECT_Validator('status'))->isRequired()->isNumber(),
                // 'iva'                 => (AVCONNECT_Validator('iva'))->isNumber(),
                'stock'               => (AVCONNECT_Validator('stock'))->isNumber(),
                'weight'              => (AVCONNECT_Validator('weight'))->isNumber(),
                'length'              => (AVCONNECT_Validator('length'))->isNumber(),
                'width'               => (AVCONNECT_Validator('width'))->isNumber(),
                'height'              => (AVCONNECT_Validator('height'))->isNumber(),
                // 'warehouse'           => (AVCONNECT_Validator('warehouse'))->isNumber(),
                // 'negative_inventory'  => (AVCONNECT_Validator('negative_inventory'))->isBoolean(),
                // 'min_price'           => (AVCONNECT_Validator('min_price'))->isNumber(),
                // 'max_price'           => (AVCONNECT_Validator('max_price'))->isNumber(),
                // 'declared_value'      => (AVCONNECT_Validator('declared_value'))->isNumber(),
                // 'dropshipping_price'  => (AVCONNECT_Validator('dropshipping_price'))->isNumber(),
                // 'additional_references' => (AVCONNECT_Validator('additional_references'))->isArray(
                //     (AVCONNECT_Validator('ref'))->isString()
                // ),
            ]);

            // Esquema para producto (mapeando los edit...)
            $schema = AVCONNECT_Validator('product')->isObject([
                'productId'              => (AVCONNECT_Validator('productId'))->isRequired()->isNumber(),
                'editProductName'        => (AVCONNECT_Validator('editProductName'))->isRequired()->isString(),
                'editProductDesc'        => (AVCONNECT_Validator('editProductDesc'))->isString(),
                'editShortDesc'          => (AVCONNECT_Validator('editShortDesc'))->isString(),
                // 'editWarrantyTime'       => (AVCONNECT_Validator('editWarrantyTime'))->isNumber(),
                // 'editWarranty'           => (AVCONNECT_Validator('editWarranty'))->isString(),
                // 'editSendConditions'     => (AVCONNECT_Validator('editSendConditions'))->isString(),
                // 'editReturnConditions'   => (AVCONNECT_Validator('editReturnConditions'))->isString(),
                'editProductRef'         => (AVCONNECT_Validator('editProductRef'))->isRequired()->isString(),
                'referenciaEquivalente'  => (AVCONNECT_Validator('referenciaEquivalente'))->isString(),
                // 'referenciaEquivalente2' => (AVCONNECT_Validator('referenciaEquivalente2'))->isString(),
                // 'referenciaEquivalente3' => (AVCONNECT_Validator('referenciaEquivalente3'))->isString(),
                // 'referenciaEquivalente4' => (AVCONNECT_Validator('referenciaEquivalente4'))->isString(),
                // 'referenciaEquivalente5' => (AVCONNECT_Validator('referenciaEquivalente5'))->isString(),
                // 'editCosto'              => (AVCONNECT_Validator('editCosto'))->isNumber(),
                'editRate'               => (AVCONNECT_Validator('editRate'))->isRequired()->isNumber(),
                'editSugerido'           => (AVCONNECT_Validator('editSugerido'))->isNumber(),
                // 'editDeclarado'          => (AVCONNECT_Validator('editDeclarado'))->isNumber(),
                'editProductStatus'      => (AVCONNECT_Validator('editProductStatus'))->isNumber(),
                // 'editCategoryName'       => (AVCONNECT_Validator('editCategoryName'))->isNumber(),
                // 'editBrandName'          => (AVCONNECT_Validator('editBrandName'))->isNumber(),
                // 'editMarcaName'          => (AVCONNECT_Validator('editMarcaName'))->isNumber(),
                // 'editTax'                => (AVCONNECT_Validator('editTax'))->isNumber(),
                // 'editMinimo'             => (AVCONNECT_Validator('editMinimo'))->isNumber(),
                // 'editInventarioNegativo' => (AVCONNECT_Validator('editInventarioNegativo'))->isNumber(),
                // 'editTipoActivacion'     => (AVCONNECT_Validator('editTipoActivacion'))->isNumber(),
                'editPeso'               => (AVCONNECT_Validator('editPeso'))->isNumber(),
                'editAlto'               => (AVCONNECT_Validator('editAlto'))->isNumber(),
                'editAncho'              => (AVCONNECT_Validator('editAncho'))->isNumber(),
                'editLargo'              => (AVCONNECT_Validator('editLargo'))->isNumber(),
                // 'editUbicacion'          => (AVCONNECT_Validator('editUbicacion'))->isString(),
                // 'editUbicacionCliente'   => (AVCONNECT_Validator('editUbicacionCliente'))->isString(),
                // 'editTallaName'          => (AVCONNECT_Validator('editTallaName'))->isNumber(),
                // 'editColorName'          => (AVCONNECT_Validator('editColorName'))->isNumber(),
                // 'editPresentacionName'   => (AVCONNECT_Validator('editPresentacionName'))->isNumber(),
                // 'editEtiquetasName'      => (AVCONNECT_Validator('editEtiquetasName'))->isString(),
                // 'editPriceMin'           => (AVCONNECT_Validator('editPriceMin'))->isNumber(),
                // 'editPriceMax'           => (AVCONNECT_Validator('editPriceMax'))->isNumber(),
                // 'editDropshipperPrice'   => (AVCONNECT_Validator('editDropshipperPrice'))->isNumber(),
                // 'editPrepTime'           => (AVCONNECT_Validator('editPrepTime'))->isNumber(),
                // 'editBodegaName'         => (AVCONNECT_Validator('editBodegaName'))->isNumber(),
                'editProductImageUrlField' => (AVCONNECT_Validator('editProductImageUrlField'))->isString(),
                // 'editProductVideoUrl'    => (AVCONNECT_Validator('editProductVideoUrl'))->isString(),
                'variants'               => (AVCONNECT_Validator('variants_array'))->isArray($variantSchema),
            ]);

            // Validar datos
            $schema->validate($data);

            // Convertir variants a JSON string
            if (!empty($data['variants']) && is_array($data['variants'])) {
                $data['variants'] = json_encode($data['variants']);
            }

            // Agregar campos base de autenticación
            $json_body = array_merge([
                "tipo"    => "authave",
                "token"   => $this->user['token'],
                "empresa" => $this->user['idEnterprise'],
            ], $data);

            // Llamar API
            $result = $this->request($this->API_URL_PRODUCT_UPDATE_URL, $json_body);
            return $result;
        } catch (\Throwable $th) {
            return [
                'status'  => 'error',
                'message' => $th->getMessage(),
                'error'   => $th
            ];
        }
    }
}
