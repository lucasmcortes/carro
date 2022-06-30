<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

} else {
	$lid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloPagina('adicionar placa'); ?>
	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p id='retornoaddplaca' class='retorno'>
	        </p> <!-- retorno -->
		<div id='addplaca' style='min-width:100%;max-width:100%;display:inline-block;'>

			<div id='placawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
				<label>Placa</label>
				<div id='placainner' style='min-width:100%;max-width:100%;display:inline-block;'>
					<input type='text' placeholder='Placa' name='placa' id='placa'>
				</div>
			</div> <!-- placawrap -->

			<?php InputGeral('Senha','pwdaddplaca','pwdaddplaca','password','100'); ?>

			<div style='min-width:100%;max-width:100%;display:inline-block;margin:0 auto;'>
				<?php MontaBotao('adicionar','enviarplacamod'); ?>
			</div>

		</div> <!-- addplaca  -->

		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<?php MontaBotao('voltar','voltaraddplaca'); ?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreVestimenta();

	$('#enviarplacamod').on('click',function() {
                retorno = $('#retornoaddplaca');
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/locatarios/includes/addplacamod.inc.php',
			data: {
				locatario: '<?php echo $locatario['lid'] ?>',
				placa:  $('#placa').val(),
				pwd: $('#pwdaddplaca').val()
			},
			success: function(addplaca) {
				retorno.html(addplaca);
				if (addplaca.includes('sucesso')==true) {
					retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					$('#addplaca').css('display','none');
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

	$('#fecharvestimenta').on('click', function () {
		altAssociado(<?php echo $lid ?>);
	});

	$('#voltaraddplaca').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});
</script>
