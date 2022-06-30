<?php

        // SANDBOX
        // 'Authorization' => '5C690FDEB9A243DDBB8E2E02781DC0D6',
        //
        // PRODUCTION
        // 'Authorization' => '3fab392e-fcf1-44d4-8ca3-057eed217f4d9266ebf94b8088387627ea90c32935c90d7a-6cb7-437a-98ad-f3e98d3bbb27',
        //

        include_once __DIR__.'/../../includes/setup.inc.php';

        $boletoRecente = new ConsultaDatabase($uid);
        $boletoRecente = $boletoRecente->BoletoRecente($uid);
        if ($boletoRecente['status']!='CANCELADO') {
                //$boletoChargeID = str_replace('-','',$boletoRecente['id']);
                $boletoGUID = $boletoRecente['reference_id'];
                
                $client = new \GuzzleHttp\Client();
                try {
                        $response = $client->request(
                                'GET', 'https://sandbox.api.pagseguro.com/charges?reference_id='.$boletoGUID.'', [
                                        'headers' => [
                                                'Accept' => 'application/json',
                                                'Authorization'=>'5C690FDEB9A243DDBB8E2E02781DC0D6'
                                        ],
                                ]
                        );

                        $resposta = array(
                                'resposta'=>json_decode($response->getBody(),true)['status']??0,
                                'preco'=>($plano=='anual') ? $preco_anual_vista : $preco_vital_vista,
                                'pagamento'=>array(
                                        'id'=>json_decode($response->getBody(),true)['id']??0,
                                        'reference_id'=>json_decode($response->getBody(),true)['reference_id']??0,
                                        'status'=>json_decode($response->getBody(),true)['status']??0,
                                        'created_at'=>json_decode($response->getBody(),true)['created_at']??0,
                                        'paid_at'=>json_decode($response->getBody(),true)['paid_at']??0,
                                        'description'=>json_decode($response->getBody(),true)['description']??0,
                                        'amount'=>array(
                                                'total'=>json_decode($response->getBody(),true)['amount']['total']??0,
                                                'paid'=>json_decode($response->getBody(),true)['amount']['paid']??0,
                                                'refunded'=>json_decode($response->getBody(),true)['amount']['refunded']??0,
                                                'currency'=>json_decode($response->getBody(),true)['amount']['currency']??0
                                        ),
                                        'payment_response'=>array(
                                                'code'=>json_decode($response->getBody(),true)['payment_response']['code']??0,
                                                'message'=>json_decode($response->getBody(),true)['payment_response']['message']??0,
                                                'reference'=>json_decode($response->getBody(),true)['payment_response']['reference']??0
                                        ),
                                        'payment_method'=>array(
                                                'type'=>json_decode($response->getBody(),true)['payment_method']['type']??0,
                                                'boleto'=>array(
                                                        'id'=>json_decode($response->getBody(),true)['payment_method']['boleto']['id']??0,
                                                        'barcode'=>json_decode($response->getBody(),true)['payment_method']['boleto']['barcode']??0,
                                                        'formatted_barcode'=>json_decode($response->getBody(),true)['payment_method']['boleto']['formatted_barcode']??0,
                                                        'due_date'=>json_decode($response->getBody(),true)['payment_method']['boleto']['due_date']??0,
                                                        'instruction_lines'=>array(
                                                                'line_1'=>json_decode($response->getBody(),true)['payment_method']['boleto']['instruction_lines']['line_1']??0,
                                                                'line_2'=>json_decode($response->getBody(),true)['payment_method']['boleto']['instruction_lines']['line_2']??0
                                                        ),
                                                        'holder'=>array(
                                                                'name'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['name']??0,
                                                                'tax_id'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['tax_id']??0,
                                                                'email'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['email']??0,
                                                                'address'=>array(
                                                                        'country'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['address']['country']??0,
                                                                        'region_code'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['address']['region_code']??0,
                                                                        'city'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['address']['city']??0,
                                                                        'postal_code'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['address']['postal_code']??0,
                                                                        'street'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['address']['street']??0,
                                                                        'number'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['address']['number']??0,
                                                                        'locality'=>json_decode($response->getBody(),true)['payment_method']['boleto']['holder']['address']['locality']??0
                                                                )
                                                        )
                                                )
                                        ),
                                        'notification_urls'=>json_decode($response->getBody(),true)['notification_urls']??0,
                                        'links'=>json_decode($response->getBody(),true)['links']??0
                                )
                        );

                } catch(Guzzle\Http\Exception\BadResponseException $e) {
                        // you can catch here 400 response errors and 500 response errors
                        $error['error'] = $e->getMessage();
                        $error['request'] = $e->getRequest();
                        if ($e->hasResponse()){
                                if ($e->getResponse()->getStatusCode() == '400'){
                                        $error['response'] = $e->getResponse();
                                }
                        }

                        //$resposta['resposta'] = 'Boleto não criado. Tente novamente.';
                        //$resposta['resposta'] = var_dump($e);
                        $resposta['resposta'] = var_dump($e->getResponse()->getBody()->getContents());
                } catch(Exception $e) {
                        // Guzzle fatal error
                        //$resposta['resposta'] = 'Boleto não criado. Tente novamente.';
                        $resposta['resposta'] = var_dump($e);
                        //$resposta['resposta'] = var_dump($e->getResponse()->getBody()->getContents());
                } // try guzzle
        } else {
                return false;
        } // boleto habil

        header('Content-Type: application/json;');
	echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
