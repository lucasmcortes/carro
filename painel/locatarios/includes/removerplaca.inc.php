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
	<?php tituloPagina('remover placa'); ?>
	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p id='retornormvplaca' class='retorno'>
	        </p> <!-- retorno -->
		<div id='rmvplaca' style='min-width:100%;max-width:100%;display:inline-block;'>

			<div id='placawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
				<label>Placa</label>
				<div id='placainner' style='min-width:100%;max-width:100%;display:inline-block;'>
					<select id='placa'>
						<option value=''>-- ESCOLHA --</option>
						<?php
							$opcoes = [];
							$placas = new ConsultaDatabase($uid);
							$placas = $placas->Placas($locatario['lid']);
							if ($placas[0]['pid']!=0) {
								foreach ($placas as $placa) {
									$ativa = new ConsultaDatabase($uid);
									$ativa = $ativa->PlacaAtiva($placa['pid']);
									if ( ($placa['data']>=$locatario['data_associado']) && ($ativa['ativa']==1)) {
										if (!in_array($ativa['pid'],$opcoes)) {
											echo '<option value="'.$ativa['pid'].'">'.$ativa['placa'].'</option>';
										} // se ainda não é uma opção
										$opcoes[] = $ativa['pid'];
									} // placa do associado
								} // foreach
							} // pid > 0
						?>
					</select>
				</div>
			</div> <!-- placawrap -->

			<?php InputGeral('Senha','pwdrmvplaca','pwdrmvplaca','password','100'); ?>

			<div style='min-width:100%;max-width:100%;display:inline-block;margin:0 auto;'>
				<?php MontaBotao('remover','removerplacamod'); ?>
			</div>

		</div> <!-- rmvplaca  -->

		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<?php MontaBotao('voltar','voltarrmvplaca'); ?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreVestimenta();

	$('#removerplacamod').on('click',function() {
                retorno = $('#retornormvplaca');
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/locatarios/includes/removerplacamod.inc.php',
			data: {
				locatario: '<?php echo $locatario['lid'] ?>',
				placa: $('#placa').val(),
				pwd: $('#pwdrmvplaca').val()
			},
			success: function(removeplacar) {
				retorno.html(removeplacar);
				if (removeplacar.includes('sucesso')==true) {
					$('#rmvplaca').css('display','none');
					retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
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

	$('#voltarrmvplaca').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});
</script>
