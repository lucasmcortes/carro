<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_POST['submitentrar'])) {

	$rmt_host = $_SERVER['REMOTE_HOST'] ?? 0;
	$rmt_addr = $_SERVER['REMOTE_ADDR'] ?? 0;
	$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 0;

	$email = $_POST['entraremail'];
	$pwd = $_POST['entrarpwd'];
	$request = $_POST['entrarrequest'];

	if ( empty($email) || empty($pwd) ) {
		$entrando = 'vazio';
	} else {
		$encontrado = new ConsultaDatabase($uid);
		$encontrado = $encontrado->EncontraAdmin($email);
		if ($encontrado===0) {
			$entrando = 'cadastrar';
		} else {
			$login = new ConsultaDatabase($uid);
			$login = $login->AuthAdmin($email,$pwd);
			if ($login===0) {
				$entrando = 'incorreta';
			} else {
				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}
				session_destroy();
				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}
				$sid = session_id();
				$chave_unica_de_login = sha1(uniqid(mt_rand(), true));

				$_SESSION['l_id'] = $login['uid'];
				$_SESSION['l_chave'] = $chave_unica_de_login;
				$_SESSION['l_nome'] = $login['nome'];
				$_SESSION['l_cpf'] = $login['cpf'];
				$_SESSION['l_telefone'] = $login['telefone'];
				$_SESSION['l_email'] = $email;
				$_SESSION['l_cadastro'] = $login['data_cadastro'];

				$formatcadastro = new DateTime($_SESSION['l_cadastro']);
				$_SESSION['l_cadastroformat'] = $formatcadastro->format('d/m/y');
				// e deleta o cookie
				setcookie('sequencia',"",time()-3600);

				$log = new setRow();
				$log = $log->Login($login['uid'],$sid,$chave_unica_de_login,$rmt_addr,$rmt_host,$user_agent,$data);

				$entrando = 'entrou';
			} // login = 0
		} // encontrado
	} // vazio
} else {
	$entrando = ':((';
} // $_post


if ($entrando == 'vazio') {
	$entrando = 'Preencha os campos para continuar:';
} else if ($entrando == 'cadastrar') {
	$entrando = 'Cadastrar';
}  else if ($entrando == 'incorreta') {
	$entrando = 'Tente novamente!';
}  else if ($entrando == 'confirmar') {
	$entrando = 'Confirme seu cadastro pelo email';
} else if ($entrando == '') {
	$entrando = '';
} else if ($entrando == 'entrou') {
	$entrando = 'entrou';
} else {
	$entrando = ':~';
}

echo $entrando;
