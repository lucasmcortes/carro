<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['veiculo'])) {
	$vid = $_POST['veiculo'];

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);

	if ($veiculo['ativo']=='S') {
		$disponibilidade = new Conforto($uid);
		$disponibilidade = $disponibilidade->Possibilidade($vid);

		$podeRemover = 0;
		foreach($disponibilidade['disponibilidade'] as $agendamento) {
			if ($agendamento['status']!='Disponível') {
				$podeRemover++;
			} // != disponivel
		} // foreach disponibilidade

		if ( ($podeRemover==0) && ($disponibilidade['status']=='Disponível') ) {
			echo "
				<!-- items -->
				<div class='items'>
			";
					tituloPagina('confirmação');
			echo "
					<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>
						<div style='min-width:100%;max-width:100%;margin:3% auto;margin-bottom:8%;display:inline-block;'>
							Remover o veículo ".$veiculo['modelo']." (".$veiculo['placa'].")?
						</div>
						<div style='min-width:48%;max-width:48%;display:inline-block;'>
			";
							MontaBotao('voltar','voltar');
			echo "
						</div>
						<div style='min-width:48%;max-width:48%;display:inline-block;'>
			";
							MontaBotao('remover','remover');
			echo "
						</div>
					</div>
				</div>
				<!-- items -->
			";
		} else {

			echo "
				<!-- items -->
				<div class='items'>
			";
					tituloPagina('confirmação');
			echo "
					<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>
						<div style='min-width:100%;max-width:100%;margin:3% auto;margin-bottom:8%;display:inline-block;'>
							O veículo ".$veiculo['modelo']." (".$veiculo['placa'].") ainda tem agendamentos ativos.
						</div>
						<div style='min-width:100%;max-width:100%;display:inline-block;'>
			";
							MontaBotao('voltar','voltar');
			echo "
						</div>
					</div>
				</div>
				<!-- items -->
			";
		}// se tá livre, sem aluguel, sem manutenção, sem reserva etc, só tem a entrada do dia corrente
	} else {
		echo "
			<!-- items -->
			<div class='items'>
		";
				tituloPagina('confirmação');
		echo "
				<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>
					<div style='min-width:100%;max-width:100%;margin:3% auto;margin-bottom:8%;display:inline-block;'>
						Deseja reativar o veículo ".$veiculo['modelo']." (".$veiculo['placa'].")?
					</div>
					<div style='min-width:48%;max-width:48%;display:inline-block;'>
		";
						MontaBotao('voltar','voltar');
		echo "
					</div>
					<div style='min-width:48%;max-width:48%;display:inline-block;'>
		";
						MontaBotao('reativar','reativar');
		echo "
					</div>
				</div>
			</div>
			<!-- items -->
		";

	}

} else {
	$vid = 0;
}// $_post
?>

<script>
	abreVestimenta();

	$('#voltar').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});

	$('#remover').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/veiculos/editar/includes/removerveiculo.inc.php',
			data: {
				veiculo: '<?php echo $vid ?>'
			},
			success: function(modificacao) {
				$('#resultado').html(modificacao);
				if (modificacao.includes('sucesso') == true) {
					$('#resultado').append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

	$('#reativar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/veiculos/editar/includes/reativarveiculo.inc.php',
			data: {
				veiculo: '<?php echo $vid ?>'
			},
			success: function(modificacao) {
				$('#resultado').html(modificacao);
				if (modificacao.includes('sucesso') == true) {
					$('#resultado').append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});
</script>
