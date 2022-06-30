<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['mid'])) {
	$mid = $_POST['mid'];
	$resultado = '';
	
	$novo_inicio = $_POST['comeco_modificado'].' 00:00:00.000000';
	$nova_devolucao = $_POST['conclusao_modificada'].' 00:00:00.000000';

	$manutencaoinfo = new ConsultaDatabase($uid);
	$manutencaoinfo = $manutencaoinfo->Manutencao($mid);

	$manutencaoreserva = new ConsultaDatabase($uid);
	$manutencaoreserva = $manutencaoreserva->ManutencaoReserva($mid);
	$manutencaoativacao = new ConsultaDatabase($uid);
	$manutencaoativacao = $manutencaoativacao->ManutencaoAtivacao($manutencaoreserva['mreid']);
	if ($manutencaoativacao['ativa']=='S') {
		$addmreserva = new setRow();
		$addmreserva = $addmreserva->ManutencaoReserva($uid,$mid,0,$novo_inicio,$nova_devolucao,$data);
		if ($addmreserva===true) {
			$manutencaoreservamod = new ConsultaDatabase($uid);
			$manutencaoreservamod = $manutencaoreservamod->ManutencaoReserva($mid);
			$addativacaomanutencao = new setRow();
			$addativacaomanutencao = $addativacaomanutencao->ManutencaoAtivacao($uid,$manutencaoreservamod['mreid'],'S',$data);
			if ($addativacaomanutencao===true) {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							O agendamento foi modificado com sucesso.
						</p>
					</div>
				";
			} else {
				$resultado = "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p class='respostaalteracao'>
							Erro ao ativar o agendamento.
						</p>
						<script>
							setTimeout(function() {
								$('#fecharvestimenta').trigger('click');
							},5000);
						</script>
					</div>
				";
			} // addativacao true
		} else {
			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						Erro ao modificar o agendamento.
					</p>
					<script>
						setTimeout(function() {
							$('#fecharvestimenta').trigger('click');
						},5000);
					</script>
				</div>
			";
		} // addativacao true
	} // ativa
} else {
	$resultado = 0;
}// $_post

$resultado .= '<script>atualizaCard('.$manutencaoinfo['vid'].');</script>';

echo $resultado;
?>
