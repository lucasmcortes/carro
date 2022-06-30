<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido
if (isset($_POST['disponibilidade'])) {
		$vid = $_POST['veiculo'];
		$disponibilidade = $_POST['disponibilidade'];
		$mod_status = $_POST['status'];

		$disponibilidade_veiculo = new Conforto($uid);
		$disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($vid);
		$status = new ConsultaDatabase($uid);
		$status = $status->VeiculoStatus($disponibilidade_veiculo['status']);

		if ( ($mod_status==$disponibilidade_veiculo['status']) && ($status!=$disponibilidade) ) {
			if ($disponibilidade==1) {
				// Mudando pra disponível
				if ($status==6) {
					// É uma nova devolução
					$aluguel = new ConsultaDatabase($uid);
					$aluguel = $aluguel->Aluguel($vid);
					$mod = $dominio.'/painel/devolucoes/novo/?v='.$vid.'&a='.$aluguel['aid'];
				} else {
					// É um novo retorno
					$manutencao = new ConsultaDatabase($uid);
					$manutencao = $manutencao->ManutencaoRecente($vid);
					$mod = $dominio.'/painel/retornos/novo/?v='.$vid.'&m='.$manutencao['motivo'];
				} // qual disponibilidade

			} else if ($disponibilidade==2) {
				// Mudando pra lavando
				// É uma nova manutenção motivo=2 (lavando)
				$mod = $dominio.'/painel/manutencoes/novo/?v='.$vid.'&m=2';

			} else if ($disponibilidade==3) {
				// Mudando pra manutenção
				// É uma nova manutenção motivo=1 (oficina)
				$mod = $dominio.'/painel/manutencoes/novo/?v='.$vid.'&m=1';

			} else if ($disponibilidade==4) {
				// Mudando pra inativo
				// É uma nova manutenção motivo=3 (inativo)
				$mod = $dominio.'/painel/manutencoes/novo/?v='.$vid.'&m=3';

			} else if ($disponibilidade==5) {
				// Mudando pra removido
				// É uma nova manutenção motivo=4 (removido)
				$mod = $dominio.'/painel/manutencoes/novo/?v='.$vid.'&m=4';

			} else if ($disponibilidade==6) {
				// Mudando pra alugado
				// É um novo aluguel
				$mod = $dominio.'/painel/alugueis/novo/';

			} else if ($disponibilidade==7) {
				// Mudando pra revisão
				// É uma nova manutenção motivo=5 (revisão)
				$mod = $dominio.'/painel/manutencoes/novo/?v='.$vid.'&m=5';

			} else if ($disponibilidade==8) {
				// Mudando pra revisão
				// É uma nova manutenção motivo=6 (pintura)
				$mod = $dominio.'/painel/manutencoes/novo/?v='.$vid.'&m=6';

			} else if ($disponibilidade==9) {
				// Tem uma reserva
				$mod = $dominio.'/painel/reservas';
			} // disponibilidade

			// } else if ($disponibilidade==8) {
			// 	// Editar  reserva
			// 	$mod = "
			// 		$.ajax({
			// 			type: 'POST',
			// 			url: '".$dominio."/painel/reservas/includes/resinfo.inc.php',
			// 			data: {aluguel: aid, ativa: 1},
			// 			success: function(resinfo) {
			// 				$('#fundamental').html(resinfo);
			// 			}
			// 		});
			// 	";
			// }

		} else {
			$mod = 'tentenovamente';
		} // se a disponibilidade é diferente

} else {
	$mod = 'tentenovamente';
}// $_post

echo $mod;
