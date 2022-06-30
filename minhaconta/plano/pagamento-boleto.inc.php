<?php

        $vencimentoBoleto = $agora;
        $vencimentoBoleto->modify('+7 days');
        $vencimentoBoleto = $vencimentoBoleto->format('Y-m-d');

        $userinfo = new ConsultaDatabase($uid);
        $userinfo = $userinfo->UserInfo($uid);
        $usernivel = $userinfo['nivel'];
        $usernome = $userinfo['nome'];
        $usercpf = $userinfo['cpf'];
        $usertelefone = $userinfo['telefone'];
        $useremail = $userinfo['email'];

        $userendereco = new ConsultaDatabase($uid);
        $userendereco = $userendereco->Enderecos($uid);
        $usercep = $userendereco[0]['cep'];
        $userrua = $userendereco[0]['rua'];
        $usernumero = $userendereco[0]['numero'];
        $userbairro = $userendereco[0]['bairro'];
        $usercidade = $userendereco[0]['cidade'];
        $userestado = $userendereco[0]['estado'];
        $usercomplemento = $userendereco[0]['complemento'];

        // SANDBOX
        // 'Authorization' => '5C690FDEB9A243DDBB8E2E02781DC0D6',
        //
        // PRODUCTION
        // 'Authorization' => '3fab392e-fcf1-44d4-8ca3-057eed217f4d9266ebf94b8088387627ea90c32935c90d7a-6cb7-437a-98ad-f3e98d3bbb27',
        //
        //
        // "country": "Brasil",
        // "regionCode": "SP",
        // "city": "Sao Paulo",
        // "postal_code": "01452002",
        // "street": "Avenida Brigadeiro Faria Lima",
        // "number": "1384",
        // "locality": "Pinheiros"
        //
        //
        //
        // "notification_urls": [
        //         "'.$dominio.'/webhook?ref='.$guid.'"
        // ],

        $guidPagamentos = new ConsultaDatabase($uid);
        $guidPagamentos = $guidPagamentos->GuidPagamentos();

        $guid = mb_strtoupper(Guid());
        do {
                $guid = mb_strtoupper(Guid());
        } while ($guid==$guidPagamentos['reference_id']);

        try {
                $response = $client->request(
                        'POST', 'https://sandbox.api.pagseguro.com/charges', [
                                'body' => '{
                                        "reference_id":"'.$guid.'",
                                        "description":"Aquisição de licença para uso da aplicação Ophanim.com.br",
                                        "amount":{
                                                "value":'.$precolicenca.',
                                                "currency":"BRL"
                                        },
                                        "payment_method":{
                                               "type":"BOLETO",
                                                "boleto": {
                                                         "due_date": "'.$vencimentoBoleto.'",
                                                         "instruction_lines": {
                                                                 "line_1": "Ophanim.com.br",
                                                                 "line_2": "Aquisição de licença"
                                                         },
                                                         "holder": {
                                                                 "name": "'.$usernome.'",
                                                                 "tax_id": "'.str_replace(array('-','.',' '),'',$usercpf).'",
                                                                 "email": "'.$useremail.'",
                                                                 "address": {
                                                                         "country":"Brasil",
                                                                         "region_code": "'.$userestado.'",
                                                                         "region": "'.$userestado.'",
                                                                         "city": "'.$usercidade.'",
                                                                         "postal_code": '.str_replace(array('-','.',' '),'',$usercep).',
                                                                         "street": "'.$userrua.'",
                                                                         "number": '.$usernumero.',
                                                                         "locality": "'.$usercidade.'"
                                                                 }
                                                         }
                                                 }
                                        },
                                }',
                                'headers' => [
                                        'Authorization' => '5C690FDEB9A243DDBB8E2E02781DC0D6',
                                        'Accept' => 'application/json',
                                        'Content-type' => 'application/json'
                                ],
                        ]
                );
                echo "'.$dominio.'/webhook?ref='.$guid.'";
                $resposta = array(
                        'resposta'=>'',
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

                if ($resposta['pagamento']['status']=='WAITING') {
                        $addpagamento = new setRow();
                        $addpagamento = $addpagamento->PagamentoBoletoPagSeguro(
                                $uid,
                                $resposta['pagamento']['id']??0,
                                'Ophanim_'.$agora->format('Y').'_'.ucfirst($plano),
                                $resposta['pagamento']['reference_id']??0,
                                $resposta['pagamento']['status']??0,
                                $resposta['pagamento']['created_at']??0,
                                $resposta['pagamento']['paid_at']??0,
                                $resposta['pagamento']['description']??0,
                                $resposta['pagamento']['amount']['total']??0,
                                $resposta['pagamento']['amount']['paid']??0,
                                $resposta['pagamento']['amount']['refunded']??0,
                                $resposta['pagamento']['amount']['currency']??0,
                                $resposta['pagamento']['payment_response']['code']??0,
                                $resposta['pagamento']['payment_response']['message']??0,
                                $resposta['pagamento']['payment_response']['reference']??0,
                                $resposta['pagamento']['payment_method']['type']??0,
                                $resposta['pagamento']['payment_method']['boleto']['id']??0,
                                $resposta['pagamento']['payment_method']['boleto']['barcode']??0,
                                $resposta['pagamento']['payment_method']['boleto']['formatted_barcode']??0,
                                $resposta['pagamento']['payment_method']['boleto']['due_date']??0,
                                $resposta['pagamento']['payment_method']['boleto']['instruction_lines']['line_1']??0,
                                $resposta['pagamento']['payment_method']['boleto']['instruction_lines']['line_2']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['name']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['tax_id']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['email']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['address']['country']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['address']['region_code']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['address']['city']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['address']['postal_code']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['address']['street']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['address']['number']??0,
                                $resposta['pagamento']['payment_method']['boleto']['holder']['address']['locality']??0,
                                $resposta['pagamento']['links'][0]['href']??0,
                                $data??0
                        );
                        if ($addpagamento===true) {
                                $pagamentorealizado = new ConsultaDatabase($uid);
                                $pagamentorealizado = $pagamentorealizado->PagamentoBoletoPagSeguro($data);

                                $_SESSION['boleto']['nome'] = NomeCliente($_SESSION['l_nome']);
                                $_SESSION['boleto']['email'] = $userinfo['email'];
                                $_SESSION['boleto']['formatted_barcode'] = $resposta['pagamento']['payment_method']['boleto']['formatted_barcode'];
                                $_SESSION['boleto']['link'] = $resposta['pagamento']['links'][0]['href'];
                                $_SESSION['boleto']['licenca'] = ($plano=='anual') ? 'anual' : 'vitalícia';

                                $cartinha = new Cartinha();
                                $cartinha->enviarCartinha('boleto',$_SESSION['boleto']['email']);

                                $resposta['resposta'] = 'Boleto gerado com sucesso';

                                //// LICENÇA NÃO BOTA AINDA PORQUE NÃO PAGOU O BOLETO, SÓ GEROU AQUI
                                // $addlicenca = new setRow();
                                // $addlicenca = $addlicenca->Licenca($uid,$pagamentorealizado['id'],'OK',ucfirst($plano),$data);

                                // if ($addlicenca===true) {
                                //         $resposta['resposta'] = 'Compra realizada com sucesso';
                                // } else {
                                //         $resposta['resposta'] = 'Pagamento realizado com sucesso. Entre em contato com o suporte e informe o código '.$pagamentorealizado['id'].' para habilitar sua licença.';
                                // } // addlicenca true
                        } else {
                                $resposta['resposta'] = 'Boleto não criado. Tente novamente';
                        } // addpagamento true
                } else {
                        $resposta['resposta'] = 'Boleto não criado. Tente novamente';
                } // pagou, registra licenca

        } catch(Guzzle\Http\Exception\BadResponseException $e) {
                // you can catch here 400 response errors and 500 response errors
                $error['error'] = $e->getMessage();
                $error['request'] = $e->getRequest();
                if ($e->hasResponse()){
                        if ($e->getResponse()->getStatusCode() == '400'){
                                $error['response'] = $e->getResponse();
                        }
                }

                $resposta['resposta'] = 'Boleto não criado. Tente novamente.';
                //$resposta['resposta'] = var_dump($e);
                //$resposta['resposta'] = var_dump($e->getResponse()->getBody()->getContents());
        } catch(Exception $e) {
                // Guzzle fatal error
                $resposta['resposta'] = 'Boleto não criado. Tente novamente.';
                //$resposta['resposta'] = var_dump($e);
                //$resposta['resposta'] = $e->getResponse()->getBody()->getContents();
        } // try guzzle

?>
