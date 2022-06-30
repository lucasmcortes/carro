<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['aluguel'])) {
	$string_resultado = '';
	$tabela = "";

	$aid = $_POST['aluguel']-$acrescimoaid;
	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
	if ($aluguel['aid']!=0) {

		$aluguel = new ConsultaDatabase($uid);
		$aluguel = $aluguel->AluguelInfo($aid);

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($aluguel['vid']);

		$locatario = new ConsultaDatabase($uid);
		$locatario = $locatario->LocatarioInfo($aluguel['lid']);

		$categoria = new ConsultaDatabase($uid);
		$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

		$dia = new DateTime($aluguel['data']);
		$consultadevolucao = new ConsultaDatabase($uid);
		$consultadevolucao = $consultadevolucao->Devolucao($aluguel['aid']);
		if ($consultadevolucao['deid']!=0) {
			$inicio = new DateTime($aluguel['inicio']);
			$devolucao = new DateTime($consultadevolucao['devolucao']);
		} else {
			$inicio = new DateTime($aluguel['inicio']);
			$devolucao = new DateTime($aluguel['devolucao']);
		} // consultadevolucao = 0

		($aluguel['acid']==0) ? $acionamento = 'Não' : $acionamento = 'Sim';

		$reserva = new ConsultaDatabase($uid);
		$reserva = $reserva->Reserva($aluguel['aid']);
		if ($reserva['reid']!=0) {
			$atividade = new ConsultaDatabase($uid);
			$atividade = $atividade->Ativacao($reserva['reid']);
			if ($atividade['ativa']=='S') {
				$inicio = new DateTime($reserva['inicio']);
				$devolucao = new DateTime($reserva['devolucao']);
				if ($reserva['confirmada']==1) {
					$ativo = 'ativo';
				} else {
					$ativo = '';
				}// confirmada
			} else {
				$ativo = '';
			} // ativa
		} else {
			$devolvido = new ConsultaDatabase($uid);
			$devolvido = $devolvido->Devolucao($aluguel['aid']);
			if ($devolvido['deid']==0) {
				$ativo = 'ativo';
			} else {
				$ativo = '';
			}
		}// reserva

		$diarias = new Conforto($uid);
		$diarias = $diarias->TotalDiarias($inicio,$devolucao);

		$contrato_numero = $aluguel['aid']+$acrescimoaid;

		$tabela .= "
			<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap ".$ativo."'>
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='numregistro'>
						<b>".$contrato_numero."</b>
					</p>
				</div>
				<div class='slotrelatoriowrap'>
					<div class='slotrelatorio'>
						<p class='headerslotrelatorio'><b>Locatário:</b></p>
						<p class='infoslotrelatorio'>".$locatario['nome']."</p>
						<p class='headerslotrelatorio'><b>Acionamento:</b></p>
						<p class='infoslotrelatorio'>".$acionamento."</p>
						<p class='headerslotrelatorio'><b>Data de registro:</b></p>
						<p class='infoslotrelatorio'>".$dia->format('d/m/Y')." às ".$dia->format('H')."h".$dia->format('i')."</p>
					</div>
				</div>
				<div class='slotrelatoriowrap'>
					<div class='slotrelatorio'>
						<p class='headerslotrelatorio'><b>Modelo:</b></p>
						<p class='infoslotrelatorio'>".$veiculo['modelo']."</p>
						<p class='headerslotrelatorio'><b>Placa:</b></p>
						<p class='infoslotrelatorio'>".$veiculo['placa']."</p>
						<p class='headerslotrelatorio'><b>Kilometragem:</b></p>
						<p class='infoslotrelatorio'>".Kilometragem($aluguel['kilometragem'])."</p>
					</div>
				</div>
				<div class='slotrelatoriowrap'>
					<div class='slotrelatorio'>
						<p class='headerslotrelatorio'><b>Data de início:</b></p>
						<p class='infoslotrelatorio'>".$inicio->format('d/m/Y')." às ".$inicio->format('H')."h</p>
						<p class='headerslotrelatorio'><b>Data de devolução:</b></p>
						<p class='infoslotrelatorio'>".$devolucao->format('d/m/Y')." às ".$devolucao->format('H')."h</p>
					</div>
					<div class='slotrelatorio'>
						<p class='headerslotrelatorio'><b>Previsão de diárias:</b></p>
						<p class='infoslotrelatorio'>".$diarias." x ".Dinheiro($aluguel['diaria'])."</p>
					</div>
				</div>
			</div>
		";

		$tabela .= "
		<script>
			$('.relatoriowrap').on('click', function() {
				aid = $(this).attr('id').split('_')[1];
				if ($(this).hasClass('ativo')) {
					valativo = 1;
				} else {
					valativo = 0;
				}
				aluguelFundamental(aid,valativo);
			});
		</script>
		";
	} else {
		$string_resultado .= 'Aluguel não encontrado';
	} // existe o aid

} else {
	$string_resultado .= ':((';
}// $_post

$resultado = array(
	'resposta'=>$string_resultado,
	'tabela'=>$tabela
);

header('Content-Type: application/json;');
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
