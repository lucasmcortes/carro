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
					tituloPagina('reservas futuras');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('addaluguel','criar reserva','addaluguelicon');
						Icone('reshoje','reservas para hoje','reservasicon');
						Icone('reservasanteriores','reservas anteriores','reservasanterioresicon');
						Icone('reservascanceladas','reservas canceladas','cancelarreservaicon');
					echo "</div>";
					echo "
						<script>
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#reshoje').on('click',function () {
								window.location.href='".$dominio."/painel/reservas';
							});
							$('#reservasanteriores').on('click',function () {
								window.location.href='".$dominio."/painel/reservas/anteriores';
							});
							$('#reservascanceladas').on('click',function () {
								window.location.href='".$dominio."/painel/reservas/canceladas';
							});
						</script>
					";
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>

					<?php
						require_once __DIR__.'/../includes/listareservas.inc.php';

						$filtro = new Conforto($uid);
						$filtro = $filtro->Exibicao($reservas_futuras);
						echo $filtro['botoes'];

						if ($filtro['i']>0) {
							echo "
								<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
								<!-- container -->
								<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							";

							$paginas = new Conforto($uid);
							$paginas = $paginas->Paginacao($filtro['itens']);
							foreach ($paginas['itens'] as $futura) {
								$aluguel = new ConsultaDatabase($uid);
								$aluguel = $aluguel->AluguelInfo($futura['aid']);
								$comeco = new DateTime($aluguel['inicio']);
								$conclusao = new DateTime($aluguel['devolucao']);
								$diarias = $comeco->diff($conclusao);

								$inicio = new DateTime($futura['inicio']);
								$devolucao = new DateTime($futura['devolucao']);
								$dia = new DateTime($futura['data']);

								$locatario = new ConsultaDatabase($uid);
								$locatario = $locatario->LocatarioInfo($aluguel['lid']);

								$veiculo = new ConsultaDatabase($uid);
								$veiculo = $veiculo->Veiculo($aluguel['vid']);

								$diarias = new Conforto($uid);
								$diarias = $diarias->TotalDiarias($inicio,$devolucao);

								$contrato_numero = new Conforto($uid);
								$contrato_numero = $contrato_numero->NumeroContrato($aluguel['aid']);

								echo "
									<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap ativa'>
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
							} // futura

							echo "
								</div>
								<!-- container -->
								".$paginas['botoes']."
								</div>
							";
						} else {
							NenhumRegistro();
						}// reservas futuras
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
