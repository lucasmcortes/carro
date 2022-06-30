<?php

	include_once __DIR__.'/../../includes/setup.inc.php';

	$resposta = array(
		'resposta'=>''
	);

	if (isset($_POST['plano'])) {

		$plano = $_POST['plano'];

		// Define o preço da licença
		($plano=='anual') ? $precolicenca = str_replace(array(',','.','R$',' '),'',$preco_anual_vista) : $precolicenca = str_replace(array(',','.','R$',' '),'',$preco_vital_vista);

		// Define se vai ser reccorente
		($plano=='anual') ? $recorrencia =
		',
		"recurring": {
			"type": "INITIAL"
		}' : $recorrencia = '';

		// Forma de pagamento
		$formadepagamento = $_POST['forma'];

		$cartao = new ConsultaDatabase($uid);
		$cartao = $cartao->UserCartao($uid);

		$client = new \GuzzleHttp\Client();
		if ($formadepagamento=='cartao') {
			// CARTÃO
			require_once __DIR__.'/pagamento-cartao.inc.php';
		} else if ($formadepagamento=='boleto') {
			// BOLETO
			require_once __DIR__.'/pagamento-boleto.inc.php';
		} else if ($formadepagamento=='pix') {
			// PIX
			require_once __DIR__.'/pagamento-pix.inc.php';
		} // forma de pagamento

	} else {
		return false;
	} // isset post

	header('Content-Type: application/json;');
	echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
