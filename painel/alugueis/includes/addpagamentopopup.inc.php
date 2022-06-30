<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];
	$ativo = $_POST['ativo'];

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);

} else {
	echo ':((';
} // $_post
?>

<!-- items -->
<div class='items'>
	<?php tituloPagina('adicionar pagamento'); ?>

	<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>

		<div id='addvalorwrap' style='min-width:100%;max-width:100%;margin:3% auto;'>
			<label>Valor pago</label>
			<div id='addvalorinner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<input type='text' id='addvalor' placeholder='Valor pago'></input>
			</div>
		</div> <!-- addvalorwrap -->

		<div id='formawrap' style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
			<?php SelectFormaPagamento('Forma de pagamento','forma'); ?>
		</div> <!-- formawrap -->

		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('pagar','pagar'); ?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreVestimenta();

	$('#voltar').on('click',function() {
		aluguelFundamental(<?php echo $aid ?>,<?php echo $ativo ?>);
		$('#fecharvestimenta').trigger('click');
	});

	$('#pagar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/alugueis/includes/addpagamento.inc.php',
			data: {
				aluguel: '<?php echo $aid ?>',
				valor: $('#addvalor').val(),
				forma: $('#forma').val(),
				ativo: '<?php echo $ativo ?>'
			},
			success: function(modificacao) {
				$('#resultado').html(modificacao);
			}
		});
	});
</script>
