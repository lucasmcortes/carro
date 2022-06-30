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
					tituloPagina('aluguéis anteriores');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('addaluguel','adicionar aluguel','addaluguelicon');
					Icone('buscaaluguel','buscar aluguel','buscaaluguelicon');
					Icone('alatuais','aluguéis atuais','alugueisicon');
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
							$('#alatuais').on('click',function () {
								window.location.href='".$dominio."/painel/alugueis';
							});
							$('#verreservas').on('click',function () {
								window.location.href='".$dominio."/painel/reservas/futuras';
							});
						</script>
					";
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<?php
						require_once __DIR__.'/../includes/listaalugueis.inc.php';

						$filtro = new Conforto($uid);
						$filtro = $filtro->Exibicao($alugueis_anteriores);
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
										$aid = $aluguel['aid'];
										$aluguel = new ConsultaDatabase($uid);
										$aluguel = $aluguel->AluguelInfo($aid);

										$veiculo = new ConsultaDatabase($uid);
										$veiculo = $veiculo->Veiculo($aluguel['vid']);

										$locatario = new ConsultaDatabase($uid);
										$locatario = $locatario->LocatarioInfo($aluguel['lid']);

										($veiculo['limpeza']=='S') ? $limpeza = 'Limpo' : $limpeza = 'Lavar';
                                                                                $categoria = new ConsultaDatabase($uid);
                                                                                $categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

										$comeco = new DateTime($aluguel['inicio']);
										$conclusao = new DateTime($aluguel['devolucao']);

										$dia = new DateTime($aluguel['data']);
										$inicio_data = new DateTime($aluguel['inicio']);
										$devolucao_data = new DateTime($aluguel['devolucao']);

										$reserva = new ConsultaDatabase($uid);
										$reserva = $reserva->ReservaDevolvida($aluguel['aid']);
										if ($reserva['reid']!=0) {
											$atividade = new ConsultaDatabase($uid);
											$atividade = $atividade->Ativacao($reserva['reid']);
											if ($atividade['ativa']=='S') {
												$inicio_data = new DateTime($reserva['inicio']);
												$devolucao_data = new DateTime($reserva['devolucao']);
											} // ativa
										} // reserva

										$devolucao = new ConsultaDatabase($uid);
										$devolucao = $devolucao->Devolucao($aluguel['aid']);
										if ($devolucao['deid']!=0) {
											$devolucao_data = new DateTime($devolucao['data']);
										} // foi devolvido

										$diarias = new Conforto($uid);
										$diarias = $diarias->TotalDiarias($inicio_data,$devolucao_data);

										$contrato_numero = new Conforto($uid);
										$contrato_numero = $contrato_numero->NumeroContrato($aluguel['aid']);

										echo "
											<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap'>
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
														<p class='infoslotrelatorio'>".$inicio_data->format('d/m/Y')." às ".$inicio_data->format('H')."h</p>
														<p class='headerslotrelatorio'><b>Data de devolução:</b></p>
														<p class='infoslotrelatorio'>".$devolucao_data->format('d/m/Y')." às ".$devolucao_data->format('H')."h</p>
													</div>
													<div class='slotrelatorio'>
														<p class='headerslotrelatorio'><b>Diárias:</b></p>
														<p class='infoslotrelatorio'>".$diarias." x ".Dinheiro($aluguel['diaria'])."</p>
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
       						} // aan (anteriores) > 0
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
	require_once __DIR__.'/../../../rodape.php';
?>
