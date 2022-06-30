<?php

	include_once __DIR__.'/setup.inc.php';

	$resposta = array(
		'titulo'=>'',
		'descricao'=>''
	);

	if (isset($_POST['emaildrive']) ) {
		$nome = $_POST['nomedrive'];
		$email = $_POST['emaildrive'];
		if (empty($email)) {
			$email = 0;
		} else {
			if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email, $email, PREG_UNMATCHED_AS_NULL)) {
				$email = $email[0];
			} else {
				$email = 0;
			} // regex email
		} // email

		if ( (empty($email)) || (empty($nome)) ) {
			//
		} else {
			$cartinha = new Cartinha();
			$cartinha->enviarCartinha('drive',$email);

			$resposta = array(
				'titulo'=>'Prepare-se para sua experiência',
				'descricao'=>'Siga as instruções no e-mail que te enviamos e comece a usar'
			);

		} // show
	} else {
		return false;
	} // isset post

	header('Content-Type: application/json;');
	echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
