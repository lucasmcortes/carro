<?php
	require_once __DIR__.'/../../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);

		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->AdminCategoria($adminivel['nivel']);

                $listaadmin = new ConsultaDatabase($uid);
                $listaadmin = $listaadmin->ListaAdmin();

		if ( (isset($_GET['v'])) && (is_numeric($_GET['v'])) ) {
			$vid = $_GET['v'];
			$veiculo = new ConsultaDatabase($uid);
			$veiculo = $veiculo->Veiculo($vid);
			$categoria = new ConsultaDatabase($uid);
			$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);
		} else {
			redirectToLogin();
		} // isset veiculo

		$permissao = new Conforto($uid);
		$permissao = $permissao->Permissao('modificacao');
		if ($permissao!==true) {
			redirectToLogin();
		} // permitido

	} else {
		redirectToLogin();
	} // isset uid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina('editar '.$veiculo['modelo']);

					//Icone('relatorioveiculo','relatório','relatorioicon');
					//Icone('vercalendario','calendário','calendarioicon');
					Icone('verdoc','documento','verdocicon');
					Icone('verfoto','foto','verfotoicon');
					Icone('removeveiculo','status de operação','removeveiculoicon');
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:0 auto;'>
						<!-- container -->
				                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							<!-- items -->
							<div class="items">
								<div style='text-align:center;margin:0 auto;'>
									<?php
										$revisao_dez_mil_km = new Conforto($uid);
										$revisao_dez_mil_km = $revisao_dez_mil_km->RevisaoDezKm($veiculo['vid']);
										if ($revisao_dez_mil_km!=0) {
											echo "<div style='min-width:100%;max-width:100%:display:inline-block;'>";
											echo $revisao_dez_mil_km;
											echo "</div>";
										} // fazer revisão dos 10k
									?>
								</div>

								<div id='modelowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Modelo</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='text' placeholder='Modelo' name='modelo' id='modelo'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='marcawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Marca</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='text' placeholder='Marca' name='marca' id='marca'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='potenciawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			                                                <label>Potência do motor</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<select id='potencia' class='wrappedinput'>
					                                                        <option value=''>-- ESCOLHA --</option>
					                                                        <option value='10'>1.0</option>
					                                                        <option value='11'>1.1</option>
					                                                        <option value='12'>1.2</option>
					                                                        <option value='13'>1.3</option>
					                                                        <option value='14'>1.4</option>
					                                                        <option value='15'>1.5</option>
					                                                        <option value='16'>1.6</option>
					                                                        <option value='17'>1.7</option>
					                                                        <option value='18'>1.8</option>
					                                                        <option value='19'>1.9</option>
					                                                        <option value='20'>2.0</option>
					                                                        <option value='21'>2.1</option>
					                                                        <option value='22'>2.2</option>
					                                                        <option value='23'>2.3</option>
					                                                        <option value='24'>2.4</option>
					                                                        <option value='25'>2.5</option>
					                                                </select>
											<div class='posinput'></div>
										</div>
									</div>
			                                        </div>

								<div id='categoriawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Categoria</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
						                                        <div id='categorias' class='wrappedinput lista'>
												<p id='cat_1' class='categorias opcoes'>
						                                                        Carro
						                                                </p>
						                                                <p id='cat_2' class='categorias opcoes'>
						                                                        Utilitário
						                                                </p>
						                                                <p id='cat_3' class='categorias opcoes'>
						                                                        Moto
						                                                </p>
						                                        </div>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

			                                        <div id='portaswrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			                                                <label>Portas</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<div id='portas' class='wrappedinput swap'>
												<span id='portasinfo' class='info' aria-label='Duas' style='float:left;'>
													<?php SwitchBox('portasswitch','4','2'); ?>
							                			</span>
											</div>
											<div class='posinput'></div>
										</div>
									</div>
			                                        </div>
					                        <script>
					                                $('#portasswitch').prop('checked', <?php echo ($veiculo['portas']==2) ? 'false' : (($veiculo['portas']==2) ? 'false' : 'true') ?>);
									$('#portasinfo').attr('aria-label', ($('#portasswitch:checked').length!=0) ? 'Quatro' : 'Duas');
					                                $('#portasswitch').on('change',function() {
					                                        if (this.checked) {
					                                                velemento = 4;
					                                        } else {
					                                                velemento = 2;
					                                        }
										$(this).closest('.info').attr('aria-label', ($(this).is(':checked')) ? 'Quatro' : 'Duas');
					                                });
					                        </script>

								<div id='completowrap' style='min-width:100%;max-width:100%;margin:3px auto;margin-bottom:7px;display:inline-block;'>
					        			<label>Completo</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<div id='completo' class='wrappedinput swap'>
							                			<span id='completoinfo' class='info' aria-label='Não' style='float:left;'>
							                                                <?php SwitchBox('completoswitch','Sim','Não'); ?>
							                			</span>
											</div>
											<div class='posinput'></div>
										</div>
									</div>
					                        </div>
					                        <script>
					                                $('#completoswitch').prop('checked', <?php echo ($veiculo['completo']=='S') ? 'true' : 'false'; ?>);
									$('#completoinfo').attr('aria-label', ($('#completoswitch:checked').length!=0) ? 'Sim' : 'Não');
					                                $('#completoswitch').on('change',function() {
					                                        if (this.checked) {
					                                                velemento = 'S';
					                                        } else {
					                                                velemento = 'N';
					                                        }
										$(this).closest('.info').attr('aria-label', ($(this).is(':checked')) ? 'Sim' : 'Não');
					                                });
					                        </script>

								<div id='limpezawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
				                			<label>Limpeza</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<div id='limpeza' class='wrappedinput swap'>
						                        			<span id='limpezainfo' class='info' aria-label='Lavar' style='float:left;'>
						                                                        <?php SwitchBox('limpezaswitch','Limpo','Lavar'); ?>
						                        			</span>
											</div>
											<div class='posinput'></div>
										</div>
									</div>
				                                </div>
				                                <script>
					                                $('#limpezaswitch').prop('checked', <?php echo ($veiculo['limpeza']=='S') ? 'true' : 'false'; ?>);
									$('#limpezainfo').attr('aria-label', ($('#limpezaswitch:checked').length!=0) ? 'Limpo' : 'Lavar');
				                                        $('#limpezaswitch').on('change',function() {
				                                                if (this.checked) {
				                                                        velemento = 'S';
				                                                } else {
				                                                        velemento = 'N';
				                                                }
										$(this).closest('.info').attr('aria-label', ($(this).is(':checked')) ? 'Limpo' : 'Lavar');
				                                        });
				                                </script>

								<div id='corwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Cor</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='text' placeholder='Cor' name='cor' id='cor'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='anowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Ano</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='number' placeholder='Ano' maxlength='4' name='ano' id='ano'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='placawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Placa</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='text' placeholder='Placa' maxlength='8' name='placa' id='placa'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='chassiwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Chassi</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='text' placeholder='Chassi' maxlength='17' name='chassi' id='chassi'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='renavamwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Renavam</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='number' placeholder='Renavam' maxlength='11' name='renavam' id='renavam'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='kmwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Kilometragem</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='number' placeholder='Kilometragem' name='kilometragem' id='kilometragem'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='revisaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Múltiplo da kilometragem para notificar revisão</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<input class='wrappedinput' type='number' placeholder='Ex.: 10000' name='revisao' id='revisao'></input>
											<div class='posinput'></div>
										</div>
									</div>
								</div>

								<div id='observacaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
									<label>Observação</label>
									<div class='inputouterwrap'>
										<div class='inputwrap'>
											<div class='preinput normal'></div>
											<textarea class='wrappedinput' id='observacao' rows='5' style='height:120px;'><?php echo $veiculo['observacao'] ?></textarea>
											<div class='posinput'></div>
										</div>
									</div>
								</div> <!-- observacaowrap -->

								<div id='senhawrap' style='min-width:100%;max-width:100%;margin:3px auto;display:none;'>
									<?php InputGeral('Senha', 'pwd', 'pwd', 'password', '100'); ?>
								</div>

							</div>
							<!-- items -->
						</div>
						<!-- container -->
					</div>
				</div>
	        	</div>
		</div>
		<!-- conteudo -->

		<script>
			$('#relatorioveiculo').on('click',function () {
				window.location.href='<?php echo $dominio ?>/painel/veiculos/relatorio/?v=<?php echo $veiculo['vid'] ?>';
			});

			$('#verdoc').on('click',function () {
				verDoc(<?php echo $veiculo['vid'] ?>);
			});

			$('#verfoto').on('click',function () {
				verFoto(<?php echo $veiculo['vid'] ?>);
			});

			$('#removeveiculo').on('click',function () {
				$.ajax({
					type: 'POST',
					url: '<?php echo $dominio ?>/painel/veiculos/editar/includes/removerveiculopopup.inc.php',
					data: {
						veiculo: <?php echo $vid ?>
					},
					beforeSend: function() {
						/* loadVestimenta('<?php echo $dominio ?>/includes/carregandovestimenta.inc.php'); */
					},
					success: function(modificacao) {
						$('#vestimenta').html(modificacao);
					}
				});
			});

			$('#vercalendario').on('click',function () {
				calendarioPop(3,'vestimenta',<?php echo $veiculo['vid'] ?>);
			});

			$('#modelo').val('<?php echo $veiculo['modelo'] ?>');
			$('#marca').val('<?php echo $veiculo['marca'] ?>');
			$('#potencia').val('<?php echo $veiculo['potencia'] ?>');
			$('.categorias[id=cat_<?php echo $veiculo['categoria'] ?>]').addClass('selecionada');
			$('#cor').val('<?php echo $veiculo['cor'] ?>');
			$('#ano').val('<?php echo $veiculo['ano'] ?>');
			$('#placa').val('<?php echo $veiculo['placa'] ?>');
			$('#chassi').val('<?php echo mb_strtoupper($veiculo['chassi']) ?>');
			$('#renavam').val('<?php echo $veiculo['renavam'] ?>');
			$('#kilometragem').val('<?php echo $veiculo['km'] ?>');

			<?php
				if ($veiculo['categoria']!=3) {
					// carro e utilitario
					if ( ($veiculo['km']>$configuracoes['rev_car_prev']) && ($veiculo['km']<$configuracoes['rev_car_limiar']) ) {
						// revisa a cada 10k
						$multiplo = $configuracoes['rev_car_prev'];
					} else if ($veiculo['km']>=$configuracoes['rev_car_limiar']) {
						// revisa a cada 7k
						$multiplo = $configuracoes['rev_car_apos'];
					} else {
						$multiplo = 0;
					} // km > 10000
				} else if ($veiculo['categoria']==3) {
					// moto
					// revisa a cada 1k
					$multiplo = $configuracoes['rev_moto'];
				} // qual categoria
				($veiculo['revisao']==0) ? $multiplo = $multiplo : $multiplo = $veiculo['revisao'];
				($multiplo==0) ? $multiplo = '' : $multiplo = $multiplo;
			?>
			$('#revisao').val('<?php echo $multiplo ?>');

			velemento = 0;
			$('.opcoes').on('click', function() {
				velemento = $(this).attr('id').split('_')[1];
				listadeopcoes = $(this).parent().attr('id');
				if ($(this).hasClass('selecionada')) {
					$('#'+listadeopcoes).children('.opcoes').removeClass('selecionada');
					velemento = 0;
					return;
				}
				$('#'+listadeopcoes).children('.opcoes').removeClass('selecionada');
				$(this).addClass('selecionada');
			});

			$('.posinput').on('click', function() {
				elemento = $(this).siblings('.wrappedinput').attr('id');
				if ($('#'+elemento).hasClass('lista') || $('#'+elemento).hasClass('swap')) {
					velemento = velemento;
				} else {
					velemento = $('#'+elemento).val();
				}
				$.ajax({
					type: 'POST',
					url: '<?php echo $dominio ?>/painel/veiculos/includes/v'+elemento+'mod.inc.php',
					data: {
						veiculo: '<?php echo $veiculo['vid'] ?>',
						modificacao: velemento
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
							$('#bannerfooter').css('display','none');
						}
					}
				});
			});
		</script>

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
