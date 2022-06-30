<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['manutencao'])) {
	$mid = $_POST['manutencao'];

	if ($_POST['lugar']=='fundamental') {
		BotaoFechar();
	} else if ($_POST['lugar']=='vestimenta') {
		BotaoFecharVestimenta();
	} // lugar

	$manutencao = new ConsultaDatabase($uid);
	$manutencao = $manutencao->Manutencao($mid);
	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($manutencao['vid']);
	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->ManutencaoReserva($manutencao['mid']);
	$inicio = new DateTime($reserva['inicio']);
	$devolucao = new DateTime($reserva['devolucao']);

} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloPagina('cancelar?'); ?>
	<div id='resultado' style='text-align:center;margin:0 auto;margin:5% auto;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
			<p>
				Cancelar o agendamento para a manutenção <?php echo $veiculo['modelo'] ?> do dia <?php echo $inicio->format('d/m/Y') ?>?
			</p>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('sim','cancelar'); ?>
		</div>
	</div>
</div>
<!-- items -->

<script>

	<?php
		if ($_POST['lugar']=='fundamental') {
			echo "
				abreFundamental();
				$('#voltar').on('click',function() {
					$('#fechar').trigger('click');
				});
			";
		} else if ($_POST['lugar']=='vestimenta') {
			echo "
				abreVestimenta();
				$('#voltar').on('click',function() {
					$('#fecharvestimenta').trigger('click');
				});
			";
		} // lugar
	?>

	$('#cancelar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/manutencoes/includes/cancelarreservamanutencao.inc.php',
			data: {
				manutencao: <?php echo $mid ?>,
				lugar: '<?php echo $_POST['lugar'] ?>'
			},
			success: function(cancelamento) {
				$('#resultado').html(cancelamento);
				if (cancelamento.includes('sucesso') == true) {
					$('#resultado').append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});
</script>
