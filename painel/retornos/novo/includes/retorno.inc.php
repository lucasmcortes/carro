<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitretorno'])) {
	$retornando = '';

	$vid = $_POST['veiculo'];
	$limpeza = $_POST['limpeza'];

	$km = str_replace(array(',','.'),'',$_POST['kilometragem']);
	$kilometragem_anterior = new ConsultaDatabase($uid);
	$kilometragem_anterior = $kilometragem_anterior->Kilometragem($vid);
	if ($km<$kilometragem_anterior['km']) {
		echo 'O veículo tinha '.Kilometragem($kilometragem_anterior['km']).' quando foi para a manutenção';
		return;
	} else if ($km==$kilometragem_anterior['km']) {
		RespostaRetorno('kmigual');
		return;
	} // se agora tem menos km rodados que quando foi pra manutenção

	$valor = Sanitiza($_POST['valor']);
	if (empty($valor)) {
		RespostaRetorno('valorretorno');
		return;
	} // valor

	$observacao = $_POST['observacao'];

	// $pwd = $_POST['pwd'];
	// if (empty($pwd)) {
	// 	RespostaRetorno('senha');
	// 	return;
	// } // pwd

	$manutencao = new ConsultaDatabase($uid);
	$manutencao = $manutencao->ManutencaoRecente($vid);
	if ($manutencao['motivo']==2) {
		// voltando de lavar
		$limpeza = 'S';
	} // lavando

	if (empty($vid) || empty($valor) || empty($km) || empty($limpeza) || empty($observacao) ) {
		RespostaRetorno('vazio');
		return;

	} else {
		$encontraadmin = new ConsultaDatabase($uid);
		$encontraadmin = $encontraadmin->AdminInfo($uid);
		if ($encontraadmin!=0) {
			if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
				// $authadmin = new ConsultaDatabase($uid);
				// $authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);
				$authadmin = 1;
				if ($authadmin==0) {
					RespostaRetorno('authadmin');
					return;
				} else {
					$addretornando = new setRow();
					$addretornando = $addretornando->Retorno($uid,$manutencao['mid'],$vid,$valor,$observacao,$data);
					if ($addretornando===true) {
						$addlimpeza = new setRow();
						$addlimpeza = $addlimpeza->Limpeza($uid,$manutencao['vid'],$limpeza,$data);
						if ($addlimpeza===true) {
							$addkilometragem = new setRow();
							$addkilometragem = $addkilometragem->Kilometragem($uid,$manutencao['vid'],$km,$data);
							if ($addkilometragem===true) {
								RespostaRetorno('sucessoretorno');
								return;
							} else {
								RespostaRetorno('regkm');
								return;
							} // addkilometragem true
						} else {
							RespostaRetorno('reglimpeza');
							return;
						} // addlimpeza true
					} else {
						RespostaRetorno('regretorno');
						return;
					} // addretornando true
				} // authadmin true
			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel ok
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // encontraadmin = 0
	} // campos preenchidos
} else {
	$retornando = ':((';
} // isset post submit

echo $retornando;

?>
