<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_GET['c'])) {

	$email = base64_decode($_GET['c']);

	$adminconfirmado = new ConsultaDatabase($uid);
	$adminconfirmado = $adminconfirmado->CadastroUid($email);
	if ($adminconfirmado===0) {
		redirectToLogin('entrar/?u=inexistente');
	} else {
		$encontraconfirmacao = new ConsultaDatabase($uid);
		$encontraconfirmacao = $encontraconfirmacao->Confirmado($adminconfirmado);
		if ($encontraconfirmacao===0) {
			$confirma = new setRow();
			$confirma = $confirma->CadastroConfirmado($adminconfirmado,$data);
			if ($confirma===true) {
				redirectToLogin('entrar/?u=confirmado');
			} // confirma true
		} else {
			redirectToLogin('entrar/?u=confirmado');
		} // encontraconfirmacao = 0
	} // adminconfirmado = 0
} else { // sem get
        redirectToLogin('entrar/?u=cancelado');
}
