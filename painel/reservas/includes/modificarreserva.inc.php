<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];
	$resultado = '';
	$novo_inicio = $_POST['comeco_modificado'];
	$nova_devolucao = $_POST['conclusao_modificada'];

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);

	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->Reserva($aid);
	$ativa = new ConsultaDatabase($uid);
	$ativa = $ativa->ReservaAtiva($reserva['aid']);
	if ($ativa['atid']!=0) {
		if ($ativa['ativa']=='S') {
			$addmodificacao = new setRow();
			$addmodificacao = $addmodificacao->Reserva($uid,$reserva['aid'],0,$novo_inicio,$nova_devolucao,$data);
			if ($addmodificacao===true) {
				$reservamod = new ConsultaDatabase($uid);
				$reservamod = $reservamod->Reserva($reserva['aid']);
				$addativacao = new setRow();
				$addativacao = $addativacao->Ativacao($uid,$reservamod['reid'],'S',$data);
				if ($addativacao===true) {
					$resultado = "
						<div style='min-width:100%;max-width:100%;display:inline-block;'>
							<p class='respostaalteracao'>
								A reserva foi modificada com sucesso.
							</p>
						</div>
					";
				} else {
					$resultado = "
						<div style='min-width:100%;max-width:100%;display:inline-block;'>
							<p class='respostaalteracao'>
								Erro ao ativar a reserva.
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
							Erro ao modificar a reserva.
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
	} // atid
} else {
	$resultado = 0;
}// $_post

$resultado .= '<script>atualizaCard('.$aluguel['vid'].');</script>';

echo $resultado;
?>
