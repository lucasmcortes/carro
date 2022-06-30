<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitmanutencao'])) {
	$manutencao = '';
	$agendamento = '';

	$vid = $_POST['veiculo'];
	$estabelecimento = $_POST['estabelecimento'];
	$motivo = $_POST['motivo'];

	$inicio = $_POST['inicio'];
	if (preg_match('/^(\d{2}\/\d{2}\/\d{4})+$/', $inicio, $inicio, PREG_UNMATCHED_AS_NULL)) {
		$inicio = $inicio[0];
		$inicio = explode('/',$inicio);
		$inicio = $inicio[2].'-'.$inicio[1].'-'.$inicio[0];
		$inicio = $inicio.' '.$agora->format('H:i:s.u');
		$data_inicio = new DateTime($inicio);
	} else {
		RespostaRetorno('datainicio');
		return;
	} // regex data inicio

	$devolucao = $_POST['devolucao'];
	if (preg_match('/^(\d{2}\/\d{2}\/\d{4})+$/', $devolucao, $devolucao, PREG_UNMATCHED_AS_NULL)) {
		$devolucao = $devolucao[0];
		$devolucao = explode('/',$devolucao);
		$devolucao = $devolucao[2].'-'.$devolucao[1].'-'.$devolucao[0];
		$devolucao = $devolucao.' '.$agora->format('H:i:s.u');
		$data_devolucao = new DateTime($devolucao);
	} else {
		RespostaRetorno('datadevolucao');
		return;
	} // regex data devolucao

	if ($inicio>$devolucao) {
		RespostaRetorno('datareal');
		return;
	}

	$possibilidade = new Conforto($uid);
	$possibilidade = $possibilidade->AluguelPossivel($vid,$data_inicio,$data_devolucao);
	if (count($possibilidade)>0) {
		$dias_agendados = 'O(s) dia(s) ';
		foreach ($possibilidade as $dia) {
			$dia = new DateTime($dia);
			$dia = $dia->format('d/m/Y');
			$dias_agendados .= '<b>'.$dia.'</b>, ';
		} // foreach
		$dias_agendados = rtrim($dias_agendados,', ');
		$dias_agendados .= ' já estão agendado para esse veículo.<br>';
		echo $dias_agendados;
		return;
	} // existem dias desejados nessa modificação que estão agendados por outra reserva

	$veiculodisponibilidade = new ConsultaDatabase($uid);
	$veiculodisponibilidade = $veiculodisponibilidade->VeiculoMotivo($motivo);

	if (empty($vid) || empty($estabelecimento) || empty($motivo) ) {
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
					$addmanutencao = new setRow();
					$addmanutencao = $addmanutencao->Manutencao($uid,$vid,$estabelecimento,$motivo,$inicio,$devolucao,$data);
					if ($addmanutencao===true) {
						if ($data_inicio->format('Y-m-d')>$agora->format('Y-m-d')) {
							// add reserva
							$manutencaoadicionada = new ConsultaDatabase($uid);
							$manutencaoadicionada = $manutencaoadicionada->VeiculoManutencoes($vid);
							$mid = $manutencaoadicionada[0]['mid'];
							$addmreserva = new setRow();
							$addmreserva = $addmreserva->ManutencaoReserva($uid,$mid,0,$inicio,$devolucao,$data);
							if ($addmreserva===true) {
								// add ativacao
								$manutencaoreserva = new ConsultaDatabase($uid);
								$manutencaoreserva = $manutencaoreserva->ManutencaoReserva($mid);
								$addativacaomanutencao = new setRow();
								$addativacaomanutencao = $addativacaomanutencao->ManutencaoAtivacao($uid,$manutencaoreserva['mreid'],'S',$data);
								if ($addativacaomanutencao===true) {
									RespostaRetorno('sucessomanutencao');
									return;
								 } else {
									RespostaRetorno('regmanutencaoativacao');
									return;
								 } // addativacaomanutencao true
							} else {
							       RespostaRetorno('regreservamanutencao');
							       return;
							} // addmreserva true
						} else {
							RespostaRetorno('sucessomanutencao');
							return;
						} // se é reserva
					} else {
						RespostaRetorno('regmanutencao');
						return;
					} // addmanutencao true
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
	$manutencao = ':((';
} // isset post submit

echo $manutencao;

?>
