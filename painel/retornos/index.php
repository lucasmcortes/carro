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
					tituloPagina('retornos');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('addmanutencao','criar manutenção','addmanutencaoicon');
						Icone('vermanutencoes','manutenções atuais','manutencoesicon');
						Icone('addretorno','criar retorno','disponibilizaricon');
						Icone('verdespesas','ver despesas','despesasicon');
					echo "</div>";
					echo "
						<script>
							$('#addmanutencao').on('click',function () {
								window.location.href='".$dominio."/painel/manutencoes/novo';
							});
							$('#vermanutencoes').on('click',function () {
								window.location.href='".$dominio."/painel/manutencoes';
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
									$r=0;
									$listaretornos = new ConsultaDatabase($uid);
									$listaretornos = $listaretornos->ListaRetornos();
									if ($listaretornos[0]['rid']!=0) {
										$r++;
									} // retornos > 0

									if ($r>0) {
										echo "
										<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
											<!-- container -->
									                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
										";
												$paginas = new Conforto($uid);
												$paginas = $paginas->Paginacao($listaretornos);
												foreach ($paginas['itens'] as $retorno) {
													$kilometragem = new ConsultaDatabase($uid);
													$kilometragem = $kilometragem->KilometragemData($retorno['data']);
													$manutencao = new ConsultaDatabase($uid);
													$manutencao = $manutencao->Manutencao($retorno['mid']);
													$veiculo = new ConsultaDatabase($uid);
													$veiculo = $veiculo->Veiculo($manutencao['vid']);
													$dia = new DateTime($retorno['data']);
													$motivo = new ConsultaDatabase($uid);
													$motivo = $motivo->VeiculoMotivo($manutencao['motivo']);

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
														<div id='retornowrap_".$retorno['mid']."' class='relatoriowrap'>
															<div class='slotrelatoriowrap'>
																<div class='slotrelatorio'>
																	<p class='headerslotrelatorio'><b>Modelo:</b></p>
																	<p class='infoslotrelatorio'>".$veiculo['modelo']."</p>
																	<p class='headerslotrelatorio'><b>Placa:</b></p>
																	<p class='infoslotrelatorio'>".$veiculo['placa']."</p>
																	<p class='headerslotrelatorio'><b>Motivo:</b></p>
																	<p class='infoslotrelatorio'>".$motivo."</p>
																</div>
															</div>
															<div class='slotrelatoriowrap'>
																<div class='slotrelatorio'>
																	<p class='headerslotrelatorio'><b>Estabelecimento:</b></p>
																	<p class='infoslotrelatorio'>".$manutencao['estabelecimento']."</p>
																	<p class='headerslotrelatorio'><b>Kilometragem:</b></p>
																	<p class='infoslotrelatorio'>".Kilometragem($kilometragem['km'])."</p>
																	<p class='headerslotrelatorio'><b>Observação:</b></p>
																	<p class='infoslotrelatorio'>".$retorno['observacao']."</p>
																</div>
															</div>
															<div class='slotrelatoriowrap'>
																<div class='slotrelatorio'>
																	<p class='headerslotrelatorio'><b>Data de início:</b></p>
																	<p class='infoslotrelatorio'>".$data_inicio->format('d/m/y')." às ".$data_inicio->format('H')."h</p>
																	<p class='headerslotrelatorio'><b>Data de retorno:</b></p>
																	<p class='infoslotrelatorio'>".$dia->format('d/m/y')." às ".$dia->format('H')."h</p>
																	<p class='headerslotrelatorio'><b>Custo total:</b></p>
																	<p class='infoslotrelatorio'>".Dinheiro($retorno['valor'])."</p>
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
									}// r>0
								?>

						<script>
							$('.relatoriowrap').on('click', function() {
								rid = $(this).attr('id').split('_')[1];
								retornoFundamental(rid);
							});
						</script>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
