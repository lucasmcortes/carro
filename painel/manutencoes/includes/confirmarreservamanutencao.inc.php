<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['manutencao'])) {
	$mid = $_POST['manutencao'];
	$resultado = '';

	if ($_POST['lugar']=='vestimenta') {
		$lugar = $_POST['lugar'];
	} else if ($_POST['lugar']=='fundamental') {
		$lugar = '';
	} // lugar

	$manutencao = new ConsultaDatabase($uid);
	$manutencao = $manutencao->Manutencao($mid);
	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->ManutencaoReserva($manutencao['mid']);
	$ativa = new ConsultaDatabase($uid);
	$ativa = $ativa->ManutencaoAtivacao($reserva['mreid']);
	if ($ativa['matid']!=0) {
		if ($ativa['ativa']=='S') {
			$updateconfirmacao = new UpdateRow();
			$updateconfirmacao = $updateconfirmacao->UpdateReservaManutencaoConfirmacao(1,$mid);
			if ($updateconfirmacao===true) {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							O agendamento foi confirmado com sucesso.
						</p>
					</div>
				";
			} else {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							Erro ao confirmar o agendamento.
						</p>
						<script>
							setTimeout(function() {
								$('#fecha".$lugar."').trigger('click');
							},5000);
						</script>
					</div>
				";
			} // addativacao true
		} else {
			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						O agendamento já estava confirmado.
					</p>
					<script>
						setTimeout(function() {
							$('#fecha".$lugar."').trigger('click');
						},5000);
					</script>
				</div>
			";
		} // ativa
	} // atid
} else {
	$resultado = 0;
}// $_post

$resultado .= '<script>atualizaCard('.$manutencao['vid'].');</script>';

echo $resultado;

?>
