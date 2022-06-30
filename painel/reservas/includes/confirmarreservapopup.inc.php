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
	<?php tituloPagina('confirmar?'); ?>
	<div id='resultado' style='text-align:center;margin:0 auto;margin:5% auto;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
			<p>
				O veículo foi retirado por <?php echo $locatario['nome'] ?> para a reserva do dia <?php echo $inicio->format('d/m/Y') ?> às <?php echo $inicio->format('H') ?>h?
			</p>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('sim','confirmar'); ?>
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

	$('#confirmar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/reservas/includes/confirmarreserva.inc.php',
			data: {
				aluguel: <?php echo $aid ?>,
				lugar: '<?php echo $_POST['lugar'] ?>'
			},
			success: function(confirmacao) {
				$('#resultado').html(confirmacao);
				if (confirmacao.includes('sucesso') == true) {
					$('#resultado').append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});
</script>
