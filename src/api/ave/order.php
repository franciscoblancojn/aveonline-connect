<?php

class AVCONNECT_api_ave_order extends AVCONNECT_api_ave_base
{
    public function create($data)
    {
        try {
            $this->onValidateUser();
            // Esquema de cada ítem del pedido
            $itemSchema = AVCONNECT_Validator('item')->isObject([
                'productRef'       => AVCONNECT_Validator('productRef')->isRequired()->isString(),
                'rateValue'        => AVCONNECT_Validator('rateValue')->isNumber(),
                'quantity'         => AVCONNECT_Validator('quantity')->isRequired()->isNumber(),
                'peso'             => AVCONNECT_Validator('peso')->isNumber(),
                'vol'              => AVCONNECT_Validator('vol')->isNumber(),
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

            $result = $this->request($this->API_URL_ORDER_CREATE_URL, $json_body);
            if ($result['order_id']) {
                update_post_meta($data['order_id'], AVCONNECT_KEY_ORDER_REF, $result['order_id']);
            }

            return $result;
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
            $this->onValidateUser();
            // Esquema de cada ítem del pedido
            $itemSchema = AVCONNECT_Validator('item')->isObject([
                'productRef'      => AVCONNECT_Validator('productRef')->isRequired()->isString(),
                'rateValue'       => AVCONNECT_Validator('rateValue')->isNumber(),
                'ivaValue'        => AVCONNECT_Validator('ivaValue')->isNumber(),
                'quantity'        => AVCONNECT_Validator('quantity')->isRequired()->isNumber(),
                'peso'            => AVCONNECT_Validator('peso')->isNumber(),
                'vol'             => AVCONNECT_Validator('vol')->isNumber(),
                'declarado'       => AVCONNECT_Validator('declarado')->isNumber(),
                'totalValue'      => AVCONNECT_Validator('totalValue')->isNumber(),
            ]);

            // Esquema principal del pedido
            $schema = AVCONNECT_Validator('order')->isObject([
                'orderId'                => AVCONNECT_Validator('orderId')->isRequired()->isNumber(),
                // 'bodegaName'              => AVCONNECT_Validator('bodegaName')->isNumber(),
                'idAgente'                => AVCONNECT_Validator('idAgente')->isRequired()->isNumber(),
                'numeropedidoExterno'     => AVCONNECT_Validator('numeropedidoExterno')->isString(),
                'items'                   => AVCONNECT_Validator('items')->isRequired()->isArray($itemSchema),
                'subTotalValue'           => AVCONNECT_Validator('subTotalValue')->isNumber(),
                // 'vatValue'                => AVCONNECT_Validator('vatValue')->isNumber(),
                'totalAmountValue'        => AVCONNECT_Validator('totalAmountValue')->isNumber(),
                // 'grandTotalValue'         => AVCONNECT_Validator('grandTotalValue')->isNumber(),
                // 'grandTotalVol'           => AVCONNECT_Validator('grandTotalVol')->isNumber(),
                // 'grandTotalPeso'          => AVCONNECT_Validator('grandTotalPeso')->isNumber(),
                // 'grandTotalUnit'          => AVCONNECT_Validator('grandTotalUnit')->isNumber(),
                // 'grandTotalDeclarado'     => AVCONNECT_Validator('grandTotalDeclarado')->isNumber(),
                // 'grandTotalDeclaradoValue' => AVCONNECT_Validator('grandTotalDeclaradoValue')->isNumber(),
                'paymentCliente'          => AVCONNECT_Validator('paymentCliente')->isRequired()->isNumber(),
                'recaudo'                 => AVCONNECT_Validator('recaudo')->isRequired()->isNumber(),
                'recaudoValue'            => AVCONNECT_Validator('recaudoValue')->isRequired()->isNumber(),
                'paymentAsumecosto'       => AVCONNECT_Validator('paymentAsumecosto')->isRequired()->isNumber(),
                'clientDestino'           => AVCONNECT_Validator('clientDestino')->isRequired()->isString(),
                'valorEnvio'              => AVCONNECT_Validator('valorEnvio')->isNumber(),
                'valorEnvioValue'         => AVCONNECT_Validator('valorEnvioValue')->isNumber(),
                // 'cadenaEnvio'             => AVCONNECT_Validator('cadenaEnvio')->isString(),
                // 'seloperadorEnvio'        => AVCONNECT_Validator('seloperadorEnvio')->isNumber(),
                'clientContact'           => AVCONNECT_Validator('clientContact')->isRequired()->isString(),
                'clientId'                => AVCONNECT_Validator('clientId')->isRequired()->isString(),
                'clientDir'               => AVCONNECT_Validator('clientDir')->isString(),
                'clientTel'               => AVCONNECT_Validator('clientTel')->isNumber(),
                'clientEmail'             => AVCONNECT_Validator('clientEmail')->isString(),
                // 'nroFactura'              => AVCONNECT_Validator('nroFactura')->isString(),
                'plugin'                  => AVCONNECT_Validator('plugin')->isRequired()->isString(),
                // 'noEditarEnvio'           => AVCONNECT_Validator('noEditarEnvio')->isNumber(),
                // 'revisarCE'               => AVCONNECT_Validator('revisarCE')->isNumber(),
                // 'obs'                     => AVCONNECT_Validator('obs')->isString(),
                'pagado'                  => AVCONNECT_Validator('pagado')->isBoolean(),
            ]);

            // Validar datos recibidos
            $schema->validate($data);

            // Agregar campos base de autenticación
            $json_body = array_merge([
                "tipo"    => "authave",
                "token"   => $this->user['token'],
                "empresa" => $this->user['idEnterprise'],
            ], $data);

            // Llamar API de actualización de orden
            return $this->request($this->API_URL_ORDER_UPDATE_URL, $json_body);
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
            $this->onValidateUser();
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
