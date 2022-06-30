<?php

	include_once __DIR__.'/../../includes/setup.inc.php';

	if (isset($_POST['submitcontato']) ) {

		$_SESSION['nome_contato'] = $nome = $_POST['connome']??0;
		$_SESSION['email_contato'] = $email = $_POST['conemail']??0;
		$_SESSION['telefone_contato'] = $telefone = $_POST['contelefone']??0;
		$_SESSION['mensagem_contato'] = $mensagem = $_POST['conmensagem']??0;

		if (empty($nome) || empty($email) || empty($telefone) || empty($mensagem)) {
			RespostaRetorno('vazio');
			return;
		} else {
			$cartinha = new Cartinha();
			$cartinha->enviarCartinha('contato','lmattoscortes@gmail.com');

			$resposta = 'Formulário enviado com sucesso';

		}
	} else {
		$resposta = ':((';
	} // isset post

	unset($_SESSION['nome_contato']);
	unset($_SESSION['email_contato']);
	unset($_SESSION['telefone_contato']);
	unset($_SESSION['mensagem_contato']);

	echo $resposta;

?>
