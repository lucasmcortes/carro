<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
	$cadastrando = '';
	$lid = $_POST['locatario'];

	if (empty($_POST['placa'])) {
		RespostaRetorno('placaveiculo');
		return;
	} else {
		$placa = new Conforto($uid);
		$placa = $placa->FormatoPlaca($_POST['placa']);
		// if (preg_match('/([A-Z0-9]{3}\-[A-Z0-9]{4})?/', $placa, $placa, PREG_UNMATCHED_AS_NULL)) {
		// 	$placa = $placa[0];
		// } else {
		// 	RespostaRetorno('placaveiculo');
		// 	return;
		// } // regex placa
	} // placa

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

	$pwd = $_POST['pwd'];
	if (empty($pwd)) {
		RespostaRetorno('senha');
		return;
	} // pwd

	if (empty($lid) || empty($placa) || empty($pwd)) {
		RespostaRetorno('vazio');
		return;
	} else {
		$encontraadmin = new ConsultaDatabase($uid);
		$encontraadmin = $encontraadmin->AdminInfo($uid);
		if ($encontraadmin!=0) {
			if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
				$authadmin = new ConsultaDatabase($uid);
				$authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);

				if ($authadmin==0) {
					RespostaRetorno('authadmin');
					return;
				} else {
					$addplaca = new setRow();
					$addplaca = $addplaca->Placa($uid,$lid,$locatario['asid'],$placa,$data);
					if ($addplaca===true) {
						$placarecente = new ConsultaDatabase($uid);
						$placarecente = $placarecente->PlacaRecente($locatario['lid']);
						$ativaplaca = new setRow();
						$ativaplaca = $ativaplaca->PlacaAtivacao($uid,$placarecente,1,$data);
						if ($ativaplaca===true) {
							RespostaRetorno('sucessoplacamod');
							return;
						} else {
							RespostaRetorno('regplacaativacao');
							return;
						} // ativaplaca true
					} else {
						RespostaRetorno('regplaca');
						return;
					} // addplaca true
				} // authadmin

			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // admin não encontrado
	} // campos preenchidos
} else {
	$cadastrando = 0;
}// $_post

echo $cadastrando;
