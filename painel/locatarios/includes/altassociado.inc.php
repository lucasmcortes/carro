<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

BotaoFechar();

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

	$placas = new ConsultaDatabase($uid);
	$placas = $placas->Placas($locatario['lid']);

} else {
	$lid = 0;
}// $_post
?>

<!-- items -->
<div class="items">

	<?php
		tituloCarro($locatario['nome']);
		EnviandoImg();
	?>

	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p id='retorno' class='retorno'>
	        </p> <!-- retorno -->
		<div id='altassociado' style='min-width:100%;max-width:100%;display:inline-block;'>
			<?php
				echo "
				<div id='associadowrap' style='min-width:100%;max-width:100%;'>
					<span id='associadoinfo' class='info' aria-label='"; echo ($locatario['associado']=='S') ? 'Associado' : 'Associar'; echo "'>
				";
					SwitchBox('associadoswitch','Associado','Associar');
				echo "
					</span>
				</div>
				<script>
					$('#associadoswitch').prop('checked', "; echo (($locatario['associado']=='S') ? 'true' : 'false'); echo ");
					$('#associadoswitch').on('change',function() {
						if (this.checked) {
							lassociado = 'S';
						} else {
							lassociado = 'N';
						}
						$('#associadoinfo').attr('aria-label', (lassociado=='S') ? 'Associado' : 'Associar');
						/*$.ajax({
							type: 'POST',
							url: '".$dominio."/painel/locatarios/includes/lassociadomod.inc.php',
							data: {
								locatario: lid,
								associado: lassociado
							},
							success: function(modassociado) {
								if (modassociado.includes('sucesso')) {
									$('#associadoswitch').prop('checked', (lassociado=='S') ? true : false);
									$('#associadoinfo').attr('aria-label', (lassociado=='S') ? 'Associado' : 'Associar');
									mostraFooter();
								} else {
									$('#associadoswitch').prop('checked', "; echo (($locatario['associado']=='S') ? 'true' : 'false'); echo ");
									$('#associadoinfo').attr('aria-label', '"; echo ($locatario['associado']=='S') ? 'Associado' : 'Associar'; echo "');
									$('#bannerfooter').css('display','none');
								}
							}
						});*/
					});
				</script>
				";
			?>

			<div id='placawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
				<label>Placas</label>
				<div id='placainner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<?php
					$opcoes = [];
					foreach ($placas as $placa) {
						if ($placa['data']<$locatario['data_associado']) {
							// de associações anteriores
							// echo "
							// 	<p style='border:1px solid var(--preto);background-color:var(--rosa);padding:5px 8px;margin:3px;float:left;border-radius:var(--radius);'>".$placa['placa']."</p>
							// ";
						} else {
							// da associação atual
							$ativa = new ConsultaDatabase($uid);
							$ativa = $ativa->PlacaAtiva($placa['pid']);
							// placas dessa associação que estão ativas (estão no PlacaAtiva)
							if (!in_array($ativa['pid'],$opcoes)) {
								if ($ativa['pid']!=0) {
									echo "
										<p style='border:1px solid var(--preto);background-color:var(--verde);padding:5px 8px;margin:3px;float:left;border-radius:var(--radius);'>".$ativa['placa']."</p>
									";
								} // placa que existe
							} // se ainda não é uma opção
							$opcoes[] = $ativa['pid'];

							// placas dessa associação mas que foram desativadas (não estão no PlacaAtiva)
							if (!in_array($placa['pid'],$opcoes)) {
								echo "
									<p style='border:1px solid var(--preto);background-color:var(--rosa);padding:5px 8px;margin:3px;float:left;border-radius:var(--radius);'>".$placa['placa']."</p>
								";
							} // se ainda não é uma opção
							$opcoes[] = $placa['pid'];
						} // placa data
					} // foreach
				?>
				</div>
			</div> <!-- placawrap -->
			<div style='min-width:100%;max-width:100%;display:inline-block;'>

				<div style='min-width:49%;max-width:49%;display:inline-block;'>
					<?php MontaBotao('remover placa','removerplaca'); ?>
				</div>

				<div style='min-width:49%;max-width:49%;display:inline-block;'>
					<?php MontaBotao('adicionar placa','enviarplaca'); ?>
				</div>
			</div>

			<div style='margin-top:3%;'>
				<?php InputGeral('Senha','pwd','pwd','password','100'); ?>
			</div>

			<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:1.8%;'>
				<?php MontaBotao('salvar alterações','enviaraltassociado'); ?>
			</div>

		</div> <!-- altassociado wrap -->

		<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:1.8%;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreFundamental();

	$('#enviaraltassociado').on('click',function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviaraltassociado');
                retorno = $('#retorno');
                formulario = $('#altassociado');

		lid = <?php echo $locatario['lid'] ?>;
		lassociado = lassociado;
		valpwd = $('#pwd').val();
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/locatarios/includes/lassociadomod.inc.php',
			data: {
				locatario: lid,
				associado: lassociado,
				pwd: valpwd
			},
			beforeSend: function(altassociado) {
				window.scrollTo(0,0);
				enviandoimg.css('display', 'block');
				formulario.css('display', 'none');
				retorno.css('display', 'none');
			},
			success: function(altassociado) {
				window.scrollTo(0,0);
				bordaRosa();
				enviandoimg.css('display', 'none');
				formulario.css('display', 'inline-block');
				retorno.css('display', 'inline-block');

				if (altassociado.includes('sucesso') == true) {
					retorno.html('Associação modificada com sucesso');
					retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					formulario.remove();
					setTimeout(function() {
						$('#voltar').trigger('click');
					},1234);
					mostraFooter();
				} else {
					retorno.html('Tente novamente');
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

	$('#enviarplaca').on('click',function() {
		lid = <?php echo $locatario['lid'] ?>;
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/locatarios/includes/addplaca.inc.php',
			data: {locatario: lid},
			success: function(linfo) {
				$('#vestimenta').html(linfo);
			}
		});
	});

	$('#removerplaca').on('click',function() {
		lid = <?php echo $locatario['lid'] ?>;
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/locatarios/includes/removerplaca.inc.php',
			data: {locatario: lid},
			success: function(linfo) {
				$('#vestimenta').html(linfo);
			}
		});
	});

	$('#voltar').on('click',function() {
		lid = <?php echo $locatario['lid'] ?>;
		locatarioFundamental(lid);
	});
</script>
