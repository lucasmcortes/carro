<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['submitrecup'])) {

	setcookie("recuperar", $data, time()+3600, '/');

	$email = $_POST['emailrecup'];

	if (empty($email)) {
		$respostarecuperacao = 'Insira o e-mail para recuperar sua senha!';
	} else {
		if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email, $email, PREG_UNMATCHED_AS_NULL)) {
			$email = $email[0];
			$encontro = new ConsultaDatabase($uid);
			$encontro = $encontro->EncontraAdmin($email);
			if ($encontro===0) {
				$respostarecuperacao = 'E-mail não cadastrado!';
			} else {
				$chave = sha1(uniqid(mt_rand(), true));

				$addrecuperacao = new setRow();
				$addrecuperacao = $addrecuperacao->Recuperar($email,$chave,$data);
				if ($addrecuperacao===true) {
					$_SESSION['link_recuperar'] = $link = "".$dominio."/entrar/recuperar-senha/nova-senha/?email=".base64_encode($email)."&chave=".$chave."";
					$cartinha = new Cartinha();
					$cartinha->enviarCartinha('recuperar',$email);
					$respostarecuperacao = 'enviando';
				} else {
					$respostarecuperacao = 'Tente novamente!';
				}// addrecuperacao true
			} // encontro = 0
		} else {
			$respostarecuperacao = 'Insira um endereço válido!';
		} // regex email
	} // isset email
} else {
	$respostarecuperacao = ':((';
} // isset post

echo $respostarecuperacao;
