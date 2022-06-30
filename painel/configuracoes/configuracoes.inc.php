<?php
	include_once __DIR__.'/../../includes/setup.inc.php';
	unset($_SESSION['img_logo_info_session']);
?>

<!-- configuracoes -->
<div style='min-width:89%;max-width:89%;display:inline-block;'>
	<?php
		tituloPagina('configurações');

		//echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
			//Icone('addadmin','adicionar administrador','addadminsicon');
		//echo "</div>";
		echo "
			<script>
				$('#addadmin').on('click',function () {
					window.location.href='".$dominio."/painel/administradores/novo';
				});
			</script>
		";
	?>

	<!-- informacoes -->
	<div class='secaoconfig'>
		informações
	</div>
	<div>
		<?php
			if (!empty($logo_empresa)) {
				echo "
					<div style='width:55%;display:inline-block;'>
						<img class='logoimg' style='max-width:222px;max-height:100%;margin-bottom:7px;' src='".$dominio."/painel/configuracoes/logo/".$logo_empresa."?".rand(1, 999)."'></img>
						<script>
							$('.logoimg').on('click', function () {
								$.ajax({
									url: '".$dominio."/includes/biggerlogo.inc.php',
									success: function(bigpic) {
										$('#vestimenta').html(bigpic);
									},
								});
							});
						</script>
				";

						MontaBotao('atualizar imagem','atualizaimglogo');

				echo "
						<script>
							$('#atualizaimglogo').on('click', function () {
								loadVestimenta('".$dominio."/painel/configuracoes/cfigatualizaimglogo.inc.php');
							});
						</script>
					</div>
				";
			} else {
				echo "
				<!-- img_logo_outer_wrap -->
				<div id='img_logo_outer_wrap' class='uploadouterwrap'>
					<label>Logo da empresa:</label>
					<div id='img_logo_wrap' class='uploadwrap'>
						<label id='label_img_logo' for='img_logo' class='upload'>
							<img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
							<p class='uploadcaption'>
								adicionar imagem
							</p>
						</label>
						<input type='file' name='img_logo' id='img_logo' class='plimgupload'  accept='image/jpeg,image/gif,image/png' style='display:none;'>
						<div style='min-width:100%;max-width:100%;display:inline-block;'>
							<div id='progressBarWrap_logo' class='uploadprogressbar'>
								<div id='progressBar_logo' class='uploadprogressbarinner'></div>
								<p id='statusUpload_logo' class='uploadstatusupload'></p>
							</div>
						</div>
					</div>
					<script>
						img_logo_outer_wrap = $('#img_logo_outer_wrap').html();
						function uploadFile(elemento) {
							file = document.getElementById(elemento).files[0];

							formdata = new FormData();
							formdata.append('img_logo', file);
							formdata.append('uploaded_file_name', file.name);

							ajax = new XMLHttpRequest();
							ajax.upload.addEventListener('progress', progressHandler, false);
							ajax.addEventListener('load', completeHandler, false);
							ajax.addEventListener('error', errorHandler, false);
							ajax.addEventListener('abort', abortHandler, false);

							ajax.open('POST','".$dominio."/painel/configuracoes/cfigaddimglogo.inc.php');
							ajax.send(formdata);
						}

						function progressHandler(event) {
							percent = (event.loaded / event.total) * 100;
							$('#progressBar_logo').width(Math.round(percent) + '%');
							document.getElementById('statusUpload_logo').innerHTML = Math.round(percent) + '%';
						}

						function completeHandler(event) {
							document.getElementById('img_logo_wrap').innerHTML = event.target.responseText;
							$('#remove_img_logo').on('click',function() {
								$('#img_logo_outer_wrap').html(img_logo_outer_wrap);
								$.ajax({
									url: '".$dominio."/painel/configuracoes/cfigunsetlogo.inc.php'
								});
							});
						}

						function errorHandler(event) {
							document.getElementById('img_logo_wrap').innerHTML = 'Upload falhou';
						}

						function abortHandler(event) {
							document.getElementById('img_logo_wrap').innerHTML = 'Upload cancelado';
						}

						$('#img_logo').change(function() {
							elemento = $(this).attr('id');
							uploadFile(elemento);
						});
					</script>
				</div>
				<!-- img_logo_outer_wrap -->
				";
			} // se existe a imagem da logo
		?>
	</div>
	<div>
		<div id='cfigrsocialwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Razão social:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='text' placeholder='Razão social' name='cfigrsocial' id='cfigrsocial'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigrsocialwrap -->
	</div>
	<div>
		<div id='cfigcnpjwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>CNPJ:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input onkeyup='maskIt(this,event,"##.###.###/####-##")' class='wrappedinput' type='text' placeholder='99.999.999/9999-99' name='cfigcnpj' id='cfigcnpj'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigcnpjwrap -->
	</div>
	<div>
		<div id='cfigenderecowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Endereço completo:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='text' placeholder='Rua X, Número Y - Bairro Z - Cidade, UF' name='cfigendereco' id='cfigendereco'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigenderecowrap -->
	</div>
	<div>
		<div id='cfigcidadewrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Cidade para pagamento de nota promissória:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='text' placeholder='Cidade, Estado' name='cfigcidade' id='cfigcidade'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigcidadewrap -->
	</div>
	<!-- informacoes -->

	<!-- valores -->
	<div class='secaoconfig'>
		valores
	</div>
	<div>
		<div id='cfigcaucaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Caução padrão:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Caução padrão' name='cfigcaucao' id='cfigcaucao'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigcaucaowrap -->

		<div id='cfigdiariaasmotowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Diária padrão para moto:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Diária padrão para moto' name='cfigdiariaasmoto' id='cfigdiariaasmoto'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigdiariaasmotowrap -->

		<div id='cfigdiariaexcmotowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Diária excedente para moto:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Diária excedente para moto' name='cfigdiariaexcmoto' id='cfigdiariaexcmoto'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigdiariaexcmotowrap -->

		<div id='cfigdiariaaswrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Diária padrão para carro:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Diária padrão para carro' name='cfigdiariaas' id='cfigdiariaas'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigdiariaaswrap -->

		<div id='cfigdiariaexccarrowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Diária excedente para carro:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Diária excedente para carro' name='cfigdiariaexccarro' id='cfigdiariaexccarro'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigdiariaexccarrowrap -->

		<div id='cfigdiariaasutilitariowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Diária padrão para utilitário:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Diária padrão para utilitário' name='cfigdiariaasutilitario' id='cfigdiariaasutilitario'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigdiariaasutilitariowrap -->

		<div id='cfigdiariaexcutilitariowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Diária excedente para utilitário:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Diária excedente para utilitário' name='cfigdiariaexcutilitario' id='cfigdiariaexcutilitario'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigdiariaexcutilitariowrap -->

		<div id='cfigprecokmwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Preço por kilometro rodado excedente:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='text' placeholder='Preço do kilometro rodado' name='cfigprecokm' id='cfigprecokm'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigprecokmwrap -->

		<div id='cfigprecolewrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Preço da limpeza executiva:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Preço da limpeza executiva' name='cfigprecole' id='cfigprecole'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigprecolewrap -->

		<div id='cfigprecolcwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Preço da limpeza completa:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Preço da limpeza completa' name='cfigprecolc' id='cfigprecolc'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigprecolcwrap -->

		<div id='cfigprecolmwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Preço da limpeza completa com motor:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Preço da limpeza completa com motor' name='cfigprecolm' id='cfigprecolm'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigprecolmwrap -->
	</div>
	<!-- valores -->

	<!-- tolerancia -->
	<div class='secaoconfig'>
		tolerância
	</div>
	<div>
		<div id='cfigtoldevwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Minutos de tolerância na devolução do aluguel:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Minutos de tolerância na devolução do aluguel' name='cfigtoldev' id='cfigtoldev'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigtoldevwrap -->
	</div>
	<!-- tolerancia -->

	<!-- revisão -->
	<div class='secaoconfig'>
		revisão
	</div>
	<div>
		<div id='switchativanotificacaorevisaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Notificar revisões dos veículos:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<div id='cfigrev' class='wrappedinput swap'>
						<span id='switchativanotificacaorevisaoinfo' class='info' aria-label='<?php echo ($configuracoes['rev_ativa']=='S') ? 'Sim' : 'Não' ?>' style='float:left;'>
							<?php SwitchBox('switchativanotificacaorevisao','Sim','Não'); ?>
						</span>
					</div>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- switchativanotificacaorevisaowrap -->

		<div id='cfigrevcarlimiarwrap' style='min-width:100%;max-width:100%;margin:3px auto;' class='revconfig'>
			<label>KM limiar de kilometros rodados para notificar revisão em carros e utilitários:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Kilometragem' name='cfigrevcarlimiar' id='cfigrevcarlimiar'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigrevcarprevwrap -->

		<div id='cfigrevcarprevwrap' style='min-width:100%;max-width:100%;margin:3px auto;' class='revconfig'>
			<label>Múltiplo de KM para notificar revisão de carros e utilitários até o limiar de kilometros rodados:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Kilometragem' name='cfigrevcarprev' id='cfigrevcarprev'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigrevcarprevwrap -->

		<div id='cfigrevcaraposwrap' style='min-width:100%;max-width:100%;margin:3px auto;' class='revconfig'>
			<label>Múltiplo de KM para notificar revisão de carros e utilitários após o limiar de kilometros rodados:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Kilometragem' name='cfigrevcarapos' id='cfigrevcarapos'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigrevcaraposwrap -->

		<div id='cfigrevmotowrap' style='min-width:100%;max-width:100%;margin:3px auto;' class='revconfig'>
			<label>Múltiplo de KM para notificar revisão em motos:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Kilometragem' name='cfigrevmoto' id='cfigrevmoto'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigrevcarprevwrap -->
	</div>
	<!-- revisão -->

	<?php //InputGeral('Senha','pwd','pwd','password','100'); ?>

</div>
<!-- configuracoes -->

<script>

	velemento = 0;
	$('#cfigrsocial').val('<?php echo $configuracoes['razao_social']; ?>');
	$('#cfigcnpj').val('<?php echo $configuracoes['cnpj']; ?>');
	$('#cfigendereco').val('<?php echo $configuracoes['endereco']; ?>');
	$('#cfigcidade').val('<?php echo $configuracoes['cidade']; ?>');
	$('#cfigcaucao').val('<?php echo $configuracoes['caucao_preco']; ?>');
	$('#cfigdiariaasmoto').val('<?php echo $configuracoes['preco_diaria_moto_associado']; ?>');
	$('#cfigdiariaexcmoto').val('<?php echo $configuracoes['excedente_moto']; ?>');
	$('#cfigdiariaas').val('<?php echo $configuracoes['preco_diaria_associado']; ?>');
	$('#cfigdiariaexccarro').val('<?php echo $configuracoes['excedente_carro']; ?>');
	$('#cfigdiariaasutilitario').val('<?php echo $configuracoes['preco_diaria_utilitario_associado']; ?>');
	$('#cfigdiariaexcutilitario').val('<?php echo $configuracoes['excedente_utilitario']; ?>');
	$('#cfigprecokm').val('<?php echo str_replace('R$','',Dinheiro($configuracoes['preco_km'])); ?>');
	$('#cfigprecole').val('<?php echo $configuracoes['preco_le']; ?>');
	$('#cfigprecolc').val('<?php echo $configuracoes['preco_lc']; ?>');
	$('#cfigprecolm').val('<?php echo $configuracoes['preco_lm']; ?>');
	$('#cfigdiasac').val('<?php echo $configuracoes['dias_por_acionamento']; ?>');
	$('#cfigdiaspla').val('<?php echo $configuracoes['dias_cortesia_placa_ano']; ?>');
	$('#cfigdiasplames').val('<?php echo $configuracoes['dias_cortesia_placa_mes']; ?>');
	$('#cfigtoldev').val('<?php echo $configuracoes['min_tolerancia']; ?>');
	$('#cfigrevcarlimiar').val('<?php echo $configuracoes['rev_car_limiar']; ?>');
	$('#cfigrevcarprev').val('<?php echo $configuracoes['rev_car_prev']; ?>');
	$('#cfigrevcarapos').val('<?php echo $configuracoes['rev_car_apos']; ?>');
	$('#cfigrevmoto').val('<?php echo $configuracoes['rev_moto']; ?>');

	$('.posinput').on('click', function() {
		elemento = $(this).siblings('.wrappedinput').attr('id');
		pwd = $('#pwd').val();

		if ($('#'+elemento).hasClass('lista') || $('#'+elemento).hasClass('swap')) {
			velemento = velemento;
		} else {
			velemento = $('#'+elemento).val();
		}

		if (elemento=='cfigrev') {
			if ($('#switchativanotificacaorevisao').prop('checked')) {
				valrevconfig = 'S';
			} else {
				valrevconfig = 'N';
			}
			$(this).closest('.info').attr('aria-label', ($(this).is(':checked')) ? 'Sim' : 'Não');

			$.ajax({
				type: 'POST',
				url: '<?php echo $dominio ?>/painel/configuracoes/cfigrev.inc.php',
				data: {
					cfigrev: valrevconfig,
					senha: pwd
				},
				success: function(cfigswitchativanotificacaorevisao) {
					if (cfigswitchativanotificacaorevisao.includes('sucesso')) {
						$('#switchativanotificacaorevisao').prop('checked', (valrevconfig=='S') ? true : false);
						$('#switchativanotificacaorevisaoinfo').attr('aria-label', (valrevconfig=='S') ? 'Sim' : 'Não');
						if (valrevconfig=='S') {
							$('.revconfig').css({
								'opacity':'1',
								'cursor':'auto'
							});
							$('.revconfig').find('*').css({
								'cursor':'auto',
								'pointer-events':'auto'
							});
							$('.revconfig').find('.salvarconfig').css({
								'cursor':'pointer',
								'pointer-events':'auto'
							});
							$('.revconfig').find('*').prop('disabled', false);
						} else {
							$('.revconfig').css({
								'opacity':'0.34',
								'cursor':'not-allowed'
							});
							$('.revconfig').find('*').css({
								'cursor':'not-allowed',
								'pointer-events':'none'
							});
							$('.revconfig').find('*').prop('disabled', true);
						}

						$('#pwd').css('cursor', 'not-allowed');
						$('#pwd').css('background-color', 'var(--verde)');
						$('#pwd').css('color', 'var(--preto)');
						$('#pwd').css('border', '1px solid var(--preto)');

						mostraFooter();
					} else {
						$('#switchativanotificacaorevisao').prop('checked', <?php echo ($configuracoes['rev_ativa']=='S') ? 'true' : 'false'; ?>);
						$('#switchativanotificacaorevisaoinfo').attr('aria-label', '<?php echo ($configuracoes['rev_ativa']=='S') ? 'Sim' : 'Não'; ?>');

						$('#pwd').css('border', '1px solid var(--rosa)');
						$('#pwd').css('background-color', 'var(--branco)');
						$('#pwd').css('color', 'var(--preto)');

						$('#bannerfooter').css('display','none');
					}
				}
			});
		} else {
			$.ajax({
				type: 'POST',
				url: '<?php echo $dominio ?>/painel/configuracoes/'+elemento+'.inc.php',
				data: {
					modificacao: velemento,
					senha: pwd
				},
				beforeSend: function() {
					$('#'+elemento).siblings('.preinput').removeClass('modificado');
					$('#'+elemento).siblings('.preinput').addClass('normal');
				},
				success: function(modconfig) {
					$('#'+elemento).siblings('.preinput').html('');
					if (modconfig.includes('sucesso')) {
						if ($('#'+elemento).siblings('.preinput').hasClass('bgrosa')) {
							$('#'+elemento).siblings('.preinput').removeClass('bgrosa');
							$('#pwd').removeClass('bordarosa');
						}
						$('#'+elemento).css('border', '0');
						$('#'+elemento).closest('.inputouterwrap').css('background-color', 'var(--verdedois)');
						$('#'+elemento).closest('.inputouterwrap').find('*').prop('disabled', 'disabled');
						$('#'+elemento).closest('.inputouterwrap *').css('cursor','not-allowed');
						$('#'+elemento).closest('.inputouterwrap *').css('pointer-events','none');
						$('#'+elemento).siblings('.preinput').removeClass('normal');
						$('#'+elemento).siblings('.preinput').addClass('modificado');
						mostraFooter();
					} else {
						$('#'+elemento).siblings('.preinput').addClass('bgrosa');
						$('#pwd').addClass('bordarosa');
						$('#bannerfooter').css('display','none');
					}
				}
			});
		} /* se cfigrev */
	});

	valrevconfig='<?php echo $configuracoes['rev_ativa'] ?>';
	$('#switchativanotificacaorevisao').prop('checked', <?php echo ($configuracoes['rev_ativa']=='S') ? 'true' : 'false' ?>);
	if ($('#switchativanotificacaorevisao').prop('checked')) {
		$('.revconfig').css({
			'opacity':'1',
			'cursor':'auto'
		});
		$('.revconfig').find('*').css({
			'cursor':'auto',
			'pointer-events':'auto'
		});
		$('.revconfig').find('.salvarconfig').css({
			'cursor':'pointer',
			'pointer-events':'auto'
		});
		$('.revconfig').find('*').prop('disabled', false);
	} else {
		$('.revconfig').css({
			'opacity':'0.34',
			'cursor':'not-allowed'
		});
		$('.revconfig').find('*').css({
			'cursor':'not-allowed',
			'pointer-events':'none'
		});
		$('.revconfig').find('*').prop('disabled', true);
	}
	$('#switchativanotificacaorevisaoinfo').attr('aria-label', ($('#switchativanotificacaorevisao:checked').length!=0) ? 'Sim' : 'Não');

</script>
