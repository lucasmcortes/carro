<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];
	$resultado = '';

	if ($_POST['lugar']=='vestimenta') {
		$lugar = $_POST['lugar'];
	} else if ($_POST['lugar']=='fundamental') {
		$lugar = '';
	} // lugar

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->Reserva($aid);
	$ativa = new ConsultaDatabase($uid);
	$ativa = $ativa->ReservaAtiva($reserva['aid']);
	if ($ativa['atid']!=0) {
		if ($ativa['ativa']=='S') {
			$updateconfirmacao = new UpdateRow();
			$updateconfirmacao = $updateconfirmacao->UpdateReservaConfirmacao(1,$aid);
			if ($updateconfirmacao===true) {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							A reserva foi confirmada com sucesso.
						</p>
					</div>
				";
			} else {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							Erro ao confirmar a reserva.
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
						A reserva já estava confirmada.
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

$resultado .= '<script>atualizaCard('.$aluguel['vid'].');</script>';

echo $resultado;

?>
