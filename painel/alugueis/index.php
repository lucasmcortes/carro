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
					tituloPagina('aluguéis atuais');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('addaluguel','criar aluguel','addaluguelicon');
					Icone('buscaaluguel','buscar aluguel','buscaaluguelicon');
					Icone('alanteriores','aluguéis anteriores','alugueisanterioresicon');
					Icone('verreservas','reservas','reservasicon');
					echo "</div>";
					echo "
						<script>
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#buscaaluguel').on('click',function () {
								window.location.href='".$dominio."/painel/alugueis/busca';
							});
							$('#alanteriores').on('click',function () {
								window.location.href='".$dominio."/painel/alugueis/anteriores';
							});
							$('#verreservas').on('click',function () {
								window.location.href='".$dominio."/painel/reservas/futuras';
							});
						</script>
					";
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<?php
						require_once __DIR__.'/includes/listaalugueis.inc.php';

						$filtro = new Conforto($uid);
						$filtro = $filtro->Exibicao($alugueis_atuais);
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
											if ($devolucao['deid']==0) {
												$veiculo = new ConsultaDatabase($uid);
												$veiculo = $veiculo->Veiculo($aluguel['vid']);

												$locatario = new ConsultaDatabase($uid);
												$locatario = $locatario->LocatarioInfo($aluguel['lid']);

		                                                                                $categoria = new ConsultaDatabase($uid);
		                                                                                $categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

												$dia = new DateTime($aluguel['data']);
												$inicio = new DateTime($aluguel['inicio']);
												$devolucao = new DateTime($aluguel['devolucao']);

												$reserva = new ConsultaDatabase($uid);
												$reserva = $reserva->Reserva($aluguel['aid']);
												if ($reserva['reid']!=0) {
													$atividade = new ConsultaDatabase($uid);
													$atividade = $atividade->Ativacao($reserva['reid']);
													if ($atividade['ativa']=='S') {
														$inicio = new DateTime($reserva['inicio']);
														$devolucao = new DateTime($reserva['devolucao']);
														if ($reserva['confirmada']==1) {
															$ativo = 'ativo';
														} else {
															$ativo = '';
														}// confirmada
													} else {
														$ativo = '';
													} // ativa
												} else {
													$ativo = 'ativo';
												}// reserva

												$diarias = new Conforto($uid);
												$diarias = $diarias->TotalDiarias($inicio,$devolucao);

												$contrato_numero = new Conforto($uid);
												$contrato_numero = $contrato_numero->NumeroContrato($aluguel['aid']);

												echo "
													<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap ".$ativo."'>
														<div style='min-width:100%;max-width:100%;display:inline-block;'>
															<p class='numregistro'>
																".$contrato_numero."
															</p>
														</div>
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
																<p class='headerslotrelatorio'><b>Previsão de diárias:</b></p>
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
						} //i>0
					?>

					<script>
						$('.relatoriowrap').on('click', function() {
							aid = $(this).attr('id').split('_')[1];
							if ($(this).hasClass('ativo')) {
								valativo = 1;
							} else {
								valativo = 0;
							}
							aluguelFundamental(aid,valativo);
						});
					</script>

                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
