<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['inicio'])) {
	$hoje = $agora->format('Y-m-d H');
	$hora = $agora->format('H:i:s.u');

	$inicio_string = $_POST['inicio'].' '.$hora;
	$devolucao_string = $_POST['devolucao'].' '.$hora;
	$data_inicio = new DateTime($inicio_string);
	$data_devolucao = new DateTime($devolucao_string);

	$veiculos_disponiveis = [];

	$veiculos = new ConsultaDatabase($uid);
	$veiculos = $veiculos->ListaVeiculos();
	if ($veiculos[0]['vid']!=0) {
		foreach ($veiculos as $veiculo) {
			if ($veiculo['ativo']=='S') {
				$possibilidade = new Conforto($uid);
				$possibilidade = $possibilidade->DiasDesejados($veiculo['vid'],$data_inicio,$data_devolucao);
				if (count($possibilidade)==0) {
					$veiculos_disponiveis[] = array(
						'vid'=>$veiculo['vid'],
						'modelo'=>$veiculo['modelo']
					);
				} // veículo com período disponível
			} // ativo
		} // foreach veiculo
	} // existe veículo

	$resultado = array(
		'veiculos'=>$veiculos_disponiveis,
		'quantidade_de_veiculos'=>count(($veiculos_disponiveis)),
		'inicio'=>$inicio_string,
		'devolucao'=>$devolucao_string
	);
} else {
	$resultado = ':((';
}// $_post

header('Content-Type: application/json;');
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
