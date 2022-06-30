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
					tituloPagina('reservas canceladas');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('addaluguel','criar reserva','addaluguelicon');
						Icone('reshoje','reservas para hoje','reservasicon');
						Icone('reservasanteriores','reservas anteriores','reservasanterioresicon');
						Icone('reservasfuturas','reservas futuras','reservasfuturasicon');
					echo "</div>";
					echo "
						<script>
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#reshoje').on('click',function () {
								window.location.href='".$dominio."/painel/reservas';
							});
							$('#reservasfuturas').on('click',function () {
								window.location.href='".$dominio."/painel/reservas/futuras';
							});
							$('#reservasanteriores').on('click',function () {
								window.location.href='".$dominio."/painel/reservas/anteriores';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>

					<?php
						require_once __DIR__.'/../includes/listareservas.inc.php';

						$filtro = new Conforto($uid);
						$filtro = $filtro->Exibicao($reservas_canceladas);
						echo $filtro['botoes'];

						if ($filtro['i']>0) {
							echo "
								<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
								<!-- container -->
								<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							";

							$paginas = new Conforto($uid);
							$paginas = $paginas->Paginacao($filtro['itens']);
							foreach ($paginas['itens'] as $cancelada) {
								$aluguel = new ConsultaDatabase($uid);
								$aluguel = $aluguel->AluguelInfo($cancelada['aid']);

								$ativa = new ConsultaDatabase($uid);
								$ativa = $ativa->Ativacao($cancelada['reid']);
								$data_cancelamento = new DateTime($ativa['data']);

								$inicio = new DateTime($cancelada['inicio']);
								$devolucao = new DateTime($cancelada['devolucao']);
								$dia = new DateTime($cancelada['data']);

								$locatario = new ConsultaDatabase($uid);
								$locatario = $locatario->LocatarioInfo($aluguel['lid']);

								$veiculo = new ConsultaDatabase($uid);
								$veiculo = $veiculo->Veiculo($aluguel['vid']);

								$diarias = new Conforto($uid);
								$diarias = $diarias->TotalDiarias($inicio,$devolucao);

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
											</div>
										</div>
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
												<p class='headerslotrelatorio'><b>Data de registro:</b></p>
												<p class='infoslotrelatorio'>".$dia->format('d/m/Y')." às ".$dia->format('H')."h".$dia->format('i')."</p>
												<p class='headerslotrelatorio'><b>Data de cancelamento:</b></p>
												<p class='infoslotrelatorio'>".$data_cancelamento->format('d/m/Y')." às ".$data_cancelamento->format('H')."h</p>
											</div>
										</div>
									</div>
								";
							} // hoje

							echo "
								</div>
								<!-- container -->
								".$paginas['botoes']."
								</div>
							";
						} else {
							NenhumRegistro();
						}// reservas canceladas
					?>

					<script>
						$('.relatoriowrap').on('click', function() {
							aid = $(this).attr('id').split('_')[1];
							if ($(this).hasClass('ativa')) {
								resativa = 1;
							} else {
								resativa = 0;
							}
							reservaFundamental(aid, resativa);
						});
					</script>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
