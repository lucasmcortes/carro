<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['aid'])) {
	$aid = $_POST['aid'];
	$vid = $_POST['vid'];

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);

	$veiculoAtual = new ConsultaDatabase($uid);
	$veiculoAtual = $veiculoAtual->Veiculo($aluguel['vid']);

	$veiculoDesejado = new ConsultaDatabase($uid);
	$veiculoDesejado = $veiculoDesejado->Veiculo($vid);

} else {
	return;
}// $_post

echo "
<!-- items -->
<div class='items'>
";
	tituloPagina('confirmação');
echo "
	<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>
		<div style='min-width:100%;max-width:100%;margin:3% auto;margin-bottom:8%;display:inline-block;'>
			<b>Veículo atual:</b>
			<br>
		 	<p style='font-size:18px;'>".$veiculoAtual['modelo']."</p>
			<br>
			<b>Veículo desejado:</b>
			<br>
		 	<p style='font-size:18px;'>".$veiculoDesejado['modelo']."</p>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
";
			MontaBotao('voltar','voltar');
echo "
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
";
			MontaBotao('modificar','modificar')."
		</div>
";
echo "
	</div>
</div>
<!-- items -->
";
?>

<script>
	abreVestimenta();

	$('#voltar').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});

	$('#modificar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/reservas/includes/mudaveiculo.inc.php',
			data: {
				aid: '<?php echo $aid ?>',
				vidAtual: '<?php echo $veiculoAtual['vid'] ?>',
				vidNovo:  '<?php echo $veiculoDesejado['vid'] ?>',
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
