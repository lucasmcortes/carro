<?php

	include_once __DIR__.'/../../includes/setup.inc.php';

	$resposta = array(
		'resposta'=>''
	);

	$inicioPlano = $agora->format('Y-m-d');
	$agora->modify('+1 year');
	$fimPlano = $agora->format('Y-m-d');
	$agora->modify('-1 year');

	return;

	if (isset($_POST['plano']) ) {

		$client = new \GuzzleHttp\Client();

		$response = $client->request(
			'POST', 'https://ws.sandbox.pagseguro.uol.com.br/pre-approvals/request/?email=lmattoscortes@gmail.com&token=5C690FDEB9A243DDBB8E2E02781DC0D6', [
				'body' => '{
					"reference":"Ophanim-Anual-2022",
					"preApproval":{
						"name":"string",
						"charge":"AUTO",
						"period":"string",
						"amountPerPayment":0,
						"membershipFee":0,
						"trialPeriodDuration":0,
						"expiration":{
							"value":0,
							"unit":"string"
						},
						"details":"string",
						"maxAmountPerPeriod":0,
						"maxAmountPerPayment":0,
						"maxTotalAmount":0,
						"maxPaymentsPerPeriod":0,
						"initialDate":"'.$inicioPlano.'",
						"finalDate":"'.$fimPlano.'",
						"dayOfYear":"string",
						"dayOfMonth":0,''
						"dayOfWeek":"string",
						"cancelURL":"string"
					},
					"reviewURL":"string",
					"maxUses":0,
					"receiver":{
						"email":"lmattoscortes@gmail.com"
					}
				}',
				'headers' => [
					'Authorization' => '5C690FDEB9A243DDBB8E2E02781DC0D6',
					'Accept' => 'application/vnd.pagseguro.com.br.v3+xml;charset=ISO-8859-1',
					'Content-type' => 'application/xml;charset=ISO-8859-1',
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
