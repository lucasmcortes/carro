<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

	$cep = $_POST['cep'];
	if (preg_match('/(\d{2}\.\d{3}\-\d{3})?/', $cep, $cep, PREG_UNMATCHED_AS_NULL)) {
		$cep = $cep[0];
	} else {
		$cep = '';
	} // regex cep

	$rua = $_POST['rua'];
	$numero = $_POST['numero'];
	$bairro = $_POST['bairro'];
	$cidade = $_POST['cidade'];
	$estado = $_POST['estado'];
	$complemento = $_POST['complemento'];

	$pwd = $_POST['pwd'];

	if (empty($lid) || empty($cep) || empty($rua) || empty($numero) || empty($bairro) || empty($cidade) || empty($estado) || empty($pwd)) {
		$cadastrando = 'vazio';
	} else {
		$encontraadmin = new ConsultaDatabase($uid);
		$encontraadmin = $encontraadmin->AdminInfo($uid);
		if ($encontraadmin!=0) {
			if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
				$authadmin = new ConsultaDatabase($uid);
				$authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);

				if ($authadmin==0) {
					$cadastrando = 'autorizacao';
				} else {
					$addendereco = new setRow();
					$addendereco = $addendereco->Endereco($uid,$locatario['lid'],$cep,$rua,$numero,$bairro,$cidade,$estado,$complemento,$data);
					if ($addendereco===true) {
						$cadastrando = 'sucesso';
					} else {
						$cadastrando = 'endereco';
					} // addendereco true
				} // authadmin

			} else {
				$cadastrando = 'nivel';
			} // nivel
		} else {
			$cadastrando = 'encontrado';
		} // admin não encontrado
	} // campos preenchidos
} else {
	$cadastrando = 0;
}// $_post

if ($cadastrando == 'vazio') {
	$cadastrando = 'Preencha todos os campos:';
} else if ($cadastrando == 'autorizacao') {
	$cadastrando = 'Tente novamente';
} else if ($cadastrando == 'endereco') {
	$cadastrando = 'Endereço não cadastrado';
} else if ($cadastrando == 'nivel') {
	$cadastrando = 'Administrador não autorizado';
} else if ($cadastrando == 'encontrado') {
	$cadastrando = 'Administrador não encontrado';
} else if ($cadastrando == 'sucesso') {
	$cadastrando = 'Endereço cadastrado com sucesso!';
}

echo $cadastrando;
