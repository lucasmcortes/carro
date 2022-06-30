<?php
	include_once __DIR__.'/../../includes/setup.inc.php';
	BotaoFechar();

	$licenca = new ConsultaDatabase($uid);
	$licenca = $licenca->LicencaUsuario($uid);
?>

<!-- items -->
<div class="items">
	<?php tituloPagina('cancelar?'); ?>
	<div id='resultado' style='text-align:center;margin:0 auto;margin-bottom:5%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;margin-bottom:8%;'>
			<p style='text-align:center;margin-bottom:5%;'>
				Cancelar plano anual?
			</p>
			<p class='aviso' style='margin:0px 5%;'>
				Não haverá extorno.
				<br>
				Não haverão cobranças adicionais.
				<br>
				Essa ação não poderá ser desfeita.
			</p>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotaoSecundario('sim','cancelar'); ?>
		</div>
	</div>
</div>
<!-- items -->

<script>
	abreFundamental();

	$('#voltar').on('click',function() {
		$('#fechar').trigger('click');
	});

	$('#cancelar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/minhaconta/plano/cancelarplano.inc.php',
			data: {
				licid: '<?php echo $licenca['licid'] ?>'
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
