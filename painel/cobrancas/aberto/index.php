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
					tituloPagina('cobranças em aberto');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('buscalocatario','buscar por locatário','buscalocatarioicon');
						Icone('vertodascobrancas','todas as faturas','verfaturaicon');
					echo "</div>";
					echo "
						<script>
							$('#buscalocatario').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas/locatario';
							});
							$('#vertodascobrancas').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
						<!-- container -->
						<?php
							$cobrancasEmAberto = [];
							$listacobrancas = new ConsultaDatabase($uid);
							$listacobrancas = $listacobrancas->ListaCobrancas();
							foreach ($listacobrancas as $cobranca) {
								if ($cobranca['valor']!=0) {
									if ($cobranca['tid']==0) {
										$devolucao = new ConsultaDatabase($uid);
										$devolucao = $devolucao->DevolucaoId($cobranca['deid']);
										$aluguel = new ConsultaDatabase($uid);
										$aluguel = $aluguel->AluguelInfo($devolucao['aid']);

										$parciais = new Conforto($uid);
										$parciais = $parciais->SomaParciais($cobranca['coid']);

										$pagamentosaluguel = new Conforto($uid);
										$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aluguel['aid']);
										if ($cobranca['valor']-$parciais-$pagamentosaluguel>0) {
											$cobrancasEmAberto[] = $cobranca;
										}
									} // em aberto
								} // R$>0
							} // foreach

							$filtro = new Conforto($uid);
							$filtro = $filtro->Exibicao($cobrancasEmAberto);
							echo $filtro['botoes'];

							if ($filtro['i']>0) {
								echo "
						               		<div id='emabertowrap' style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
								";
										$paginas = new Conforto($uid);
										$paginas = $paginas->Paginacao($filtro['itens']);
										foreach ($paginas['itens'] as $cobranca) {
											$residual = 0;
											$locatario = new ConsultaDatabase($uid);
											$locatario = $locatario->LocatarioInfo($cobranca['lid']);
											if ($cobranca['valor']!=0) {
												if ($cobranca['tid']==0) {
													$aluguel = new ConsultaDatabase($uid);
													$aluguel = $aluguel->AluguelInfo($cobranca['aid']);

													$residual = new Conforto($uid);
													$residual = $residual->Residual($cobranca['coid']);

													echo "
														<div id='cobrancawrap_".$cobranca['coid']."' class='relatoriowrap'>
															<div class='slotrelatoriowrap'>
																<div class='slotrelatorio'>
																	<p class='headerslotrelatorio'><b>Locatário:</b></p>
																	<p class='infoslotrelatorio'>".$locatario['nome']."</p>
																</div>
															</div>
															<div class='slotrelatoriowrap'>
																<div class='slotrelatorio'>
																	<p class='headerslotrelatorio'><b>Valor da fatura:</b></p>
																	<p class='infoslotrelatorio'>".Dinheiro($cobranca['valor'])."</p>
																</div>
															</div>
															<div class='slotrelatoriowrap'>
																<div class='slotrelatorio'>
																	<p class='headerslotrelatorio'><b>Status:</b></p>
																	<p class='infoslotrelatorio'>".$residual['status']."".$residual['valor']."</p>
																</div>
															</div>
														</div>
													";
												} // em aberto
											} // R$ > 0
										} // foreach veiculos

								echo "
									</div>
									<!-- container -->
									".$paginas['botoes']."
								";
							} else {
								NenhumRegistro();
							}// i > 0
						?>

						<script>
							$('.relatoriowrap').on('click', function() {
								coid = $(this).attr('id').split('_')[1];
								cobrancaFundamental(coid);
							});
						</script>
					</div>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
