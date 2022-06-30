<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];
	$opcoes = [];
	$string_resultado = '';
	$placas_disponiveis = [];

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);
	if ($locatario['lid']!=0) {
		($locatario['associado']=='S') ? $associado = 'Desde '.strftime('%d de %B de %Y', strtotime($locatario['data_associado'])) : $associado = 'Não';
		$string_resultado .= '
			<span id="locatarioresultspan" data-lid="'.$locatario['lid'].'" data-associado="'.$locatario['associado'].'">
				<b>'.$locatario['nome'].'</b>
				<br>
		';

		if ($associado!='Não') {
			$placas = new ConsultaDatabase($uid);
			$placas = $placas->Placas($locatario['lid']);
			$placas_string = '';
			if (count($placas)>1) {
				$plural_placas = 's';
			} else {
				$plural_placas = '';
			} // plural placas
			$string_resultado .= '<b>Placa'.$plural_placas.': </b>';
			foreach ($placas as $placa) {
				$ativa = new ConsultaDatabase($uid);
				$ativa = $ativa->PlacaAtiva($placa['pid']);
				if ( ($placa['data']>=$locatario['data_associado']) && ($ativa['ativa']==1)) {
					if (!in_array($ativa['pid'],$opcoes)) {
						// vê quantas cortesias a placa tem
						$cortesias = new Conforto($uid);
						$cortesias = $cortesias->Cortesias($placa['pid']);
						$placa += ['cortesias_disponiveis' => $cortesias['cortesias_disponiveis'] ?? 0];

						if (!in_array($placa, $placas_disponiveis)) {
							$placas_disponiveis[] = $placa;
						} // placa nova
						$placas_string .= $placa['placa'].', ';
					} // se ainda não é uma opção
					$opcoes[] = $ativa['pid'];
				} // if placa dessa associatividade
			} // foreach placa
			$placas_disponiveis = array_values(array_unique($placas_disponiveis, SORT_REGULAR));
			$string_resultado .= rtrim($placas_string, ', ');
			$string_resultado .= '<br>';
		} // associado

		$telefone = new Conforto($uid);
		$telefone = $telefone->FormatoTelefone($locatario['telefone'],'br');

		$validade_cnh = explode('/',$locatario['validade']);
		$validade_cnh = $validade_cnh[2].'-'.$validade_cnh[1].'-'.$validade_cnh[0].' 00:00:00.000000';
		$validade_cnh = new DateTime($validade_cnh);
		if ($validade_cnh<$agora) {
			$validade = $validade_cnh->format('d/m/Y').' (renovar)';
		} else {
			$validade = $validade_cnh->format('d/m/Y');
		} // validade

		$string_resultado .= '
				<b>CPF:</b> '.$locatario['documento'].'
				<br>
				<b>CNH:</b> '.$locatario['cnh'].'
				<br>
				<b>Validade da CNH:</b> '.$validade.'
				<br>
				<b>Telefone:</b> '.$telefone.'
				<br>
				<b>Email:</b> '.$locatario['email'].'
				<br>
				<b>Observação:</b> '.$locatario['observacao'].'
			</span>
		';
	} else {
		$string_resultado .= 'Locatário não encontrado. <span id="addlocatario" style="cursor:pointer;text-decoration:underline;">Adicionar</span>';
		$string_resultado .= "
					<script>
						$('#addlocatario').on('click',function () {
							window.location.href='".$dominio."/painel/locatarios/novo/'
						});
					</script>
		";
	}
} else {
	$string_resultado .= ':((';
}// $_post

$resultado = array(
	'resposta'=>$string_resultado,
	'placas'=>$placas_disponiveis
);

header('Content-Type: application/json;');
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
