<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['placa'])) {
	$pid = $_POST['placa'];

	$cortesia = [];
	$cortesias_da_placa_utilizadas_no_ultimo_mes = 0;
	$cortesias_da_placa_utilizadas_no_ultimo_ano = 0;
	
	$cortesias_totais_da_placa = new ConsultaDatabase($uid);
	$cortesias_totais_da_placa = $cortesias_totais_da_placa->Cortesia($pid);
	if (count($cortesias_totais_da_placa)>0) {
		foreach ($cortesias_totais_da_placa as $cortesia) {
			if ($cortesia['utilizadas']==0) {
				//
			} else if ($cortesia['utilizadas']>0) {
				$trinta_dias_atras = new DateTime(date('Y-m-d H:i:s.u', strtotime($data. ' -30 days')));
				$um_ano_atras = new DateTime(date('Y-m-d H:i:s.u', strtotime($data. ' -360 days')));
				$data_cortesia = new DateTime($cortesia['data']);
				if ( ($data_cortesia>$trinta_dias_atras) && ($data_cortesia<$agora) ) {
					// se foi nos últimos 30 dias que usou a cortesia
					$cortesias_da_placa_utilizadas_no_ultimo_mes += $cortesia['utilizadas'];
				} else if ( ($data_cortesia<$trinta_dias_atras) && ($data_cortesia>$um_ano_atras) && ($data_cortesia<=$agora) ) {
					// cortesias utilizadas antes de 30 dias atrás dentro dos últimos 360 dias
					$cortesias_da_placa_utilizadas_no_ultimo_ano += $cortesia['utilizadas'];
				} // datas utilizacao
			} // utilizadas
		} // foreach cortesia
	} // cortesias > 0

	echo $cortesias_da_placa_utilizadas_no_ultimo_ano+$cortesias_da_placa_utilizadas_no_ultimo_mes;

} // $_post
?>
