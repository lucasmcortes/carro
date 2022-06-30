<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($aluguel['vid']);
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($aluguel['lid']);
	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->Reserva($aluguel['aid']);
	$inicio = new DateTime($reserva['inicio']);
	$devolucao = new DateTime($reserva['devolucao']);
	$ativa = new ConsultaDatabase($uid);
	$ativa = $ativa->ReservaAtiva($reserva['aid']);
	if ($ativa['atid']!=0) {
		if ($ativa['ativa']=='S') {
			$resativa = 1;
		} else {
			$resativa = 0;
		} // ativa
	} else {
		$resativa = 0;
	} // atid >0

	$primeironome = explode(' ',$locatario['nome']);
	$_SESSION['lembrete']['locatario'] = ucfirst(mb_strtolower($primeironome[0]));
	$_SESSION['lembrete']['modelo'] = $veiculo['modelo'];
	$_SESSION['lembrete']['hora'] = $inicio->format('H');

} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">

	<?php
		tituloPagina('lembrete');
		EnviandoImg();
	?>

	<div id='resultado' style='text-align:center;margin:0 auto;margin:5% auto;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin:8% auto;margin-top:0;'>
			<p>
				Enviar email agora para <b><?php echo $locatario['nome'] ?></b> para lembrar sobre a reserva do <b><?php echo $veiculo['modelo'] ?></b> de <b>hoje às <?php echo $inicio->format('H') ?>h</b>?
			</p>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('enviar','confirmar'); ?>
		</div>
	</div>
</div>
<!-- items -->

<script>
	abreVestimenta();

	$('#voltar').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});

	$('#confirmar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/reservas/includes/lembrete.inc.php',
			data: {
				aluguel: <?php echo $aid ?>,
			},
			beforeSend: function() {
				$('#resultado').css('display','none');
				$('#enviando').css('display','inline-block');
			},
			success: function(confirmacao) {
				$('#enviando').css('display','none');
				$('#resultado').css('display','inline-block');
				$('#resultado').html(confirmacao);
				if (confirmacao.includes('sucesso') == true) {
					$('#resultado').append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
				}
				reservaFundamental(<?php echo $aid ?>, <?php echo $resativa ?>);
			}
		});
	});
</script>
