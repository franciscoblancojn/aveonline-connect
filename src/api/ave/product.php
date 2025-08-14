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
            $result = $this->request($this->API_URL_CREATE_URL, $json_body);
            return $result;
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                'message' =>  $th->getMessage(),
                'error' => $th
            ];
        }
    }
}
