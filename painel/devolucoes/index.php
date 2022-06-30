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
					tituloPagina('devoluções');
				?>

				<div id='adicionarwrap' style='min-width:100%;max-width:100%;display:inline-block;'>
					<?php BotaoPainel('adicionar devolução','adicionardevolucao','devolucoes/novo'); ?>
				</div>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<?php
						$devolucoes = [];
						$alugueisativos = new ConsultaDatabase($uid);
						$alugueisativos = $alugueisativos->ListaAlugueis();
						if ($alugueisativos[0]['aid']!=0) {
							foreach ($alugueisativos as $aluguelativo) {
								$devolucao = new ConsultaDatabase($uid);
								$devolucao = $devolucao->Devolucao($aluguelativo['aid']);
								if ($devolucao['deid']!=0) {
									$devolucoes[] = $aluguelativo;
								} // devolucao == 0
							} // foreach
						} // ativos > 0

						$filtro = new Conforto($uid);
						$filtro = $filtro->Exibicao($devolucoes);
						echo $filtro['botoes'];

						if ($filtro['i']>0) {
							echo "
							<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
								<!-- container -->
						                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							";
										$paginas = new Conforto($uid);
										$paginas = $paginas->Paginacao($filtro['itens']);
										foreach ($paginas['itens'] as $aluguel) {
											$devolucao = new ConsultaDatabase($uid);
											$devolucao = $devolucao->Devolucao($aluguel['aid']);
											if ($devolucao['deid']!=0) {
												$veiculo = new ConsultaDatabase($uid);
												$veiculo = $veiculo->Veiculo($aluguel['vid']);

												$locatario = new ConsultaDatabase($uid);
												$locatario = $locatario->LocatarioInfo($aluguel['lid']);

		                                                                                $categoria = new ConsultaDatabase($uid);
		                                                                                $categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

												$dia = new DateTime($aluguel['data']);
												$inicio = new DateTime($aluguel['inicio']);
												$devolucao = new DateTime($devolucao['data']);

												$reserva = new ConsultaDatabase($uid);
												$reserva = $reserva->ReservaDevolvida($aluguel['aid']);
												if ($reserva['aid']!=0) {
													$ativa = new ConsultaDatabase($uid);
													$ativa = $ativa->Ativacao($reserva['reid']);
													if ($ativa['ativa']=='S') {
														$inicio = new DateTime($reserva['inicio']);
													} // ativa
												} // reserva

												$diarias = new Conforto($uid);
												$diarias = $diarias->TotalDiarias($inicio,$devolucao);

												echo "
													<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap'>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Locatário:</b></p>
																<p class='infoslotrelatorio'>".$locatario['nome']."</p>
																<p class='headerslotrelatorio'><b>Data de registro:</b></p>
																<p class='infoslotrelatorio'>".$dia->format('d/m/Y')." às ".$dia->format('H')."h".$dia->format('i')."</p>
															</div>
														</div>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Modelo:</b></p>
																<p class='infoslotrelatorio'>".$veiculo['modelo']."</p>
																<p class='headerslotrelatorio'><b>Placa:</b></p>
																<p class='infoslotrelatorio'>".$veiculo['placa']."</p>
																<p class='headerslotrelatorio'><b>Kilometragem:</b></p>
																<p class='infoslotrelatorio'>".Kilometragem($aluguel['kilometragem'])."</p>
															</div>
														</div>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Data de início:</b></p>
																<p class='infoslotrelatorio'>".$inicio->format('d/m/Y')." às ".$inicio->format('H')."h</p>
																<p class='headerslotrelatorio'><b>Data de devolução:</b></p>
																<p class='infoslotrelatorio'>".$devolucao->format('d/m/Y')." às ".$devolucao->format('H')."h</p>
															</div>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Diárias:</b></p>
																<p class='infoslotrelatorio'>".$diarias." x ".Dinheiro($aluguel['diaria'])."</p>
															</div>
														</div>
													</div>
												";
											} // deid 0
										} // foreach veiculos

								echo "
								</div>
								<!-- container -->
								".$paginas['botoes']."
								</div>
								";
							} else {
								NenhumRegistro();
							}//i>0
					?>
					<script>
						$('.relatoriowrap').on('click', function() {
							aid = $(this).attr('id').split('_')[1];
							if ($(this).hasClass('ativo')) {
								alativo = 1;
							} else {
								alativo = 0;
							}
							aluguelFundamental(aid,alativo);
						});
					</script>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
