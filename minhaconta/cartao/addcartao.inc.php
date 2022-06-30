<?php

	include_once __DIR__.'/../../includes/setup.inc.php';

	$resposta = array(
		'resposta'=>''
	);

	if (isset($_POST['nome']) ) {
		$nome = $_POST['nome'];
		$cpf = $_POST['cpf'];
		$numero = $_POST['numero'];
		$expiracao = $_POST['expiracao'];
		$cvc = $_POST['cvc'];

		if ( (empty($nome)) || (empty($cpf)) || (empty($numero)) || (empty($expiracao)) || (empty($cvc)) ) {
			//
		} else {
			$addcartao = new setRow();
			$addcartao = $addcartao->CadastroCartao($uid,$nome,$cpf,$numero,$expiracao,$cvc,$data);
			if ($addcartao===true) {
				$resposta = array(
					'resposta'=>'Cartão adicionado com sucesso'
				);
			} else {

			} // addcartao true

		} // show
	} else {
		return false;
	} // isset post

	header('Content-Type: application/json;');
	echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
