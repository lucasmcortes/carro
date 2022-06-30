<?php
	require_once __DIR__.'/../../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);

		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->AdminCategoria($adminivel['nivel']);

                $listaadmin = new ConsultaDatabase($uid);
                $listaadmin = $listaadmin->ListaAdmin();

	} else {
		redirectToLogin();
	} // isset uid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina('novo recibo');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('recibosemitidos','recibos emitidos','todosrecibosicon');
					echo "</div>";
					echo "
						<script>
							$('#recibosemitidos').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas/recibo/emitidos';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>

					<p id='retorno' class='retorno'>
						Preencha os campos
				        </p> <!-- retorno -->

					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<!--<div style='min-width:300px;max-width:300px;display:inline-block;'>
							<div class="rangerwrap">
								<input type='range' name='' class='ranger sombraabaixo' min='0' max='100000' step='1' value='0'>
								<span class='ranger-thumb'>km</span>
							</div>
						</div>-->
						<script>
							var $range = $('.ranger');
							$range.each(function() {
								var $thumb = $(this).next('.ranger-thumb');
								var max = parseInt(this.max, 10);
								var tw = 100; // Thumb width. See CSS
								$(this).on('input input.ranger', function() {
									var w = $(this).width();
									var val = parseInt(this.value, 10);
									var txt = val >= max ? '∞' : val;
									var xPX = val * (w - tw) / max; // Position in PX
									// var xPC = xPX * 100 / w;     // Position in % (if ever needed)
									$thumb.css({left: xPX}).attr("data-val", txt);
								});
							});
							$range.trigger('input.ranger'); // Calc on load
							$(window).on("resize", () => $range.trigger("input.ranger")); // and on resize
						</script>
					</div>

					<!-- container -->
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>

						<div style='min-width:100%;max-width:100%;display:inline-block;padding:1.8% 3%;padding-top:0;'>
							<div class='wrapcampo'>
								<p class='descrcampo'>Nº.: </p>
								<div class='camporecibo'><input class='inputrecibo' type='text' placeholder='Número' name='numero' id='numero' value='01/01' maxlength='5'></input></div>
								<p class='charcountwrap'><span>0</span>/5</p>
							</div>

							<div id='pagadorwrap'>
								<div class='wrapcampo'>
									<p class='descrcampo'>Recebemos de: </p>
									<div class='camporecibo' style='position:relative;'>
										<input class='inputrecibo' type='text' placeholder='Pagador' name='pagador' id='pagador' maxlength='55'></input>
										<div id='pagadoroptions'></div>
									</div>
									<p class='charcountwrap'><span>0</span>/55</p>
								</div>
							</div>

							<div class='wrapcampo'>
								<p class='descrcampo'>Endereço: </p>
								<div class='camporecibo'><input class='inputrecibo' type='text' placeholder='Endereço' name='endereco' id='endereco' maxlength='59'></input></div>
								<p class='charcountwrap'><span>0</span>/59</p>
							</div>

							<script>
								typingTimer = '';
								doneTypingInterval = 555;
								$('#pagador').on('keyup', function () {
									clearTimeout(typingTimer);
									typingTimer = setTimeout(doneTyping, doneTypingInterval);
								});
								$('#pagador').on('keydown', function () {
									clearTimeout(typingTimer);
								});
								function doneTyping () {
									if ($('#pagador').val()!='') {
										BuscaPagador();
									} else {
										$('#endereco').val('');
										$('#pagadoroptions').empty();
										$('#pagadoroptions').css('border','0');
									}
								}

								function BuscaPagador() {
									$.ajax({
										type: 'POST',
										url: '<?php echo $dominio ?>/painel/cobrancas/recibo/includes/buscapagador.inc.php',
										data: { pagador: $('#pagador').val() },
							                        beforeSend: function() {
							                                $('#pagadoroptions').html('');
							                                $('#pagadoroptions').html("<div id='enviando' style='display:inline-block;'><div id='enviandospinner'></div></div>");
							                        },
										success: function(pagadores) {
											$('#pagadoroptions').empty();
											if (pagadores.length>0) {
												$('#pagadoroptions').css('border','1px solid var(--preto)');

												for(var p in pagadores) {
													$('#pagadoroptions').append("<p class='pagadoroption' data-endereco='"+pagadores[p]['endereco']+"'>"+pagadores[p]['nome']+"</p>");
												}

												$('.pagadoroption').on('click', function() {
													$('#pagador').val($(this).html());
													$('#endereco').val($(this).data('endereco'));
													$('#pagadoroptions').empty();
													$('#pagadoroptions').css('border','0');
												});
											}
										}
									});
								}
							</script>

							<div class='wrapcampo'>
								<p class='descrcampo'>A importância de R$: </p>
								<div class='camporecibo'><input class='inputrecibo' type='text' onkeyup='maskIt(this,event,"#######")' placeholder='Valor' name='valor' id='valor'></input></div>
								<p class='charcountwrap'><span>0</span>/7</p>
							</div>

							<div class='wrapcampo'>
								<p class='descrcampo'>Referente a: </p>
								<div class='camporecibo'><input class='inputrecibo' type='text' placeholder='Referente a' name='referente' id='referente' maxlength='152'></input></div>
								<p class='charcountwrap'><span>0</span>/152</p>
							</div>

							<div class='wrapcampo'>
								<p class='descrcampo'>Cidade: </p>
								<div class='camporecibo'><input class='inputrecibo' type='text' placeholder='Cidade' name='cidade' id='cidade' value='' maxlength='51'></input></div>
								<p class='charcountwrap'><span>0</span>/51</p>
							</div>

							<?php
								if ($agora->format('m')==3) {
									$mesReciboExtenso = 'março';

									$dataRecibo = strftime('%d de ', strtotime($agora->format('Y-m-d')));
									$dataRecibo .= $mesReciboExtenso;
									$dataRecibo .= strftime(' de %Y', strtotime($agora->format('Y-m-d')));
								} else {
									$dataRecibo = strftime('%d de %B de %Y', strtotime($agora->format('Y-m-d')));
								} // mes devolucao = março
							?>
							<div class='wrapcampo'>
								<p class='descrcampo'>Data: </p>
								<div class='camporecibo'><input class='inputrecibo' type='text' placeholder='Data' name='datarecibo' id='datarecibo' value='<?php echo $dataRecibo ?>' maxlength='23'></input></div>
								<p class='charcountwrap'><span>0</span>/23</p>
							</div>

							<div class='wrapcampo'>
								<p class='descrcampo'>Emitente: </p>
								<div class='camporecibo'><input class='inputrecibo' type='text' placeholder='Emitente' name='emitente' id='emitente' value='' maxlength='31'></input></div>
								<p class='charcountwrap'><span>0</span>/31</p>
							</div>

							<div class='wrapcampo'>
								<div id='tipodocumentoinner' style='white-space: nowrap;display:inline-block;'>
									<span id='tipodocumentoinfo' class='info' aria-label='CNPJ' style='float:left;'>
										<?php SwitchBox('tipodocumento','CPF','CNPJ'); ?>
									</span>
								</div>
								<div class='camporecibo'><input class='inputrecibo' type='text' placeholder='CPF/CNPJ' name='documento' id='documento' value='' maxlength='18'></input></div>
								<p class='charcountwrap'><span>0</span>/18</p>
								<script>
									$('#tipodocumentoinfo').attr('aria-label', (tipodocumento=='CPF') ? 'CPF' : 'CNPJ');
									tipodocumento = 'CNPJ';
									$('#tipodocumento').prop('checked', false);
									$('#tipodocumento').on('change',function() {
										if ($(this).prop('checked')) {
											tipodocumento = 'CPF';
										} else {
											tipodocumento = 'CNPJ';
										}
										$('#tipodocumento').prop('checked', (tipodocumento=='CPF') ? true : false);
										$('#tipodocumentoinfo').attr('aria-label', (tipodocumento=='CPF') ? 'CPF' : 'CNPJ');
									});
								</script>
							</div>

							<script>
								$('.inputrecibo').each(function(index, value) {
									$(this).parent().siblings('.charcountwrap').find('span').html($(this).val().length);
								});
								// conta caracteres
								$('.inputrecibo').on('keyup',function() {
								        $(this).parent().siblings('.charcountwrap').find('span').html($(this).val().length);
									if ($(this).hasClass('bordarosa')) {
										$(this).removeClass('bordarosa');
									}
								});
								// conta caracteres
							</script>

						</div>

						<div id='criarreceibo' class='cardslot' style='padding:1.8% 8%;padding-top:1.2%;'><p class='grande'>criar recibo</p></div>

						<script>
							$('#criarreceibo').on('click', function() {
								campos = 0;
								$('.inputrecibo').each(function(index, value) {
									if ($(this).val().length===0) {
										campos++;
									}
								});

								if (campos===0) {
									window.open(
										'<?php echo $dominio ?>/painel/cobrancas/recibo/pdf/?numero='+($('#numero').val()||'01/01')+'&importancia='+($('#valor').val()||0)+'&pagador='+($('#pagador').val()||0)+'&endereco='+($('#endereco').val()||0)+'&referente='+($('#referente').val()||0)+'&cidade='+($('#cidade').val()||'')+'&datarecibo='+($('#datarecibo').val()||'<?php echo $agora->format('d').' de '.strftime('%B', strtotime($agora->format('Y-m-d'))).' de '.$agora->format('Y') ?>')+'&emitente='+($('#emitente').val()||'')+'&documento='+($('#documento').val()||0)+'&tipodocumento='+tipodocumento,
										'_blank'
									);
									window.location.reload(false);
								} else {
									window.scrollTo(0,0);
									$('#retorno').html('Preencha todos os campos');
									$('.inputrecibo').each(function(index, value) {
										if ($(this).val().length===0) {
											$(this).addClass('bordarosa');
										} else {
											if ($(this).hasClass('bordarosa')) {
												$(this).removeClass('bordarosa');
											}
										}
									});
								}
							});
						</script>
					<!-- container -->
					</div>

                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
