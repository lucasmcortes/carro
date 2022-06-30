<?php
	require_once __DIR__.'/../../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {

		$diasnoperiodo = 0;

		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['u_email']);

		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->AdminCategoria($adminivel['nivel']);

                $listaadmin = new ConsultaDatabase($uid);
                $listaadmin = $listaadmin->ListaAdmin();

		if ( (isset($_GET['v'])) && (is_numeric($_GET['v'])) ) {
			$vid = $_GET['v'];

			$veiculo = new ConsultaDatabase($uid);
			$veiculo = $veiculo->Veiculo($vid);
			if ($veiculo['vid']!=0) {
				echo "
					<script>
						document.title = 'Relatório do veículo ".$veiculo['modelo']."';
					</script>
				";

				$potencia = new Conforto($uid);
				$potencia = $potencia->Potencia($vid);

				$anominimo = new DateTime($veiculo['data']);

				$inicioperiodo = $_GET['de']??$agora->format('m/Y');
				$inicioperiodo = explode('/',$inicioperiodo);
				$anoinicioperiodo = $inicioperiodo[1];
				$anoinicioperiodo = $inicioperiodo??$agora->format('Y');
				($anoinicioperiodo<$anominimo->format('Y')) ? $anoinicioperiodo = $anominimo->format('Y') : (($anoinicioperiodo==$agora->format('Y')) ? $anoinicioperiodo = $agora->format('Y') : $anoinicioperiodo = $anoinicioperiodo[1]);

				$mesinicioperiodo = $inicioperiodo[0];

				$string_data_inicial_periodo = $anoinicioperiodo.'-'.$mesinicioperiodo.'-01';
				$data_desejada_periodo = new DateTime($string_data_inicial_periodo);
				($data_desejada_periodo<$anominimo) ? $string_data_inicial_periodo = $anoinicioperiodo.'-'.$anominimo->format('m').'-01' : $string_data_inicial_periodo = $string_data_inicial_periodo;
				($data_desejada_periodo>$agora) ? $string_data_inicial_periodo = $anoinicioperiodo.'-'.$agora->format('m').'-01' : $string_data_inicial_periodo = $string_data_inicial_periodo;
				$data_inicial_periodo = new DateTime($string_data_inicial_periodo);

				$devolucaoperiodo = $_GET['ate']??$agora->format('m/Y');
				$devolucaoperiodo = explode('/',$devolucaoperiodo);
				$anodevolucaoperiodo = $devolucaoperiodo[1];
				$anodevolucaoperiodo = $anodevolucaoperiodo??$agora->format('Y');
				($anodevolucaoperiodo<$anominimo->format('Y')) ? $anodevolucaoperiodo = $anominimo->format('Y') : (($anodevolucaoperiodo>$agora->format('Y')) ? $anodevolucaoperiodo = $agora->format('Y') : $anodevolucaoperiodo = $anodevolucaoperiodo);

				$mesdevolucaoperiodo = $devolucaoperiodo[0];

				$string_data_devolucao_periodo = $anodevolucaoperiodo.'-'.$mesdevolucaoperiodo.'-01';
				$data_desejada_periodo = new DateTime($string_data_devolucao_periodo);
				($data_desejada_periodo<$anominimo) ? $string_data_devolucao_periodo = $anodevolucaoperiodo.'-'.$anominimo->format('m').'-01' : $string_data_devolucao_periodo = $string_data_devolucao_periodo;
				($data_desejada_periodo>$agora) ? $string_data_devolucao_periodo = $anoinicioperiodo.'-'.$mesinicioperiodo.'-01' : $string_data_devolucao_periodo = $string_data_devolucao_periodo;
				($data_desejada_periodo<$anoinicioperiodo) ? $string_data_devolucao_periodo = $anodevolucaoperiodo.'-'.$anoinicioperiodo.'-01' : $string_data_devolucao_periodo = $string_data_devolucao_periodo;
				$data_devolucao_periodo = new DateTime($string_data_devolucao_periodo);
				$data_devolucao_periodo = $data_devolucao_periodo->modify('last day of this month');

				if ($data_inicial_periodo>$data_devolucao_periodo) {
					$data_devolucao_periodo= new DateTime($string_data_inicial_periodo);
				} // conserta data

				$cadastro = new DateTime($veiculo['data']);
				($veiculo['completo']=='S') ? $completo = 'Sim' : $completo = 'Não';

				$categoria = new ConsultaDatabase($uid);
				$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

				$listaalugueis = new ConsultaDatabase($uid);
				$listaalugueis = $listaalugueis->ListaAlugueisVeiculo($vid);
				$totalalugueis = count($listaalugueis);
				if ($totalalugueis>0) {
					$alugueisconcluidos = 0;
					$totalganhos = 0;
					$totaldespesas = 0;
					$concluidos = [];
					$retornosconcluidos = [];;

					foreach ($listaalugueis as $aluguelatual) {
						if ($aluguelatual['aid']!=0) {
							$aluguel = new ConsultaDatabase($uid);
							$aluguel = $aluguel->AluguelInfo($aluguelatual['aid']);

							$organizcaodepagamentos[0][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos[0][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Dinheiro'][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos['Dinheiro'][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Débito'][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos['Débito'][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Cartão de crédito'][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos['Cartão de crédito'][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Pix'][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos['Pix'][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Cortesia'][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos['Cortesia'][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Cheque'][$aluguel['aid']]['valor'] = [];;
							$organizcaodepagamentos['Cheque'][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Promissória'][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos['Promissória'][$aluguel['aid']]['data'] = [];
							$organizcaodepagamentos['Outro'][$aluguel['aid']]['valor'] = [];
							$organizcaodepagamentos['Outro'][$aluguel['aid']]['data'] = [];

							$data_inicio = new DateTime($aluguel['inicio']);
							$inicio_string = $aluguel['inicio'];

							$devolucao = new ConsultaDatabase($uid);
							$devolucao = $devolucao->Devolucao($aluguel['aid']);
							if ($devolucao['deid']!=0) {
								$alugueisconcluidos++;

								$reserva = new ConsultaDatabase($uid);
								$reserva = $reserva->Reserva($aluguel['aid']);
								if ($reserva['reid']!=0) {
									$data_inicio = new DateTime($reserva['inicio']);
									$inicio_string = $reserva['inicio'];
								} // se foi reserva

								$data_devolucao = new DateTime($devolucao['data']);

								// seta o período
								if ( ($data_inicio->format('Y-m-d')>=$data_inicial_periodo->format('Y-m-d')) && ($data_devolucao->format('Y-m-d')<=$data_devolucao_periodo->format('Y-m-d')) ) {
									// conta dias no período
									$totalHorasPeriodo = round((strtotime($data_devolucao_periodo->format('Y-m-d H:i:s')) - strtotime($data_inicial_periodo->format('Y-m-d H:i:s')))/3600, 0);
									$diasnoperiodo = ceil($totalHorasPeriodo/24)+1;

									$tolerancia_devolucao = new Conforto($uid);
									$tolerancia_devolucao = $tolerancia_devolucao->Tolerancia($data_devolucao);

									$totalHoras = round((strtotime($tolerancia_devolucao->format('Y-m-d H:i:s')) - strtotime($inicio_string))/3600, 0);
									$totalDiarias = ceil($totalHoras/24);
									($totalDiarias==0) ? $totalDiarias = 1 : $totalDiarias = $totalDiarias;

									$valoradicional = new ConsultaDatabase($uid);
							       	 	$valoradicional = $valoradicional->ValorAdicional($devolucao['deid']);

									$limpeza = new ConsultaDatabase($uid);
									$limpeza = $limpeza->LimpezaTipo($devolucao['data'],$devolucao['limpeza']);

									$precokm = new ConsultaDatabase($uid);
									$precokm = $precokm->PrecoKMData($devolucao['data']);
									$km_anterior = new ConsultaDatabase($uid);
									$km_anterior = $km_anterior->KilometragemAnterior($aluguel['vid'],$inicio_string,$devolucao['data']);
									$kilometragem_anterior = $km_anterior['km'];
									$limite_km_aluguel = $aluguel['kilometragem'];
									$kilometragem_devolucao = $devolucao['km'];

									$kmDiff = $kilometragem_devolucao - $kilometragem_anterior;
									$kmExcedentes = $kmDiff - $limite_km_aluguel;
									if ($kmExcedentes<0) {
										$kmExcedentes=0;
									} else {
										if ($aluguel['kilometragem']==1) {
											$kmExcedentes=0;
										} else {
											$kmExcedentes=$kmExcedentes;
										} // se é locatario
									} // kmExcedentes

									$cobranca = new ConsultaDatabase($uid);
									$cobranca = $cobranca->CobrancaAluguel($aluguel['aid']);
									$totalganhos += $cobranca['valor'];

									//echo '<br>aluguel: '.$aluguel['aid'].'<br>';

									//echo 'valor pagamento incial: '.$pagamentoinicialvalor = $aluguel['valor'].'<br>';
									//echo 'forma pagamento inicial: '.$pagamentoinicialforma = $aluguel['forma'].'<br>';
									$organizcaodepagamentos[$aluguel['forma']][$aluguel['aid']]['valor'][] = $aluguel['valor'];
									$organizcaodepagamentos[$aluguel['forma']][$aluguel['aid']]['data'][] = $aluguel['data'];

									$totalpagamentosparciais = 0;
									$pagamentosparciais = new ConsultaDatabase($uid);
									$pagamentosparciais = $pagamentosparciais->PagamentosParciais($aluguel['aid']);
									if ($pagamentosparciais[0]['papid']!=0) {
										foreach ($pagamentosparciais as $pagamentoparcial) {
											//echo 'valor pagamento parcial: '.$pagamentoparcialvalor = $pagamentoparcial['valor'].'<br>';
											//echo 'forma pagamento parcial: '.$pagamentoparcialforma = $pagamentoparcial['forma'].'<br>';
											$organizcaodepagamentos[$pagamentoparcial['forma']][$aluguel['aid']]['valor'][] = $pagamentoparcial['valor'];
											$organizcaodepagamentos[$pagamentoparcial['forma']][$aluguel['aid']]['data'][] = $pagamentoparcial['data'];

											$totalpagamentosparciais += $pagamentoparcial['valor'];
										} // foreach
									} // tem pagamentos parciais da cobrança

									$totalcobrancasparciais = 0;
									$pagamentosparciaiscobranca = new ConsultaDatabase($uid);
									$pagamentosparciaiscobranca = $pagamentosparciaiscobranca->CobrancaParcial($cobranca['coid']);
									if ($pagamentosparciaiscobranca[0]['copid']!=0) {
										foreach ($pagamentosparciaiscobranca as $pagamentoparcialcobranca) {
											//echo 'valor cobrança parcial: '.$pagamentoparcialcobrancavalor = $pagamentoparcialcobranca['valor'].'<br>';
											//echo 'forma cobrança parcial: '.$pagamentoparcialcobrancaforma = $pagamentoparcialcobranca['forma'].'<br>';
											$organizcaodepagamentos[$pagamentoparcialcobranca['forma']][$aluguel['aid']]['valor'][] = $pagamentoparcialcobranca['valor'];
											$organizcaodepagamentos[$pagamentoparcialcobranca['forma']][$aluguel['aid']]['data'][] = $pagamentoparcialcobranca['data_pagamento'];

											$totalcobrancasparciais += $pagamentoparcialcobranca['valor'];
										} // foreach
									} // tem pagamentos parciais da cobrança
									$antesdofechamento = $aluguel['valor'] + $totalpagamentosparciais + $totalcobrancasparciais;
									//echo 'pagamentos antes do fechamento: '.$antesdofechamento.'<br>';

									//echo 'valor cobrança: '.$cobranca['valor'].'<br>';
									//echo 'cobrança com pagamentos antes do fechamento descontados: '.$cobranca['valor']-$antesdofechamento.'<br>';
									//echo 'valor transação: '.$cobranca['valor_transacao'].'<br>';
									//echo 'forma transação: '.$cobranca['forma'].'<br>';

									$valortotal = $cobranca['valor'];
									$pagoateomomento = 0;
									$descontado = 0;
									$sobre = 0;
									$somaparciais = new Conforto($uid);
									$somaparciais = $somaparciais->SomaParciais($cobranca['coid']);
									$pagamentosaluguel = new Conforto($uid);
									$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aluguel['aid']);
									$pagoateomomento = $pagamentosaluguel+$somaparciais;
									$valor_mostrado = $cobranca['valor']-$pagoateomomento;
									// aqui é o valor total da transacao
									$transacao = new ConsultaDatabase($uid);
									$transacao = $transacao->Transacao($cobranca['tid']);
									if ($transacao['forma']=='Cortesia') {
										if ($transacao['desconto']>0) {
											$pagamentosintegraldoresidual = $valor_mostrado - ($valor_mostrado * ($transacao['desconto']/100));
											$descontado = $valor_mostrado-$pagamentosintegraldoresidual;
											$sobre = $valor_mostrado;
											$organizcaodepagamentos[$transacao['forma']][$aluguel['aid']]['valor'][] = $valor_mostrado;
											$organizcaodepagamentos[$transacao['forma']][$aluguel['aid']]['data'][] = $transacao['data'];
										} else {
											$organizcaodepagamentos[$transacao['forma']][$aluguel['aid']]['valor'][] = $valor_mostrado;
											$organizcaodepagamentos[$transacao['forma']][$aluguel['aid']]['data'][] = $transacao['data'];
										}
									} else if ($transacao['forma']!='Cortesia') {
										if ($transacao['desconto']>0) {
											$pagamentosintegraldoresidual = $valor_mostrado - ($valor_mostrado * ($transacao['desconto']/100));
											$descontado = $valor_mostrado-$pagamentosintegraldoresidual;
											$sobre = $valor_mostrado;
										}
									}

									//echo 'desconto transação: '.$cobranca['desconto'].'<br>';
									//echo 'preço do km excedente na época: '.$precokm['preco'].'<br>';
									//echo 'km excedentes: '.$kmExcedentes.'<br>';
									//echo 'valor dos km excedentes: '.$kmExcedentes*$precokm['preco'].'<br>';
									//echo 'limpeza devolução: '.$devolucao['limpeza'].'<br>';
									//echo 'limpeza tipo:'.$limpeza['tipo'].'<br>';
									//echo 'valor adicional: '.$valoradicional['valor'].'<br>';
									//echo 'descrição valor adicional: '.$valoradicional['descricao'].'<br>';
									//echo 'cortesias de diárias: '.$cobranca['cortesias'].'<br>';

									//echo 'valor por diária: '.$aluguel['diaria'].'<br>';
									$diarias = $data_inicio->diff($data_devolucao);
									($diarias->format('%a')<1) ? $diarias = 1 : $diarias = $diarias->format('%a');
									//echo 'total de diárias: '.$diarias.'<br>';
									//echo '<br>';

									($devolucao['limpeza']==0) ? $limpezavalor = 0 : $limpezavalor = $devolucao['limpeza'];
									($limpezavalor==0) ? $limpezatipo = '' : $limpezatipo = ucfirst(str_replace('limpeza ','',$limpeza['tipo']));

									($kmExcedentes==0) ? $excedentesvalor = 0 : (($kmExcedentes==1) ? $excedentesvalor = 0 : $excedentesvalor = $kmExcedentes*$precokm['preco']);
									($excedentesvalor==0) ? $kmExcedentes = $kmExcedentes : $kmExcedentes = $kmExcedentes;

									($valoradicional['valor']==0) ? $adicional = 0: $adicional = $valoradicional['valor'];
									($adicional==0) ? $descadicional = '' : $descadicional = $valoradicional['descricao'];

									($cobranca['cortesias']>$totalDiarias) ? $totalCortesias = $totalDiarias : $totalCortesias = $cobranca['cortesias'];

									$associado = new ConsultaDatabase($uid);
									$associado = $associado->AssociadoData($aluguel['lid'],$inicio_string);
									($associado=='S') ? $associado = 'Sim' : $associado = 'Não';
									($aluguel['particular']=='S') ? $particular = 'Sim' : $particular = 'Não';

									($aluguel['acid']==0) ? $acionamento = 'Não' : $acionamento = 'Sim';

									$concluidos[] = array(
										'aid'=>$aluguel['aid'],
										'associado'=>$associado,
										'acionamento'=>$acionamento,
										'particular'=>$particular,
										'coid'=>$cobranca['coid'],
										'inicio'=>$data_inicio,
										'devolucao'=>$data_devolucao,
										'data_registro_devolucao'=>$devolucao['data'],
										'diaria'=>$aluguel['diaria'],
										'diarias'=>$totalDiarias,
										'cortesias'=>$totalCortesias,
										'precocortesias'=>$totalCortesias*$aluguel['diaria'],
										'limpeza'=>$limpezavalor,
										'limpezatipo'=>$limpezatipo,
										'kmexcedentes'=>$kmExcedentes,
										'precokmexcedentes'=>$precokm['preco'],
										'totalprecoexcedentes'=>$excedentesvalor,
										'valoradicional'=>$adicional,
										'descadicional'=>$descadicional,
										'total'=>$cobranca['valor'],
										'descontado'=>$descontado,
										'sobre'=>$sobre,
										'soma_anteriores'=>$pagoateomomento,
										'transacao_desconto'=>$transacao['desconto']
									);

									$sort_inicio = array_column($concluidos, 'inicio');
									array_multisort($sort_inicio, SORT_ASC, $concluidos);
								} // dentro do período determinado
							} // se já devolveu
						} // existem alugueis
					} // foreach aluguel
				} // alugueis > 0

				$listamanutencoes = new ConsultaDatabase($uid);
				$listamanutencoes = $listamanutencoes->VeiculoManutencoes($vid);
				if (count($listamanutencoes)>0) {
					foreach ($listamanutencoes as $manutencao) {
						$retorno = new ConsultaDatabase($uid);
						$retorno = $retorno->Retorno($manutencao['mid']);
						if ($retorno['rid']!=0) {
							$data_inicio = new DateTime($manutencao['inicio']);
							$data_devolucao = new DateTime($retorno['data']);
							$inicio_string = $manutencao['inicio'];
							$devolucao_string = $retorno['data'];

							$reservamanutencao = new ConsultaDatabase($uid);
							$reservamanutencao = $reservamanutencao->ManutencaoReserva($manutencao['mid']);
							if ($reservamanutencao['mreid']!=0) {
								if ($reservamanutencao['confirmada']==1) {
									$data_inicio = new DateTime($reservamanutencao['inicio']);
									$data_devolucao = new DateTime($reservamanutencao['devolucao']);
									$inicio_string = $reservamanutencao['inicio'];
									$devolucao_string = $reservamanutencao['devolucao'];
								} // se confirmou
							} // se foi agendamento

							if ( ($data_inicio->format('Y-m-d')>=$data_inicial_periodo->format('Y-m-d')) && ($data_devolucao->format('Y-m-d')<=$data_devolucao_periodo->format('Y-m-d')) ) {
								$totaldespesas += $retorno['valor'];

								$totalHoras = round((strtotime($devolucao_string) - strtotime($inicio_string))/3600, 0);
								$totalDiarias = ceil($totalHoras/24);
								($totalDiarias==0) ? $totalDiarias = 1 : $totalDiarias = $totalDiarias;

								$manutencaomotivo = new ConsultaDatabase($uid);
								$manutencaomotivo = $manutencaomotivo->VeiculoManutencao($manutencao['motivo']);
								$retornosconcluidos[] = array(
									'mid'=>$manutencao['mid'],
									'inicio'=>$data_inicio,
									'devolucao'=>$data_devolucao,
									'totaldias'=>$totalDiarias,
									'estabelecimento'=>$manutencao['estabelecimento'],
									'motivo'=>$manutencaomotivo,
									'total'=>$retorno['valor']
								);
							} // dentro do período
						} // se retornou
					} // foreach manutencao
				} // manutencoes > 0

				$lucro = $totalganhos-$totaldespesas;
			} else {
				redirectToLogin();
			} // se existe o veículo
		} else {
			redirectToLogin();
		} // isset veiculo

	} else {
		redirectToLogin();
	} // isset uid
?>
	<style>
		.columnchart {min-width:100%;max-width:100%;margin:0 auto;margin-bottom:3%;display:inline-block;}
		.columnchart:first-child {min-width:100%;max-width:100%;width:100%;}
	</style>
	<corpo>
		<!-- conteudo -->
		<div class='conteudo' style='min-height:34vh;'>

		        <div style='min-width:100%;max-width:100%;text-align:center;'>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:0 auto;padding:1.2% 3%;'>
						<?php tituloPagina($veiculo['modelo']); ?>
						<div style='min-width:100%;max-width:100%;display:inline-block;margin:1.8% auto;'>
							<div class='relatorio'>
								<p class='relatorio'><b>Marca:</b> <?php echo $veiculo['marca']?:'-' ?> </p>
								<p class='relatorio'><b>Cor:</b> <?php echo $veiculo['cor'] ?> </p>
								<p class='relatorio'><b>Ano:</b> <?php echo $veiculo['ano'] ?> </p>
							</div>
							<div class='relatorio'>
								<p class='relatorio'><b>Categoria:</b> <?php echo $categoria ?> </p>
								<p class='relatorio'><b>Potência:</b> <?php echo $potencia?:'-' ?> </p>
								<p class='relatorio'><b>Completo:</b> <?php echo $completo ?> </p>
							</div>
							<div class='relatorio'>
								<p class='relatorio'><b>Placa:</b> <?php echo $veiculo['placa'] ?> </p>
								<p class='relatorio'><b>Chassi:</b> <?php echo $veiculo['chassi']?:0 ?> </p>
								<p class='relatorio'><b>Renavam:</b> <?php echo $veiculo['renavam']?:0 ?> </p>
							</div>
							<div class='relatorio'>
								<p class='relatorio' style='float:left;'><b>Início da atividade:</b> <?php echo $cadastro->format('d/m/Y') ?></p>
								<p class='relatorio' style='float:right;'><b>Total de aluguéis concluídos:</b> <?php echo $alugueisconcluidos ?></p>
							</div>
						</div>

						<div class='relatorio' style='margin:1% auto;'>
							<div style='display:inline-block;'> <!-- de -->
								<p style='display:inline-block;vertical-align:sub;'><b>Relatório de </b></p>
								<div style='display:inline-block;'> <!-- data definida-->
									<div style='display:inline-block;'>
										<select id='mesiniciorelatorio' style='max-width:100%;min-width:120px;'>
											<option value='01'>Janeiro</option>
											<option value='02'>Fevereiro</option>
											<option value='03'>Março</option>
											<option value='04'>Abril</option>
											<option value='05'>Maio</option>
											<option value='06'>Junho</option>
											<option value='07'>Julho</option>
											<option value='08'>Agosto</option>
											<option value='09'>Setembro</option>
											<option value='10'>Outubro</option>
											<option value='11'>Novembro</option>
											<option value='12'>Dezembro</option>
										</select>
										<script>
											$('#mesiniciorelatorio').val('<?php echo $data_inicial_periodo->format('m') ?>');
										</script>
									</div>
									<div style='display:inline-block;'>
										<select id='anoiniciorelatorio' style='max-width:100%;min-width:120px;'>
											<?php
												$anos = 0;
												for ($anoinicioperiodo=$anominimo->format('Y');$anominimo<=$agora;$anominimo->modify('+1 year')) {
													echo "<option value='".$anoinicioperiodo."'>".$anoinicioperiodo."</option>";
													$anos++;
												} // for
												echo "<option value='".$agora->format('Y')."'>".$agora->format('Y')."</option>";
												$anominimo->modify('-'.$anos.' years');
											?>
										</select>
										<script>
											$('#anoiniciorelatorio').val('<?php echo $data_inicial_periodo->format('Y') ?>');
										</script>
									</div>
								</div> <!-- data definida -->

							</div> <!-- de -->
							<div style='display:inline-block;'> <!-- até-->
								<p style='display:inline-block;vertical-align:sub;'><b>até </b></p>
								<div style='display:inline-block;'> <!-- data definida-->
									<div style='display:inline-block;'>
										<select id='mesdevolucaorelatorio' style='max-width:100%;min-width:120px;'>
											<option value='01'>Janeiro</option>
											<option value='02'>Fevereiro</option>
											<option value='03'>Março</option>
											<option value='04'>Abril</option>
											<option value='05'>Maio</option>
											<option value='06'>Junho</option>
											<option value='07'>Julho</option>
											<option value='08'>Agosto</option>
											<option value='09'>Setembro</option>
											<option value='10'>Outubro</option>
											<option value='11'>Novembro</option>
											<option value='12'>Dezembro</option>
										</select>
										<script>
												$('#mesdevolucaorelatorio').val('<?php echo $data_devolucao_periodo->format('m') ?>');
										</script>
									</div>
									<div style='display:inline-block;'>
										<select id='anodevolucaorelatorio' style='max-width:100%;min-width:120px;'>
											<?php
												for ($ano=$anominimo->format('Y');$anominimo<=$agora;$anominimo->modify('+1 year')) {
													echo "<option value='".$ano."'>".$ano."</option>";
												} // for
												echo "<option value='".$agora->format('Y')."'>".$agora->format('Y')."</option>";
											?>
										</select>
										<script>
											$('#anodevolucaorelatorio').val('<?php echo $data_devolucao_periodo->format('Y') ?>');
										</script>
									</div>
								</div> <!-- até -->
							</div> <!-- data definida -->

							<div style='min-width:100%;max-width:100%;display:inline-block;'>
								<p id='verrelatorio'>ver relatório</p>
							</div>
							<script>
								$('#verrelatorio').on('click', function() {
									window.location.href='http://<?php echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) ?>/?v=<?php echo $_GET['v'] ?>&de='+$('#mesiniciorelatorio').val()+'/'+$('#anoiniciorelatorio').val()+'&ate='+$('#mesdevolucaorelatorio').val()+'/'+$('#anodevolucaorelatorio').val();
								});
							</script>
						</div>

						<!-- container -->
				                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;border-top:5px solid var(--preto);'>
							<!-- items -->
							<div class="items">
								<?php tituloRelatorio('faturamento de aluguéis'); ?>
								<!-- container -->
						                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;margin:0 auto;'>
									<?php
										$receitabruta = 0;
										$receitaliquida = 0;
										$totaldiariascortesias = 0;
										$todasasdiarias = 0;
										$gratuidadetotal = 0;
										$faturamentoparticular = 0;
										$faturamentonaoparticular = 0;
										$faturamentoassociado = 0;
										$faturamentonaoassociado = 0;
										$totaldeganhosantesdofechamento = 0;
										$totaldeganhosaposofechamento = 0;
										$valores = [];
										$receitas = [];
										foreach ($concluidos as $concluido) {
											$totalgratuidade = 0;
											$pagoanteriormente = 0;
											$pagoaposfechamento = 0;

											//$totalgratuidade += $concluido['precocortesias'];
											if (count($organizcaodepagamentos['Cortesia'][$concluido['aid']])>0) {
												foreach ($organizcaodepagamentos['Cortesia'][$concluido['aid']]['data'] as $key => $gratuidadedata) {
													$totalgratuidade += $organizcaodepagamentos['Cortesia'][$concluido['aid']]['valor'][$key];
												} // foreach data de pagamento em cortesia
											} // tem cortesias no aluguel
											$metodospagamentos = array(
												'Dinheiro','Débito','Cartão de crédito','Pix','Cheque','Promissória','Outro'
											);
											foreach ($metodospagamentos as $metodo) {
												if (count($organizcaodepagamentos[$metodo][$concluido['aid']])>0) {
													foreach ($organizcaodepagamentos[$metodo][$concluido['aid']]['data'] as $key => $metododata) {
														if ($metododata<$concluido['data_registro_devolucao']) {
															//echo $metododata.'////coid'.$concluido['coid'].'/////aid'.$concluido['aid'].'$'.$organizcaodepagamentos[$metodo][$concluido['aid']]['valor'][$key].'em///'.$metodo.'<br>';
															$pagoanteriormente += $organizcaodepagamentos[$metodo][$concluido['aid']]['valor'][$key];
															$totaldeganhosantesdofechamento += $pagoanteriormente;
														} else {
															$pagoaposfechamento += $organizcaodepagamentos[$metodo][$concluido['aid']]['valor'][$key];
															$totaldeganhosaposofechamento += $pagoaposfechamento;
														}// valor de pagamento em cortesia
													} // foreach data de pagamento em cortesia
												} // tem cortesias no aluguel
											} // foreach metodo

											$gratuidadetotal += $totalgratuidade;

											if ($concluido['transacao_desconto']>0) {
												$liquidoaluguel = $concluido['total']-$concluido['descontado'];
												$descontototal = "(pago com ".$concluido['transacao_desconto']."% de desconto sobre ".Dinheiro($concluido['sobre']).")";
											} else {
												$liquidoaluguel = $concluido['total']-$totalgratuidade;
												$descontototal = '';
											}

											$receitaliquida += $liquidoaluguel;
											$receitabruta += $concluido['total'];

											$pagoaposfechamento = $liquidoaluguel-$pagoanteriormente;
											($pagoaposfechamento<0) ? $devolvido = ' devolvido(s) ao locatário' : $devolvido = '';

											$totaldiariascortesias += $concluido['cortesias'];
											$todasasdiarias += $concluido['diarias'];

											$valores[$concluido['aid']] = array(
												'receita'=>$concluido['total'],
												'gratuidade'=>$totalgratuidade,
												'saldo'=>$liquidoaluguel
											);

											$receitas[$concluido['aid']] = array(
												'diarias'=>$concluido['diaria']*$concluido['diarias'],
												'adicionais'=>$concluido['limpeza']+$concluido['totalprecoexcedentes']+$concluido['valoradicional']
											);

											if ($concluido['particular']=='Sim') {
												$faturamentoparticular += $liquidoaluguel;
											} else if ($concluido['particular']=='Não') {
												$faturamentonaoparticular += $liquidoaluguel;
											} // particular

											if ($concluido['associado']=='Sim') {
												$faturamentoassociado += $liquidoaluguel;
											} else if ($concluido['associado']=='Não') {
												$faturamentonaoassociado += $liquidoaluguel;
											} // associado

											echo "
												<div id='relatoriofaturamento_wrap_".$concluido['aid']."' class='relatoriowrap aluguel'>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='data de início do aluguel'>
																	<b>Data de início:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".$concluido['inicio']->format('d/m/y')." às ".$concluido['inicio']->format('H')."h</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='data de devolução do veículo'>
																	<b>Data de devolução:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".$concluido['devolucao']->format('d/m/y')." às ".$concluido['devolucao']->format('H')."h</p>
														</div>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='preço de cada diária'>
																	<b>Diária:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro($concluido['diaria'])."</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='total de diárias/diárias gratuitas'>
																	<b>Diárias consumidas/cortesia:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".$concluido['diarias']."/".$concluido['cortesias']."</p>
																<p class='headerslotrelatorio'>
																	<span class='info' aria-label='locatário associado/acionamento da placa'>
																		<b>Associado/Acionamento:</b>
																	</span>
																</p>
																<p class='infoslotrelatorio'>".$concluido['associado']."/".$concluido['acionamento']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='aluguel particular ou pela placa do associado'>
																	<b>Aluguel particular:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".$concluido['particular']."</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='tipo de limpeza escolhida na devolução'>
																	<b>Limpeza:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro($concluido['limpeza'])."
														";
														if ($concluido['limpezatipo']!='') {
															echo "
																(".$concluido['limpezatipo'].")
															";
														}
														echo "</p>";
														echo "
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='valor adicional definido na devolução'>
																	<b>Valor adicional:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".$concluido['descadicional']."</p>
															<p class='infoslotrelatorio'>".Dinheiro($concluido['valoradicional'])."</p>
														</div>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='número de km além do especificado/valor do km na época'>
																	<b>Kilometragem excedente:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Kilometragem($concluido['kmexcedentes'])." x ".Dinheiro($concluido['precokmexcedentes'])."</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='valor total da kilometragem além da especificada'>
																	<b>Preço pela kilometragem excedente:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro($concluido['totalprecoexcedentes'])."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='pago inicialmente e durante o aluguel'>
																	<b>Valor pago antes do fechamento:</b
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro($pagoanteriormente)."</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='pago após a devolução'>
																	<b>Valor após o fechamento:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro(str_replace('-','',$pagoaposfechamento))."".$devolvido."</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='valor em descontos e pagamentos em cortesia'>
																	<b>Gratuidade:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro($totalgratuidade+($concluido['total']-$liquidoaluguel))."</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='soma de todos os custos'>
																	<b>Valor total da fatura:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro($concluido['total'])." ".$descontototal."</p>
															<p class='headerslotrelatorio'>
																<span class='info' aria-label='valor total - gratuidade'>
																	<b>Faturamento líquido:</b>
																</span>
															</p>
															<p class='infoslotrelatorio'>".Dinheiro($liquidoaluguel)."</p>
														</div>
													</div>
												</div>
											";
										} // foreach concluido
									?>
									<script>
										$('.relatoriowrap.aluguel').on('click', function() {
											aid = $(this).attr('id').split('_')[2];
											aluguelFundamental(aid,0);
										});
									</script>
								</div>
								<!-- container -->

								<div class='relatorio'>
									<p class='relatorio' style='float:left;'>• <b>Receita total:</b></p>
									<p class='relatorio' style='float:right;text-align:right;'><?php echo Dinheiro($totalganhos); ?></p>
								</div>
								<div class='relatorio'>
									<p class='relatorio' style='float:left;'>• <b>Receita líquida:</b></p>
									<p class='relatorio' style='float:right;text-align:right;'><?php echo Dinheiro($receitaliquida); ?></p>
								</div>

								<script>
									google.charts.load('current', {'packages':['bar']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Aluguel', 'Receita', 'Gratuidade', 'Saldo'],
											<?php
												$a=0;
												$valores_string = '';
												if (count($valores)>0) {
													foreach ($valores as $aid => $valor) {
														$a++;
														$valores_string .= "['Aluguel ".$a."',".$valor['receita'].",".$valor['gratuidade'].",".$valor['saldo']."],";
													} // foreach valores
												} // valores > 0
												echo rtrim($valores_string,',');
											?>
										]);

										var options = {
											bars: 'vertical',
											backgroundColor: 'transparent',
											chartArea: {
												backgroundColor:'transparent',
												left:0,
												width: '100%'
											},
											legend: {
												position:'none'
											},
											vAxis: {format: 'decimal'},
											colors: ['#40C0CB', '#FF0309','#C3FF68'],
											width: '100%'
										};

										var chart = new google.charts.Bar(document.getElementById('chart_div'));
										chart.draw(data, google.charts.Bar.convertOptions(options));
									}
								</script>
								<div id="chart_div" class="columnchart"></div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											['Dias disponíveis no período', <?php echo $diasnoperiodo-$todasasdiarias ?>],
											['Dias alugados no período', <?php echo $todasasdiarias ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart" class="piechart" style="display:inline-block;"></div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											<?php
												$totalreceitasdiarias = 0;
												$totalreceitaextras = 0;
												if (count($receitas)>0) {
													foreach ($receitas as $receita) {
														$totalreceitasdiarias += $receita['diarias'];
														$totalreceitaextras += $receita['adicionais'];
													} // foreach valores
												} // valores > 0
											?>
											['Diárias', <?php echo $totalreceitasdiarias ?>],
											['Adicionais (limpeza, km, extras)', <?php echo $totalreceitaextras ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart2" class="piechart" style="display:inline-block;"></div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											['Recebimentos antes do fechamento da fatura', <?php echo $totaldeganhosantesdofechamento ?>],
											['Recebimentos após a devolução)', <?php echo $totaldeganhosaposofechamento ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart3'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart3" class="piechart" style="display:inline-block;"></div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											['Gratuidade', <?php echo $gratuidadetotal ?>],
											['Receita', <?php echo $receitabruta-$gratuidadetotal ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart4'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart4" class="piechart" style="display:inline-block;"></div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											['Faturamento de aluguéis particulares', <?php echo $faturamentoparticular ?>],
											['Faturamento de aluguéis não particulares', <?php echo $faturamentonaoparticular ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart7'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart7" class="piechart" style="display:inline-block;"></div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											['Faturamento de aluguéis para associados', <?php echo $faturamentoassociado ?>],
											['Faturamento de aluguéis para não associados', <?php echo $faturamentonaoassociado ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart8'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart8" class="piechart" style="display:inline-block;"></div>

								<!-- container -->
						                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;margin:0 auto;'>
									<?php tituloRelatorio('despesas em manutenção'); ?>
									<?php
									$totaldiasmanutencao = 0;
										foreach ($retornosconcluidos as $retornoconcluido) {
											$totaldiasmanutencao += $retornoconcluido['totaldias'];
											echo "
												<div id='relatoriodespesas_wrap_".$retornoconcluido['mid']."' class='relatoriowrap manutencao'>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Data de início:</b></p>
															<p class='infoslotrelatorio'>".$retornoconcluido['inicio']->format('d/m/y')." às ".$retornoconcluido['inicio']->format('H')."h</p>
															<p class='headerslotrelatorio'><b>Data de devolução:</b></p>
															<p class='infoslotrelatorio'>".$retornoconcluido['devolucao']->format('d/m/y')." às ".$retornoconcluido['devolucao']->format('H')."h</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Estabelecimento:</b></p>
															<p class='infoslotrelatorio'>".$retornoconcluido['estabelecimento']."</p>
															<p class='headerslotrelatorio'><b>Motivo:</b></p>
															<p class='infoslotrelatorio'>".$retornoconcluido['motivo']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Custo total:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($retornoconcluido['total'])."</p>
														</div>
													</div>
												</div>
											";
										} // foreach concluido
									?>
									<script>
										$('.relatoriowrap.manutencao').on('click', function() {
											rid = $(this).attr('id').split('_')[2];
											retornoFundamental(rid);
										});
									</script>
								</div>
								<!-- container -->
								<div class='relatorio'>
									<p class='relatorio' style='float:left;'>• <b>Despesa total:</b></p>
									<p class='relatorio' style='float:right;'><?php echo Dinheiro($totaldespesas) ?></p>
								</div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											['Dias em manutenção no período', <?php echo $totaldiasmanutencao ?>],
											['Dias disponíveis no período', <?php echo $diasnoperiodo-$totaldiasmanutencao ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart5'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart5" class="piechart" style="display:inline-block;"></div>

								<script type="text/javascript">
									google.charts.load('current', {'packages':['corechart']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['Descrição', 'Valor'],
											['Dias em manutenção', <?php echo $totaldiasmanutencao ?>],
											['Dias alugados', <?php echo $todasasdiarias ?>]
										]);
										var options = {
											legend: 'none',
											pieSliceText: 'label',
											backgroundColor: 'transparent',
											colors: ['#FF0309', '#C3FF68'],
											is3D: true,
											pieSliceText:'none',
											legend: {
												position:'labeled'
											},
											tooltip: {
												trigger: 'selection'
											}

										};
										var chart = new google.visualization.PieChart(document.getElementById('piechart6'));
										chart.draw(data, options);
									}
								</script>
								<div id="piechart6" class="piechart" style="display:inline-block;"></div>

								<?php tituloRelatorio('saldo do veículo'); ?>

								<script>
									google.charts.load('current', {'packages':['bar']});
									google.charts.setOnLoadCallback(drawChart);

									function drawChart() {
										var data = google.visualization.arrayToDataTable([
											['','Receita', 'Despesas'],
											['Total',<?php echo $receitaliquida ?>,<?php echo $totaldespesas ?>]
										]);

										var options = {
											bars: 'vertical',
											backgroundColor: 'transparent',
											chartArea: {
												backgroundColor:'transparent',
												left:0,
												width: 900
											},
											legend: {
												position:'none'
											},
											vAxis: {format: 'decimal'},
											colors: ['#40C0CB', '#FF0309'],
											width: '100%'
										};

										var chart = new google.charts.Bar(document.getElementById('chart_div2'));
										chart.draw(data, google.charts.Bar.convertOptions(options));
									}
								</script>
								<div id="chart_div2" class="columnchart"></div>

								<div class='relatorio'>
									<p class='relatorio' style='float:left;'>• <b>Saldo do veículo:</b></p>
									<p class='relatorio' style='float:right;'><?php echo Dinheiro($receitaliquida-$totaldespesas) ?></p>
								</div>

							</div>
							<!-- items -->
						</div>
						<!-- container -->
					</div>
				</div>
	        	</div>
			<div id='relatorioheader' style='min-width:100%;max-width:100%;text-align:center;margin:0 auto;'>
				<div style='display:inline-block;vertical-align:bottom;'> <!-- imprimir -->
					<?php Icone('imprimirpagina','imprimir','imprimiricon'); ?>
					<script>$('#imprimirpagina').on('click',function() {window.print();});</script>
				</div> <!-- imprimir -->
			</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
