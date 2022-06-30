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
	$manutencaoreserva = new ConsultaDatabase($uid);
	$manutencaoreserva = $manutencaoreserva->ManutencaoReserva($mid);
	$manutencaoativacao = new ConsultaDatabase($uid);
	$manutencaoativacao = $manutencaoativacao->ManutencaoAtivacao($manutencaoreserva['mreid']);
	if ($manutencaoativacao['matid']!=0) {
		if ($manutencaoativacao['ativa']=='S') {
			$addativacao = new setRow();
			$addativacao = $addativacao->ManutencaoAtivacao($uid,$manutencaoreserva['mreid'],'N',$data);
			if ($addativacao===true) {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							O agendamento foi cancelado com sucesso.
						</p>
					</div>
				";
			} else {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							Erro ao cancelar o agendamento.
						</p>
						<script>
							setTimeout(function() {
								$('#fechar".$lugar."').trigger('click');
							},5000);
						</script>
					</div>
				";
			} // addativacao true
		} else {
			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						O agendamento já estava cancelado.
					</p>
					<script>
						setTimeout(function() {
							$('#fechar".$lugar."')'.trigger('click');
						},5000);
					</script>
				</div>
			";
		}// ativa
	} // atid
} else {
	$resultado = 0;
}// $_post

$resultado .= '<script>atualizaCard('.$manutencao['vid'].');</script>';

echo $resultado;

?>
