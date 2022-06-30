<?php

        $cartao = new ConsultaDatabase($uid);
        $cartao = $cartao->UserCartao($uid);
        $numerocartao = str_replace(' ','',$cartao['numero']);
        $dataexp = explode('/',$cartao['dataexp']);
        $expmonth = $dataexp[0];
        $expyear = $dataexp[1];
        $cvc = $cartao['cvc'];

        // "number":"'.$numerocartao.'",
        // "exp_month":"'.$expmonth.'",
        // "exp_year":"20'.$expyear.'",
        // "security_code":"'.$cvc.'"
        //
        // "number":"4111111111111111",
        // "exp_month":"12",
        // "exp_year":"2030",
        // "security_code":"123"
        //
        // SANDBOX
        // 'Authorization' => '5C690FDEB9A243DDBB8E2E02781DC0D6',
        //
        // PRODUCTION
        // 'Authorization' => '3fab392e-fcf1-44d4-8ca3-057eed217f4d9266ebf94b8088387627ea90c32935c90d7a-6cb7-437a-98ad-f3e98d3bbb27',

        try {
                $response = $client->request(
                        'POST', 'https://api.pagseguro.com/charges', [
                                'body' => '{
                                        "reference_id":"",
                                        "description":"Aquisição de licença para uso da aplicação Ophanim.com.br",
                                        "amount":{
                                                "value":'.$precolicenca.',
                                                "currency":"BRL"
                                        },
                                        "payment_method":{
                                                "card":{
                                                        "holder":{
                                                                "name":"'.$cartao['nome'].'"
                                                        },
                                                        "number":"'.$numerocartao.'",
                                                        "exp_month":"'.$expmonth.'",
                                                        "exp_year":"20'.$expyear.'",
                                                        "security_code":"'.$cvc.'"
                                                },
                                                "soft_descriptor": "Ophanim",
                                                "type":"CREDIT_CARD",
                                                "installments":1,
                                                "capture":true
                                        }
                                        '.$recorrencia.'
                                }',
                                'headers' => [
                                        'Authorization' => '3fab392e-fcf1-44d4-8ca3-057eed217f4d9266ebf94b8088387627ea90c32935c90d7a-6cb7-437a-98ad-f3e98d3bbb27',
                                        'Accept' => 'application/json',
                                        'Content-type' => 'application/json',
                                        'x-api-version' => '4.0'
                                ],
                        ]
                );

                $resposta = array(
                        'resposta'=>'',
                        'pagamento'=>array(
                                'id'=>json_decode($response->getBody(),true)['id']??0,
                                'reference_id'=>json_decode($response->getBody(),true)['reference_id']??0,
                                'status'=>json_decode($response->getBody(),true)['status']??0,
                                'created_at'=>json_decode($response->getBody(),true)['created_at']??0,
                                'paid_at'=>json_decode($response->getBody(),true)['paid_at']??0,
                                'description'=>json_decode($response->getBody(),true)['description']??0,
                                'amount'=>array(
                                        'value'=>json_decode($response->getBody(),true)['amount']['value']??0,
                                        'currency'=>json_decode($response->getBody(),true)['amount']['currency']??0,
                                        'summary'=>array(
                                                'total'=>json_decode($response->getBody(),true)['amount']['summary']['total']??0,
                                                'paid'=>json_decode($response->getBody(),true)['amount']['summary']['paid']??0,
                                                'refunded'=>json_decode($response->getBody(),true)['amount']['summary']['refunded']??0
                                        )
                                ),
                                'payment_response'=>array(
                                        'code'=>json_decode($response->getBody(),true)['payment_response']['code']??0,
                                        'message'=>json_decode($response->getBody(),true)['payment_response']['message']??0,
                                        'reference'=>json_decode($response->getBody(),true)['payment_response']['reference']??0
                                ),
                                'payment_method'=>array(
                                        'type'=>json_decode($response->getBody(),true)['payment_method']['type']??0,
                                        'installments'=>json_decode($response->getBody(),true)['payment_method']['installments']??0,
                                        'capture'=>json_decode($response->getBody(),true)['payment_method']['capture']??0,
                                        'card'=>array(
                                                'brand'=>json_decode($response->getBody(),true)['payment_method']['card']['brand']??0,
                                                'first_digits'=>json_decode($response->getBody(),true)['payment_method']['card']['first_digits']??0,
                                                'last_digits'=>json_decode($response->getBody(),true)['payment_method']['card']['last_digits']??0,
                                                'exp_month'=>json_decode($response->getBody(),true)['payment_method']['card']['exp_month']??0,
                                                'exp_year'=>json_decode($response->getBody(),true)['payment_method']['card']['exp_year']??0,
                                                'holder'=>array(
                                                        'name'=>json_decode($response->getBody(),true)['payment_method']['card']['holder']['name']??0
                                                )
                                        ),
                                        'soft_descriptor'=>json_decode($response->getBody(),true)['payment_method']['soft_descriptor']??0,
                                ),
                                'recurring'=>array(
                                        'type'=>json_decode($response->getBody(),true)['recurring']['type']??0
                                ),
                                'notification_urls'=>json_decode($response->getBody(),true)['notification_urls']??0,
                                'links'=>json_decode($response->getBody(),true)['links']??0
                        )
                );

                if ($resposta['pagamento']['status']=='PAID') {
                        $dateTimePagamento = new DateTime($resposta['pagamento']['paid_at']);
                        $dataPagamento = $dateTimePagamento->format('Y-m-d H:i:s.u');

                        $addpagamento = new setRow();
                        $addpagamento = $addpagamento->PagamentoPagSeguro(
                                $resposta['pagamento']['id']??0,
                                $uid,
                                'Ophanim_'.$agora->format('Y').'_'.ucfirst($plano),
                                $resposta['pagamento']['reference_id']??0,
                                $resposta['pagamento']['status']??0,
                                $resposta['pagamento']['created_at']??0,
                                $resposta['pagamento']['paid_at']??0,
                                $resposta['pagamento']['description']??0,
                                $resposta['pagamento']['amount']['value']??0,
                                $resposta['pagamento']['amount']['currency']??0,
                                $resposta['pagamento']['amount']['summary']['total']??0,
                                $resposta['pagamento']['amount']['summary']['paid']??0,
                                $resposta['pagamento']['amount']['summary']['refunded']??0,
                                $resposta['pagamento']['payment_response']['code']??0,
                                $resposta['pagamento']['payment_response']['message']??0,
                                $resposta['pagamento']['payment_response']['reference']??0,
                                $resposta['pagamento']['payment_method']['type']??0,
                                $resposta['pagamento']['payment_method']['installments']??0,
                                $resposta['pagamento']['payment_method']['capture']??0,
                                $resposta['pagamento']['payment_method']['card']['brand']??0,
                                $resposta['pagamento']['payment_method']['card']['first_digits']??0,
                                $resposta['pagamento']['payment_method']['card']['last_digits']??0,
                                $resposta['pagamento']['payment_method']['card']['exp_month']??0,
                                $resposta['pagamento']['payment_method']['card']['exp_year']??0,
                                $resposta['pagamento']['payment_method']['card']['holder']['name']??0,
                                $resposta['pagamento']['payment_method']['soft_descriptor']??0,
                                $resposta['pagamento']['recurring']['type']??0,
                                $dataPagamento??0
                        );
                        if ($addpagamento===true) {
                                $pagamentorealizado = new ConsultaDatabase($uid);
                                $pagamentorealizado = $pagamentorealizado->PagamentoPagSeguro($dataPagamento);

                                $addlicenca = new setRow();
                                $addlicenca = $addlicenca->Licenca($uid,$pagamentorealizado['id'],'OK',ucfirst($plano),$dataPagamento);

                                if ($addlicenca===true) {
                                        $resposta['resposta'] = 'Compra realizada com sucesso';
                                } else {
                                        $resposta['resposta'] = 'Pagamento realizado com sucesso. Entre em contato com o suporte e informe o código PAGID_'.$pagamentorealizado['id'].' para habilitar sua licença.';
                                } // addlicenca true
                        } else {
                                $resposta['resposta'] = 'Pagamento não realizado. Tente novamente';
                        } // addpagamento true
                } else {
                        $resposta['resposta'] = 'Pagamento não realizado. Tente novamente';
                } // pagou, registra licenca

        } catch(\GuzzleHttp\Exception\RequestException $e) {
                // you can catch here 400 response errors and 500 response errors
                $error['error'] = $e->getMessage();
                $error['request'] = $e->getRequest();
                if ($e->hasResponse()){
                        if ($e->getResponse()->getStatusCode() == '400'){
                                $error['response'] = $e->getResponse();
                        }
                }

                //$resposta['resposta'] = 'Compra não realizada. Tente novamente.';
                $resposta['resposta'] = $e->getMessage();
        } catch(Exception $e) {
                // Guzzle fatal error
                $resposta['resposta'] = 'Compra não realizada. Tente novamente.';
                //$resposta['resposta'] = var_dump($e);
        } // try guzzle

?>
