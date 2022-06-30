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
					tituloPagina('manutenções');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('addmanutencao','criar manutenção','addmanutencaoicon');
						Icone('manutencoesanteriores','manutenções anteriores','manutencoesanterioresicon');
						Icone('addretorno','criar retorno','disponibilizaricon');
						Icone('verdespesas','ver despesas','despesasicon');
					echo "</div>";
					echo "
						<script>
							$('#addmanutencao').on('click',function () {
								window.location.href='".$dominio."/painel/manutencoes/novo';
							});
							$('#manutencoesanteriores').on('click',function () {
								window.location.href='".$dominio."/painel/retornos';
							});
							$('#addretorno').on('click',function () {
								window.location.href='".$dominio."/painel/retornos/novo';
							});
							$('#verdespesas').on('click',function () {
								window.location.href='".$dominio."/painel/despesas';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
				<?php
					$m=0;
					$manutencoesAtivas = [];
					$listamanutencoes = new ConsultaDatabase($uid);
					$listamanutencoes = $listamanutencoes->ListaManutencoes();
					foreach ($listamanutencoes as $manutencao) {
						if ($manutencao['mid']!=0) {
							$retornado = new ConsultaDatabase($uid);
							$retornado = $retornado->Retorno($manutencao['mid']);
							if ($retornado['mid']==0) {
								if ($manutencao['ativa']!="N") {
									if ($manutencao['reserva_inicio']!=0) {
										$manutencaoinicio = new DateTime($manutencao['reserva_inicio']);
										if ($manutencaoinicio<=$agora) {
											if ($manutencao['confirmada']==1) {
												$m++;
												$manutencoesAtivas[] = $manutencao;
											} // confirmada
										} else {
											$m++;
											$manutencoesAtivas[] = $manutencao;
										} // já começou
									} else {
										$m++;
										$manutencoesAtivas[] = $manutencao;
									} // se é reserva
								}
							} // mid > 0
						} // mid >0
					} // foreach veiculo

					if (count($manutencoesAtivas)>0) {
						echo "
						<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
							<!-- container -->
					                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
						";
										$paginas = new Conforto($uid);
										$paginas = $paginas->Paginacao($manutencoesAtivas);
										foreach ($paginas['itens'] as $manutencao) {
											$veiculo = new ConsultaDatabase($uid);
											$veiculo = $veiculo->Veiculo($manutencao['vid']);
											$motivo = new ConsultaDatabase($uid);
											$motivo = $motivo->VeiculoManutencao($manutencao['motivo']);
											$agendamento = new DateTime($manutencao['inicio']);
											$dia = new DateTime($manutencao['data']);

											$data_inicio = new DateTime($manutencao['inicio']);
											$data_devolucao = new DateTime($manutencao['devolucao']);

											$retorno = new ConsultaDatabase($uid);
											$retorno = $retorno->Retorno($manutencao['mid']);
											if ($retorno['rid']!=0) {
												$data_devolucao = new DateTime($retorno['data']);

												$reservamanutencao = new ConsultaDatabase($uid);
												$reservamanutencao = $reservamanutencao->ManutencaoReserva($manutencao['mid']);
												if ($reservamanutencao['mreid']!=0) {
													if ($reservamanutencao['confirmada']==1) {
														$data_inicio = new DateTime($reservamanutencao['inicio']);
														$data_devolucao = new DateTime($reservamanutencao['devolucao']);
													} // se confirmou
												} // se foi agendamento
											} // retorno > 0

											echo "
												<div id='manutencaowrap_".$manutencao['mid']."' class='relatoriowrap'>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Modelo:</b></p>
															<p class='infoslotrelatorio'>".$veiculo['modelo']."</p>
															<p class='headerslotrelatorio'><b>Placa:</b></p>
															<p class='infoslotrelatorio'>".$veiculo['placa']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Estabelecimento:</b></p>
															<p class='infoslotrelatorio'>".$manutencao['estabelecimento']."</p>
															<p class='headerslotrelatorio'><b>Motivo:</b></p>
															<p class='infoslotrelatorio'>".$motivo."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Data de início:</b></p>
															<p class='infoslotrelatorio'>".$data_inicio->format('d/m/y')." às ".$data_inicio->format('H')."h</p>
															<p class='headerslotrelatorio'><b>Data de previsão de retorno:</b></p>
															<p class='infoslotrelatorio'>".$data_devolucao->format('d/m/y')." às ".$data_devolucao->format('H')."h</p>
														</div>
													</div>
												</div>
											";
										} // foreach veiculos

							echo "
							</div>
							<!-- container -->
							".$paginas['botoes']."
						</div>
							";
						} else {
							NenhumRegistro();
						}// i>0
					?>

						<script>
						$('.relatoriowrap').on('click', function() {
							mid = $(this).attr('id').split('_')[1];
							manutencaoFundamental(mid);
						});
						</script>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
