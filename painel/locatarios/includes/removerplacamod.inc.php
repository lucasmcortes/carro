<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];
	$placa = $_POST['placa'];

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

	$pwd = $_POST['pwd'];

	if (empty($lid) || empty($placa) || empty($pwd)) {
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
					$ativaplaca = new setRow();
					$ativaplaca = $ativaplaca->PlacaAtivacao($uid,$placa,0,$data);
					if ($ativaplaca===true) {
						$cadastrando = 'sucesso';
					} else {
						$cadastrando = 'ativacao';
					} // ativaplaca true
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
} else if ($cadastrando == 'placa') {
	$cadastrando = 'Placa não cadastrada';
} else if ($cadastrando == 'ativacao') {
	$cadastrando = 'Placa não removida';
} else if ($cadastrando == 'nivel') {
	$cadastrando = 'Administrador não autorizado';
} else if ($cadastrando == 'encontrado') {
	$cadastrando = 'Administrador não encontrado';
} else if ($cadastrando == 'sucesso') {
	$cadastrando = 'Placa removida com sucesso';
}

echo $cadastrando;
