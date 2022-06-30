<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];

	if ($_POST['lugar']=='fundamental') {
		BotaoFechar();
	} else if ($_POST['lugar']=='vestimenta') {
		BotaoFecharVestimenta();
	} // lugar

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($aluguel['lid']);
	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->Reserva($aluguel['aid']);
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
				Cancelar a reserva de <?php echo $locatario['nome'] ?> do dia <?php echo $inicio->format('d/m/Y') ?> às <?php echo $inicio->format('H') ?>h?
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
			url: '<?php echo $dominio ?>/painel/reservas/includes/cancelarreserva.inc.php',
			data: {
				aluguel: <?php echo $aid ?>,
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
