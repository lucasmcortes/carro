<?php
	require_once __DIR__.'/../../../../cabecalho.php';

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
					tituloPagina('recibos emitidos');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('criarrecibo','criar recibo','criarreciboicon');
					echo "</div>";
					echo "
						<script>
							$('#criarrecibo').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas/recibo';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<!-- container -->

					<!-- recibos anteriores -->
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
						<?php
							$recibos = glob(__DIR__.'/../_recibos/*.pdf', GLOB_BRACE);
							$recibos_anteriores = [];
							if (!empty($recibos)) {
								usort($recibos, fn($a, $b) => filemtime($b) - filemtime($a)); // arquivo mais recente
								foreach ($recibos as $anterior) {
									if (preg_match('/\d{14}/', $anterior, $data_recibo, PREG_UNMATCHED_AS_NULL)) {
										$data_recibo = str_split($data_recibo[0]);
										$ano_recibo_anterior = $data_recibo[0].$data_recibo[1].$data_recibo[2].$data_recibo[3];
										$mes_recibo_anterior = $data_recibo[4].$data_recibo[5];
										$dia_recibo_anterior = $data_recibo[6].$data_recibo[7];
										$hora_recibo_anterior = $data_recibo[8].$data_recibo[9];
										$minutos_recibo_anterior = $data_recibo[10].$data_recibo[11];
										$segundos_recibo_anterior = $data_recibo[12].$data_recibo[13];
									} else {
										$data_recibo = 0;
									} // regex recibo filename

									$parser = new \Smalot\PdfParser\Parser();
									$pdf = $parser->parseFile($anterior);
									$recibo_conteudo = $pdf->getText();

									// numero
									if (preg_match('/(RECIBO)(.*)(R\$)/s', $recibo_conteudo, $numero_recibo, PREG_UNMATCHED_AS_NULL)) {
										$numero = rtrim($numero_recibo[2],' ');
									} else {
										$numero = '';
									} // numero

									// pagador
									if (preg_match('/(Recebemos de: )(.*)(Endereço:)/s', $recibo_conteudo, $pagador_recibo, PREG_UNMATCHED_AS_NULL)) {
										$pagador = mb_strtolower($pagador_recibo[2],'UTF-8');
										$pagador = ucwords($pagador);
									} else {
										$pagador = '';
									} // pagador

									// valor
									if (preg_match('/(R\$)(.*)(Recebemos de:)/s', $recibo_conteudo, $valor_recibo, PREG_UNMATCHED_AS_NULL)) {
										$valor = str_replace(array(',','.'),'',$valor_recibo[2]);
									} else {
										$valor = '';
									} // valor

									// referente
									if (preg_match('/(Referente a )(.*)(•)/s', $recibo_conteudo, $referente_recibo, PREG_UNMATCHED_AS_NULL)) {
										$referente = $referente_recibo[2];
									} else {
										$referente = '';
									} // referente

									// emitente
									if (preg_match('/(Emitente: )(.*)(CPF|CNPJ)/s', $recibo_conteudo, $emitente_recibo, PREG_UNMATCHED_AS_NULL)) {
										$emitente = $emitente_recibo[2];
									} else {
										$emitente = '';
									} // emitente

									// cidade
									if (preg_match('/(•)(.*)(\,)/s', $recibo_conteudo, $cidade_recibo, PREG_UNMATCHED_AS_NULL)) {
										$cidade = $cidade_recibo[2];
									} else {
										$cidade = '';
									} // cidade

									// tipodocumento
									if (preg_match('/(CPF|CNPJ)(.*)(:)/s', $recibo_conteudo, $tipodocumento_recibo, PREG_UNMATCHED_AS_NULL)) {
										$tipodocumento = $tipodocumento_recibo[1];
										$documento = str_replace(array($tipodocumento,': ',':','Assinatura'),'',$tipodocumento_recibo[0]);
									} else {
										$tipodocumento = '';
										$documento = '';
									} // tipodocumento

									$recibos_anteriores[] = array(
										'path'=>$anterior,
										'url'=>$dominio.'/painel/cobrancas/recibo/_recibos/'.basename($anterior),
										'nome'=>basename($anterior),
										'tamanho'=>filesize($anterior),
										'ano_recibo_anterior'=>$ano_recibo_anterior,
										'mes_recibo_anterior'=>$mes_recibo_anterior,
										'dia_recibo_anterior'=>$dia_recibo_anterior,
										'hora_recibo_anterior'=>$hora_recibo_anterior,
										'minutos_recibo_anterior'=>$minutos_recibo_anterior,
										'segundos_recibo_anterior'=>$segundos_recibo_anterior,
										'data_recibo'=>$dia_recibo_anterior.'/'.$mes_recibo_anterior.'/'.$ano_recibo_anterior,
										'conteudo'=>$recibo_conteudo,
										'pagador'=>$pagador,
										'valor'=>$valor,
										'emitente'=>$emitente,
										'numero'=>$numero,
										'tipodocumento'=>$tipodocumento,
										'documento'=>$documento,
										'referente'=>$referente,
										'cidade'=>$cidade
									);
								} // foreach
							} else {
								NenhumRegistro();
							} // glob recibos

							foreach ($recibos_anteriores as $recibo) {
								echo "
									<div id='recibowrap_".$recibo['nome']."' class='relatoriowrap' data-arquivo='".$recibo['url']."'>
										<div class='slotrelatoriowrap'>
											<div class='slotrelatorio'>
												<p class='headerslotrelatorio'><b>Pagador:</b></p>
												<p class='infoslotrelatorio'>".$recibo['pagador']."</p>
												<p class='headerslotrelatorio'><b>Data:</b></p>
												<p class='infoslotrelatorio'>".$recibo['data_recibo']."</p>
											</div>
										</div>
										<div class='slotrelatoriowrap'>
											<div class='slotrelatorio'>
												<p class='headerslotrelatorio'><b>Valor do recibo:</b></p>
												<p class='infoslotrelatorio'>".Dinheiro($recibo['valor'])."</p>
												<p class='headerslotrelatorio'><b>Referente a:</b></p>
												<p class='infoslotrelatorio'>".ucfirst($recibo['referente'])."</p>
											</div>
										</div>
										<div class='slotrelatoriowrap'>
											<div class='slotrelatorio'>
												<p class='headerslotrelatorio'><b>Emitente:</b></p>
												<p class='infoslotrelatorio'>".$recibo['emitente']."</p>
												<p class='headerslotrelatorio'><b>".$recibo['tipodocumento'].":</b></p>
												<p class='infoslotrelatorio'>".$recibo['documento']."</p>
											</div>
										</div>
									</div>
								";
							} // foreach array recibos anteriores
						?>
						<script>
							$('.relatoriowrap').on('click', function() {
								window.open(
									$(this).attr('data-arquivo'),
									'_blank'
								);
							});
						</script>
					</div>
					<!-- recibos anteriores -->
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../../rodape.php';
?>
