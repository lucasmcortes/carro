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
					tituloPagina('histórico da reserva');
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>

				<div id='veralugueis' style='min-width:48%;max-width:48%;display:inline-block;'>
					<?php BotaoPainel('ver aluguéis','alugueis','alugueis'); ?>
				</div>
				<div id='verreservas' style='min-width:48%;max-width:48%;display:inline-block;'>
					<?php BotaoPainel('ver reservas','reservas','reservas'); ?>
				</div>

					<?php
						if ( (isset($_GET['aid'])) && (is_numeric($_GET['aid'])) ) {
							$historico = new ConsultaDatabase($uid);
							$historico = $historico->HistoricoReserva($_GET['aid']);
							if ($historico[0]['data']!=0) {
								$filtro = new Conforto($uid);
								$filtro = $filtro->Exibicao($historico);
								//echo $filtro['botoes'];

								$consultareserva = new ConsultaDatabase($uid);
								$consultareserva = $consultareserva->Reserva($_GET['aid']);
								$consultaativacao = new ConsultaDatabase($uid);
								$consultaativacao = $consultaativacao->Ativacao($consultareserva['reid']);
								$registrodevolucao = new ConsultaDatabase($uid);
								$registrodevolucao = $registrodevolucao->Devolucao($_GET['aid']);

								$ativa = '';
								if ($consultaativacao['ativa']=='S') {
									$ativa = 'ativa';
									if ($registrodevolucao['deid']!=0) {
										$ativa = '';
									} // se devolveu
								} // ativa

								$inicio = new DateTime($consultareserva['inicio']);
								if ($inicio>$agora) {
									$ainda = 1;
									$iniciando = "<p style='min-width:80%;max-width:80%;display:inline-block;'>Iniciando dia ".$inicio->format('d/m/Y')." às ".$inicio->format('H')."h</p>";
								} else {
									$ainda = 0;
									$iniciando = '';
								}

								($consultaativacao['ativa']=='S') ? $atividade = 'Vigente' : $atividade = 'Cancelada';

								// primeira: não confirmou e ainda não chegou o dia de início
								// segunda: não confirmou e já passou o dia de início
								// terceira: confirmou (já chegou o dia de início [porque só tem a opção de confirmar quando chega o dia de início e confirma no painel])
								($consultareserva['confirmada']==0) ? ($ainda==1) ? $confirmacao = 'À confirmar' : $confirmacao = 'Não confirmada' : $confirmacao = 'Confirmada';

								if ($filtro['i']>0) {
									echo "
										<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
										<!-- container -->
										<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
									";

									$paginas = new Conforto($uid);
									$paginas = $paginas->Paginacao($filtro['itens']);
									foreach ($paginas['itens'] as $modificacao) {
										$aluguel = new ConsultaDatabase($uid);
										$aluguel = $aluguel->AluguelInfo($modificacao['aid']);
										$comeco = new DateTime($aluguel['inicio']);
										$conclusao = new DateTime($aluguel['devolucao']);
										$diarias = $comeco->diff($conclusao);

										$inicio = new DateTime($modificacao['inicio']);
										$devolucao = new DateTime($modificacao['devolucao']);
										$dia = new DateTime($modificacao['data']);

										$locatario = new ConsultaDatabase($uid);
										$locatario = $locatario->LocatarioInfo($aluguel['lid']);

										$veiculo = new ConsultaDatabase($uid);
										$veiculo = $veiculo->Veiculo($aluguel['vid']);

										$diarias = new Conforto($uid);
										$diarias = $diarias->TotalDiarias($inicio,$devolucao);

										$contrato_numero = new Conforto($uid);
										$contrato_numero = $contrato_numero->NumeroContrato($aluguel['aid']);

										echo "
											<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap ".$ativa."'>
												<div style='min-width:100%;max-width:100%;display:inline-block;'>
													<p class='numregistro'>
														".$contrato_numero."
													</p>
												</div>
												<div class='slotrelatoriowrap'>
													<div class='slotrelatorio'>
														<p class='headerslotrelatorio'><b>Status:</b></p>
														<p class='infoslotrelatorio'>".$atividade."</p>
														<p class='headerslotrelatorio'><b>Confirmação:</b></p>
														<p class='infoslotrelatorio'>".$confirmacao."</p>
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
														<p class='headerslotrelatorio'><b>Kilometragem:</b></p>
														<p class='infoslotrelatorio'>".Kilometragem($aluguel['kilometragem'])."</p>
													</div>
												</div>
												<div class='slotrelatoriowrap'>
													<div class='slotrelatorio'>
														<p class='headerslotrelatorio'><b>Data de registro:</b></p>
														<p class='infoslotrelatorio'>".$dia->format('d/m/Y')." às ".$dia->format('H')."h".$dia->format('i')."</p>
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
									} // modificacao

									echo "
										</div>
										<!-- container -->
										".$paginas['botoes']."
										</div>
									";
								} else {
									NenhumRegistro();
								} // histórico
							} // existe
						} else {
							//
						} // isset get
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
