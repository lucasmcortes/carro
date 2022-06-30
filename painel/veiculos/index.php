<?php
	require_once __DIR__.'/../../cabecalho.php';

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
					tituloPagina('veículos');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('addveiculo','adicionar veículo','addveiculoicon');
					Icone('addaluguel','adicionar aluguel','addaluguelicon');
					Icone('veiculosremovidos','veículos removidos','removeveiculoicon');
					echo "</div>";
					echo "
						<script>
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#addveiculo').on('click',function () {
								window.location.href='".$dominio."/painel/veiculos/novo';
							});
							$('#veiculosremovidos').on('click',function () {
								window.location.href='".$dominio."/painel/veiculos/removidos';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
						<!-- veiculos container -->
				                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:overlay;'>
								<?php
									$listaveiculos = new ConsultaDatabase($uid);
									$listaveiculos = $listaveiculos->ListaVeiculos();

									foreach ($listaveiculos as $veiculos) {
										$veiculo = new ConsultaDatabase($uid);
										$veiculo = $veiculo->Veiculo($veiculos['vid']);
										if ($veiculo['ativo']=='S') {
											$categoria = new ConsultaDatabase($uid);
											$categoria = $categoria->VeiculoCategoria($veiculos['categoria']);

											$disponibilidade_veiculo = new Conforto($uid);
						                                      	$disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($veiculo['vid']);

											// atualiza o array de disponibilidade tirando as datas de antes de hoje
											if (key($disponibilidade_veiculo['disponibilidade'])>=0) {
												// atualiza o array de disponibilidade tirando as datas de antes de hoje
												while ($disponibilidade_veiculo['disponibilidade'][key($disponibilidade_veiculo['disponibilidade'])]<$agora->format('Y-m-d')) {
													unset($disponibilidade_veiculo['disponibilidade'][key($disponibilidade_veiculo['disponibilidade'])]);
												} // while
											}

						                                       	$disponibilidade = $disponibilidade_veiculo['disponibilidade'][$agora->format('Y-m-d')]['status']??$disponibilidade_veiculo['status'];

											$revisao_dez_mil_km = new Conforto($uid);
											$revisao_dez_mil_km = $revisao_dez_mil_km->RevisaoDezKm($veiculo['vid']);
											($revisao_dez_mil_km==0) ? $revisao_dez_mil_km = 'Revisão em dia' : $revisao_dez_mil_km = '<b>Fazer revisão</b>';

											($veiculo['observacao']=='') ? $observacao = 'Ok' : $observacao = $veiculo['observacao'];
											($veiculo['limpeza']=='S') ? $iconelimpeza = 'limpoicon' : $iconelimpeza = 'lavaricon';
											($iconelimpeza=='limpoicon') ? $arialimpeza = 'limpo' : $arialimpeza = 'lavar';

											if ($veiculo['ativo']=='S') {
												$ativo = 'Ativo';

												if ( ($disponibilidade=='Oficina') || ($disponibilidade=='Pintura') || ($disponibilidade=='Revisão') || ($disponibilidade=='Lavando') ) {
													$corespecial = "style='background-color:var(--rosa);color:var(--branco);'";
												} else if ($disponibilidade=='Alugado') {
													$corespecial = "style='background-color:var(--azul);color:var(--preto);'";
												} else {
													$corespecial = '';
												}
											} else if ($veiculo['ativo']=='N') {
												$ativo = 'Removido';

												$corespecial = "style='background-color:var(--bege);color:var(--preto);'";
											} // bg se tá ativo

											echo "
												<div id='veiculowrap_".$veiculo['vid']."' class='relatoriowrap' ".$corespecial.">
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Modelo:</b></p>
															<p class='infoslotrelatorio'>".$veiculo['modelo']."</p>
															<p class='headerslotrelatorio'><b>Placa:</b></p>
															<p class='infoslotrelatorio'>".$veiculo['placa']."</p>
															<p class='headerslotrelatorio'><b>Ano:</b></p>
															<p class='infoslotrelatorio'>".$veiculo['ano']."</p>
															<p class='headerslotrelatorio'><b>Cor:</b></p>
															<p class='infoslotrelatorio'>".$veiculo['cor']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Kilometragem:</b></p>
															<p class='infoslotrelatorio'>".Kilometragem($veiculo['km'])."</p>
															<p class='headerslotrelatorio'><b>Categoria:</b></p>
															<p class='infoslotrelatorio'>".$categoria."</p>
															<p class='headerslotrelatorio'><b>Disponibilidade:</b></p>
															<p class='infoslotrelatorio'>".$disponibilidade."</p>
															<p class='headerslotrelatorio'><b>Limpeza:</b></p>
															<p class='infoslotrelatorio'>".ucfirst($arialimpeza)."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Observação:</b></p>
															<p class='infoslotrelatorio'>".mb_strimwidth($observacao, 0, 36, '[...]')."</p>
															<p class='headerslotrelatorio'><b>Revisão:</b></p>
															<p class='infoslotrelatorio'>".$revisao_dez_mil_km."</p>
															<p class='headerslotrelatorio'><b>Status:</b></p>
															<p class='infoslotrelatorio'>".$ativo."</p>
														</div>
													</div>
												</div>
											";
											//Icone($veiculo['vid']."_iconelimpeza",$arialimpeza,$iconelimpeza);
										} // ativos
									} // foreach veiculos
								?>
						</div>
						<!-- veiculos container -->

						<script>
						$('.relatoriowrap').on('click', function() {
							vid = $(this).attr('id').split('_')[1];
							veiculoFundamental(vid);
						});
						</script>

						<?php
							if ($listaveiculos[0]['vid']==0) {
								VamosComecar();
							} else {
								echo "
									<div id='vercalendariotodoswrap' style='min-width:100%;max-width:100%;display:inline-block;'>
								";
								BotaoPainel('ver calendário','vercalendariotodos','veiculos/calendario');
								echo ";
									</div>
								";
							}
						?>

					</div>
				</div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
