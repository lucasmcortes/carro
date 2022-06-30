<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['submitnova'])) {
	$respostanovasenha = '';
	$email = $_SESSION['email'];
	setcookie("email", $email, time() + (86400 * 30), "/"); // 86400 = 1 day

	$nova = $_POST['nsenha'];
	$confirme = $_POST['nconfirme'];

	if (empty($nova) || empty($confirme)) {
		$respostanovasenha = 'Preencha todos os campos!';
	} else {
		if ($nova <> $confirme) {
			$respostanovasenha = 'Confira se as duas senhas são a mesma.';
		} else {
			$senhanova = password_hash($nova, PASSWORD_DEFAULT);
			$updatesenha = new UpdateRow();
			$updatesenha = $updatesenha->UpdateSenha($senhanova,$email);
			if ($updatesenha===true) {
				$cartinha = new Cartinha();
				$cartinha->enviarCartinha('senha-recuperada',$email);
				$respostanovasenha = 'criando';
			} // updatesenha true
		} // senhas iguais
	} // campos preenchidos
} else {
	$respostanovasenha = ':((';
} // isset post

echo $respostanovasenha;
