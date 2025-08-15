<?php

class AVCONNECT_api_ave_order extends AVCONNECT_api_ave_base
{
    public function create($data)
    {
        try {
            // Esquema de cada ítem del pedido
            $itemSchema = AVCONNECT_Validator('item')->isObject([
                'productRef'       => AVCONNECT_Validator('productRef')->isRequired()->isString(),
                'rateValue'        => AVCONNECT_Validator('rateValue')->isNumber(),
                'quantity'         => AVCONNECT_Validator('quantity')->isRequired()->isNumber(),
                'peso'             => AVCONNECT_Validator('peso')->isNumber(),
                'vol'              => AVCONNECT_Validator('vol')->isNumber(),
                'descuentoValue'   => AVCONNECT_Validator('descuentoValue')->isNumber(),
                'descuento'        => AVCONNECT_Validator('descuento')->isNumber(),
                'numerodescuento'  => AVCONNECT_Validator('numerodescuento')->isNumber(),
                'declarado'        => AVCONNECT_Validator('declarado')->isNumber(),
                'totalValue'       => AVCONNECT_Validator('totalValue')->isNumber(),
                'subTotalValue'    => AVCONNECT_Validator('subTotalValue')->isNumber(),
            ]);

            // Esquema principal del pedido
            $schema = AVCONNECT_Validator('order')->isObject([
                'numeropedidoExterno'   => AVCONNECT_Validator('numeropedidoExterno')->isString(),
                'idAgente'              => AVCONNECT_Validator('idAgente')->isRequired()->isNumber(),
                'items'                 => AVCONNECT_Validator('items')->isRequired()->isArray($itemSchema),
                'subTotalValue'         => AVCONNECT_Validator('subTotalValue')->isNumber(),
                'totalAmountValue'      => AVCONNECT_Validator('totalAmountValue')->isNumber(),
                'paymentCliente'        => AVCONNECT_Validator('paymentCliente')->isRequired()->isNumber(),
                'recaudo'               => AVCONNECT_Validator('recaudo')->isRequired()->isNumber(),
                'recaudoValue'          => AVCONNECT_Validator('recaudoValue')->isRequired()->isNumber(),
                'paymentAsumecosto'     => AVCONNECT_Validator('paymentAsumecosto')->isRequired()->isNumber(),
                'clientDestino'         => AVCONNECT_Validator('clientDestino')->isRequired()->isString(),
                'valorEnvio'            => AVCONNECT_Validator('valorEnvio')->isNumber(),
                'valorEnvioValue'       => AVCONNECT_Validator('valorEnvioValue')->isNumber(),
                'seloperadorEnvio'      => AVCONNECT_Validator('seloperadorEnvio')->isNumber(),
                'clientContact'         => AVCONNECT_Validator('clientContact')->isRequired()->isString(),
                'clientId'              => AVCONNECT_Validator('clientId')->isRequired()->isString(),
                'clientDir'             => AVCONNECT_Validator('clientDir')->isString(),
                'clientTel'             => AVCONNECT_Validator('clientTel')->isNumber(),
                'clientEmail'           => AVCONNECT_Validator('clientEmail')->isString(),
                'plugin'                => AVCONNECT_Validator('plugin')->isString(),
                'noGenerarEnvio'        => AVCONNECT_Validator('noGenerarEnvio')->isNumber(),
                'pagado'                => AVCONNECT_Validator('pagado')->isBoolean(),
                'enviopropio'           => AVCONNECT_Validator('enviopropio')->isBoolean(),
            ]);

            // Validar datos recibidos
            $schema->validate($data);

            // Armar cuerpo de la petición
            $json_body = array_merge([
                "tipo"    => "authave",
                "token"   => $this->user['token'],
                "empresa" => $this->user['idEnterprise'],
            ], $data);

            // Hacer request
            return $this->request($this->API_URL_ORDER_CREATE_URL, $json_body);
        } catch (\Throwable $th) {
            return [
                'status'  => 'error',
                'message' => $th->getMessage(),
                'error'   => $th
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
            $result = $this->request($this->API_URL_ORDER_UPDATE_URL, $json_body);
            return $result;
        } catch (\Throwable $th) {
            return [
                'status'  => 'error',
                'message' => $th->getMessage(),
                'error'   => $th
            ];
        }
    }
    public function get()
    {
        try {
            // Agregar campos base de autenticación
            $json_body = array_merge([
                "tipo"    => "authave",
                "token"   => $this->user['token'],
                "empresa" => $this->user['idEnterprise'],
            ], []);

            // Llamar API
            $result = $this->request($this->API_URL_ORDER_GET_URL, $json_body);
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
