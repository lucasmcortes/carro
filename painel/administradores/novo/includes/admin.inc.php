<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitadmin'])) {
	$cadastrando = '';

	$cpf = $_POST['cpf'];
	if (empty($cpf)) {
		RespostaRetorno('cpf');
		return;
	} else {
		if (preg_match('/^(\d{3}\.\d{3}\.\d{3}\-\d{2})?+$/', $cpf, $cpf, PREG_UNMATCHED_AS_NULL)) {
			$cpf = $cpf[0];
		} else {
			RespostaRetorno('cpf');
			return;
		} // regex cpf
	} // cpf

	$telefone = $_POST['telefone'];
	if (empty($telefone)) {
		RespostaRetorno('telefone');
		return;
	} else {
		if (preg_match('/(\(\d{2}\)[ ]{1}\d{5}\-\d{4})/', $telefone, $telefone, PREG_UNMATCHED_AS_NULL)) {
			$telefone = $telefone[0];
		} else {
			RespostaRetorno('telefone');
			return;
		} // regex telefone
	} // telefone

	$email = $_POST['email'];
	if (empty($email)) {
		RespostaRetorno('email');
		return;
	} else {
		if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email, $email, PREG_UNMATCHED_AS_NULL)) {
			$_SESSION['cadastro_email'] = $email = $email[0];
		} else {
			RespostaRetorno('email');
			return;
		} // regex email
	} // email

	$_SESSION['cadastro_nome'] = $nome = mb_strtoupper($_POST['nome']);
	if (empty($nome)) {
		RespostaRetorno('nomeadmin');
		return;
	} // nome

	$nivel = $_POST['nivel'];
	if (empty($nivel)) {
		RespostaRetorno('niveladmin');
		return;
	} // nivel

	$pwd = $_POST['pwd'];
	if (empty($pwd)) {
		RespostaRetorno('senhaadmin');
		return;
	} // senha

	$remote_addr = $_SERVER['REMOTE_ADDR'];

	if (empty($nome) || empty($nivel) || empty($cpf) || empty($telefone) || empty($email) || empty($pwd)) {
		RespostaRetorno('vazio');
		return;

	} else {
		$valida_documento = new ValidaCPFCNPJ($cpf);
		if ($valida_documento->valida() ) {
			$encontraadmin = new ConsultaDatabase($uid);
			$encontraadmin = $encontraadmin->EncontraAdmin($email);
			if ($encontraadmin['uid']==0) {
				$senha = password_hash($pwd, PASSWORD_DEFAULT);
				$addadmin = new setRow();
				$addadmin = $addadmin->Admin($nome,$cpf,$telefone,$email,$senha,$data);
		 		if ($addadmin===true) {
		 			$novoadmin = new ConsultaDatabase($uid);
		 			$novoadmin = $novoadmin->AdminUid($email);
		 			$addnivel = new setRow();
					$addnivel = $addnivel->AdminNivel($novoadmin,$nivel,$data);
					if ($addnivel===true) {
						$cartinha = new Cartinha();
						$cartinha->enviarCartinha('cadastro',$email);

						RespostaRetorno('sucessoadmin');
						return;
					} // addnivel true
				} else {
					RespostaRetorno('novamente');
					return;
				} // addadmin true
		 	} else {
				RespostaRetorno('adminexistente');
				return;
		 	} // encontraadmin = 0
		} else {
			RespostaRetorno('cpfinvalido');
			return;
		} // documento valido
	} // campos preenchidos
} else {
	$cadastrando = ':((';
} // isset post submit

echo $cadastrando;

?>
