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

	if ( (isset($_GET['lid'])) && (is_numeric($_GET['lid'])) ) {
		$lid = $_GET['lid'];
		$locatario = new ConsultaDatabase($uid);
		$locatario = $locatario->LocatarioInfo($lid);

		$telefone = new Conforto($uid);
		$telefone = $telefone->FormatoTelefone($locatario['telefone'],'br');

		if ($locatario['associado']=='S') {
			$associado = 'Sim';
		} else {
			$associado = 'Não';
		}
		$placas = new ConsultaDatabase($uid);
		$placas = $placas->Placas($locatario['lid']);

		$cnh = $locatario['cnh'];
		$imagem = glob(__DIR__.'/../cnh/'.$cnh.'.*', GLOB_BRACE);
		if (!empty($imagem)) {
			usort($imagem, fn($a, $b) => filemtime($b) - filemtime($a)); // arquivo mais recente
			$imagem = basename($imagem[0]);
		} else {
			$imagem = '';
		}

	} else {
		redirectToLogin('painel/locatarios');
	} // get lid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina($locatario['nome']);
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
						<!-- container -->
				                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Cadastrado em:</b> <?php echo strftime('%d de %B de %Y', strtotime($locatario['data_cadastro'])); ?></p>

							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>CNH:</b> <?php echo $locatario['cnh'] ?></p>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Validade:</b> <?php echo $locatario['validade'] ?></p>
							<div style='display:inline-block;border:1px solid var(--preto);border-radius:var(--radius);padding:1.8%;margin:1.8%;'>
								<?php
									if (!empty($imagem)) {
									echo "
										<div style='width:55%;display:inline-block;'>
									";
									if (strpos($imagem,'.pdf')!==false) {
										echo "
											<iframe id='cnhimg' src='".$dominio."/painel/locatarios/cnh/".$imagem."?".rand(1,999)."' style='width:100%;auto;'></iframe>
											<p id='dldoc' style='background-color:var(--preto);padding:3px 5px;border-radius:var(--radius);color:var(--branco);margin-bottom:1.8%;'>ver pdf</p>
											<script>
												$('#dldoc').on('click', function() {
													window.open('".$dominio."/painel/locatarios/cnh/".$imagem."','_blank');
												})
											</script>
										";
									} else {
										echo "
											<img class='cnhimg' style='max-width:100%;max-height:100%;' src='".$dominio."/painel/locatarios/cnh/".$imagem."?".rand(1, 999)."'></img>
											<script>
												$('.cnhimg').on('click', function () {
													$.ajax({
														url: '".$dominio."/includes/biggercnh.inc.php',
														success: function(bigpic) {
															$('#vestimenta').html(bigpic);
														},
													});
												});
											</script>
										";
									}
											MontaBotao('atualizar imagem','atualizaimgcnh');
										echo "
											<script>
												$('#atualizaimgcnh').on('click', function () {
													loadVestimenta('".$dominio."/painel/locatarios/novo/includes/atualizaimgcnh.inc.php');
												});
											</script>
											</div>
										";
									} else {
										echo "
										<!-- img_cnh_outer_wrap -->
										<div id='img_cnh_outer_wrap' class='uploadouterwrap'>
											<label>Foto da CNH:</label>
											<div id='img_cnh_wrap' class='uploadwrap'>
												<label id='label_img_cnh' for='img_cnh' class='upload'>
													<img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
													<p class='uploadcaption'>
														adicionar imagem
													</p>
												</label>
												<input type='file' name='img_cnh' id='img_cnh' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
												<div style='min-width:100%;max-width:100%;display:inline-block;'>
													<div id='progressBarWrap_cnh' class='uploadprogressbar'>
														<div id='progressBar_cnh' class='uploadprogressbarinner'></div>
														<p id='statusUpload_cnh' class='uploadstatusupload'></p>
													</div>
												</div>
											</div>


											<script>
												img_cnh_outer_wrap = $('#img_cnh_outer_wrap').html();
												function uploadFile(elemento) {
													file = document.getElementById(elemento).files[0];

													formdata = new FormData();
													formdata.append('img_cnh', file);
													formdata.append('uploaded_file_name', file.name);

													ajax = new XMLHttpRequest();
													ajax.upload.addEventListener('progress', progressHandler, false);
													ajax.addEventListener('load', completeHandler, false);
													ajax.addEventListener('error', errorHandler, false);
													ajax.addEventListener('abort', abortHandler, false);

													ajax.open('POST','".$dominio."/painel/locatarios/novo/includes/addimgcnh.inc.php');
													ajax.send(formdata);
												}

												function progressHandler(event) {
													percent = (event.loaded / event.total) * 100;
													$('#progressBar_cnh').width(Math.round(percent) + '%');
													document.getElementById('statusUpload_cnh').innerHTML = Math.round(percent) + '%';
												}

												function completeHandler(event) {
													document.getElementById('img_cnh_wrap').innerHTML = event.target.responseText;

													$.ajax({
														url: '".$dominio."/painel/locatarios/novo/includes/salvaimgcnh.inc.php'
													});

													$('#remove_img_cnh').on('click',function() {
														$('#img_cnh_outer_wrap').html(img_cnh_outer_wrap);
														$.ajax({
															url: '".$dominio."/painel/locatarios/novo/includes/unsetcnh.inc.php'
														});
													});
												}

												function errorHandler(event) {
													document.getElementById('img_cnh_wrap').innerHTML = 'Upload falhou';
												}

												function abortHandler(event) {
													document.getElementById('img_cnh_wrap').innerHTML = 'Upload cancelado';
												}

												$('#img_cnh').change(function() {
													elemento = $(this).attr('id');
													uploadFile(elemento);
												});
											</script>
										</div>
										<!-- img_cnh_outer_wrap -->
										";
									} // se existe a imagem da cnh
								?>
							</div>

							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>CPF:</b> <?php echo $locatario['documento'] ?></p>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Telefone:</b> <?php echo $telefone ?></p>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Email:</b> <?php echo $locatario['email'] ?></p>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Endereço:</b> <?php echo $locatario['rua'].', '.$locatario['numero'].' - '.$locatario['bairro'].' - '.$locatario['cidade'].' - '.$locatario['estado'] ?> </p>
							<?php
								if ($associado=='Sim') {
									echo "
										<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Associado:</b> ".$associado."</p>
										<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Desde:</b> ".strftime('%d de %B de %Y', strtotime($locatario['data_associado']))."</p>
									";
									$placas = new ConsultaDatabase($uid);
									$placas = $placas->Placas($locatario['lid']);
									$opcoes = [];
									foreach ($placas as $placa) {
										if ($placa['data']<$locatario['data_associado']) {
											$utilizadas = 0;
											$cortesia = new ConsultaDatabase($uid);
											$cortesia = $cortesia->Cortesia($placa['pid']);
											foreach($cortesia as $dias_gratis) {
												$utilizadas += $dias_gratis['utilizadas'];
											} // soma os dias utilizados de cortesia
											echo "
												<div style='border:1px solid var(--preto);padding:5px;display:inline-block;border-radius:var(--radius);margin:3px;'>
													<p style='border:1px solid var(--preto);background-color:var(--rosa);padding:5px 8px;margin:3px; display:inline-block;border-radius:var(--radius);'>".$placa['placa']."</p>
													<p>Cortesias utilizadas: ".$utilizadas."</p>
												</div>
											";
										} else {
											// da associação atual
											$ativa = new ConsultaDatabase($uid);
											$ativa = $ativa->PlacaAtiva($placa['pid']);
											// placas dessa associação que estão ativas (estão no PlacaAtiva)
											if (!in_array($ativa['pid'],$opcoes)) {
												if ($ativa['pid']!=0) {
													$utilizadas = 0;
													$cortesia = new ConsultaDatabase($uid);
													$cortesia = $cortesia->Cortesia($placa['pid']);
													foreach($cortesia as $dias_gratis) {
														$utilizadas += $dias_gratis['utilizadas'];
													} // soma os dias utilizados de cortesia
													echo "
														<div style='border:1px solid var(--preto);padding:5px;display:inline-block;border-radius:var(--radius);margin:3px;'>
															<p style='border:1px solid var(--preto);background-color:var(--verde);padding:5px 8px;margin:3px; display:inline-block;border-radius:var(--radius);'>".$placa['placa']."</p>
															<p>Cortesias utilizadas: ".$utilizadas."</p>
														</div>
													";
												} // placa que existe
											} // se ainda não é uma opção
											$opcoes[] = $ativa['pid'];

											// placas dessa associação mas que foram desativadas (não estão no PlacaAtiva)
											if (!in_array($placa['pid'],$opcoes)) {
												$utilizadas = 0;
												$cortesia = new ConsultaDatabase($uid);
												$cortesia = $cortesia->Cortesia($placa['pid']);
												foreach($cortesia as $dias_gratis) {
													$utilizadas += $dias_gratis['utilizadas'];
												} // soma os dias utilizados de cortesia
												echo "
													<div style='border:1px solid var(--preto);padding:5px;display:inline-block;border-radius:var(--radius);margin:3px;'>
														<p style='border:1px solid var(--preto);background-color:var(--rosa);padding:5px 8px;margin:3px; display:inline-block;border-radius:var(--radius);'>".$placa['placa']."</p>
														<p>Cortesias utilizadas: ".$utilizadas."</p>
													</div>
												";
											} // se ainda não é uma opção
											$opcoes[] = $placa['pid'];
										} // placa data
									} // foreach
								} // associado

								tituloPagina('aluguéis do locatário');
								$alugueis = new ConsultaDatabase($uid);
								$alugueis = $alugueis->ListaAlugueisLocatario($locatario['lid']);
								if ($alugueis[0]['aid']!=0) {
									echo "
										<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
											<!-- container -->
										";
													foreach ($alugueis as $aluguel) {
														$veiculo = new ConsultaDatabase($uid);
														$veiculo = $veiculo->Veiculo($aluguel['vid']);

														$locatario = new ConsultaDatabase($uid);
														$locatario = $locatario->LocatarioInfo($aluguel['lid']);

														$categoria = new ConsultaDatabase($uid);
														$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

														$dia = new DateTime($aluguel['data']);
														$inicio = new DateTime($aluguel['inicio']);
														$devolucao = new DateTime($aluguel['devolucao']);

														$devolucaoaluguel = new ConsultaDatabase($uid);
														$devolucaoaluguel = $devolucaoaluguel->Devolucao($aluguel['aid']);
														if ($devolucaoaluguel['deid']==0) {
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
																} // ativa
															} else {
																$ativo = 'ativo';
															} // reserva
														} else {
															$reserva = new ConsultaDatabase($uid);
															$reserva = $reserva->ReservaDevolvida($aluguel['aid']);
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
																} // ativa
															} else {
																$ativo = '';
															} // reserva
														} // deid 0

														$diarias = new Conforto($uid);
														$diarias = $diarias->TotalDiarias($inicio,$devolucao);

														echo "
															<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap ".$ativo."'>
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

													} // foreach alugueis

												echo "
											</div>
											<!-- container -->
									";
								} // aid > 0
							?>
						</div>
						</div>
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
						<!-- container -->
						<?php
							//tituloPagina($locatario['nome']);
						?>
					</div>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
