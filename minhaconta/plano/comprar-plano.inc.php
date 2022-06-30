<?php

	include_once __DIR__.'/../../includes/setup.inc.php';

	$resposta = array(
		'resposta'=>''
	);

	if (isset($_POST['plano']) ) {

		$client = new \GuzzleHttp\Client();

		$response = $client->request(
			'POST', 'https://ws.sandbox.pagseguro.uol.com.br/pre-approvals?email=lmattoscortes@gmail.com&token=5C690FDEB9A243DDBB8E2E02781DC0D6', [
				'body' => '{
					"plan":"659AB301DFDFE684448C1FB8B86F28F8",
					"reference":"ID-CND",
					"sender":{
						"name":"Comprador testes",
						"email":"adesao@sandbox.pagseguro.com.br",
						"ip":"192.168.0.1",
						"hash":"hash",
						"phone":{
							"areaCode":"11",
							"number":"999999999"
						},
						"address":{
							"street":"Av. Brigadeira Faria Lima",
							"number":"1384",
							"complement":"3 andar",
							"district":"Jd. Paulistano",
							"city":"São Paulo",
							"state":"SP",
							"country":"BRA",
							"postalCode":"01452002"
						},
						"documents":[{
								"type":"CPF",
								"value":"00000000191"
							}]
						},
						"paymentMethod":{
							"type":"CREDITCARD",
							"creditCard":{
								"token":"59c9d69d2fcc439eb30c5d2da83fe2c3",
								"holder":{
									"name":"Nome Comprador",
									"birthDate":"11/01/1984",
									"documents":[{
										"type":"CPF",
										"value":"00000000191"
									}],
									"billingAddress":{
										"street":"Av. Brigadeiro Faria Lima",
										"number":"1384",
										"complement":"3 andar",
										"district":"Jd. Paulistano",
										"city":"São Paulo",
										"state":"SP",
										"country":"BRA",
										"postalCode":"01452002"
									},
									"phone":{
										"areaCode":"11",
										"number":"988881234"
									}
								}
							}
						}
					}',
				'headers' => [
					'Authorization' => '5C690FDEB9A243DDBB8E2E02781DC0D6',
					'Accept' => 'application/json',
					'Content-type' => 'application/json',
				],
			]
		);

		$resposta = array(
			'resposta'=>'Compra realizada com sucesso',
			'pagamento'=>array(
				'id'=>json_decode($response->getBody(),true)['id'],
				'reference_id'=>json_decode($response->getBody(),true)['reference_id'],
				'status'=>json_decode($response->getBody(),true)['status'],
				'created_at'=>json_decode($response->getBody(),true)['created_at'],
				//'paid_at'=>json_decode($response->getBody(),true)['paid_at'],
				'description'=>json_decode($response->getBody(),true)['description'],
				'amount'=>array(
					'value'=>json_decode($response->getBody(),true)['amount']['value'],
					'currency'=>json_decode($response->getBody(),true)['amount']['currency'],
					'summary'=>array(
						'total'=>json_decode($response->getBody(),true)['amount']['summary']['total'],
						'paid'=>json_decode($response->getBody(),true)['amount']['summary']['paid'],
						'refunded'=>json_decode($response->getBody(),true)['amount']['summary']['refunded']
					)
				),
				'payment_response'=>array(
					'code'=>json_decode($response->getBody(),true)['payment_response']['code'],
					'message'=>json_decode($response->getBody(),true)['payment_response']['message'],
					'reference'=>json_decode($response->getBody(),true)['payment_response']['reference']
				),
				'payment_method'=>array(
					'type'=>json_decode($response->getBody(),true)['payment_method']['type'],
					'installments'=>json_decode($response->getBody(),true)['payment_method']['installments'],
					'capture'=>json_decode($response->getBody(),true)['payment_method']['capture'],
					'card'=>array(
						'brand'=>json_decode($response->getBody(),true)['payment_method']['card']['brand'],
						'first_digits'=>json_decode($response->getBody(),true)['payment_method']['card']['first_digits'],
						'last_digits'=>json_decode($response->getBody(),true)['payment_method']['card']['last_digits'],
						'exp_month'=>json_decode($response->getBody(),true)['payment_method']['card']['exp_month'],
						'exp_year'=>json_decode($response->getBody(),true)['payment_method']['card']['exp_year'],
						'holder'=>array(
							'name'=>json_decode($response->getBody(),true)['payment_method']['card']['holder']['name']
						)
					),
					'soft_descriptor'=>json_decode($response->getBody(),true)['payment_method']['soft_descriptor'],
				),
				'recurring'=>array(
					'type'=>json_decode($response->getBody(),true)['recurring']['type']
				),
				'notification_urls'=>json_decode($response->getBody(),true)['notification_urls'],
				'links'=>json_decode($response->getBody(),true)['links']
			)
		);

	} else {
		return false;
	} // isset post

	header('Content-Type: application/json;');
	echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
