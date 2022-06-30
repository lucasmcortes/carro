<?php
	require_once __DIR__.'/../../../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);

		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->AdminCategoria($adminivel['nivel']);

                $listaadmin = new ConsultaDatabase($uid);
                $listaadmin = $listaadmin->ListaAdmin();

		$veiculos = new ConsultaDatabase($uid);
		$veiculos = $veiculos->ListaVeiculos();
		if ($veiculos[0]['vid']!=0) {
			foreach ($veiculos as $veiculo) {
				$vid = $veiculo['vid'];
				$veiculo = new ConsultaDatabase($uid);
				$veiculo = $veiculo->Veiculo($vid);
				$potencia = new Conforto($uid);
				$potencia = $potencia->Potencia($vid);

				$anominimo = new DateTime($veiculo['data']);
				//$anominimo = new DateTime('2021-09-01 00:00:00.000000');

				if (!isset($_GET['de'])) {
					echo "
						<script>
							window.location.href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?de=".$agora->format('m/Y')."&ate=".$agora->format('m/Y')."';
						</script>
					";
				} // get de

				$inicioperiodo = $_GET['de']??$agora->format('m/Y');
				$inicioperiodo = explode('/',$inicioperiodo);
				$anoinicioperiodo = $inicioperiodo[1];
				$anoinicioperiodo = $inicioperiodo??$agora->format('Y');
				($anoinicioperiodo<$anominimo->format('Y')) ? $anoinicioperiodo = $anominimo->format('Y') : (($anoinicioperiodo>$agora->format('Y')) ? $anoinicioperiodo = $agora->format('Y') : $anoinicioperiodo = $anoinicioperiodo);

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

				if ($_GET['de']!=$data_inicial_periodo->format('m/Y')) {
					echo "
						<script>
							window.location.href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?de=".$data_inicial_periodo->format('m/Y')."&ate=".$agora->format('m/Y')."';
						</script>
					";
				} // get de

				if ($_GET['ate']!=$data_devolucao_periodo->format('m/Y')) {
					echo "
						<script>
							window.location.href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?de=".$data_inicial_periodo->format('m/Y')."&ate=".$data_devolucao_periodo->format('m/Y')."';
						</script>
					";
				} // get ate

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
								$reserva = $reserva->ReservaDevolvida($aluguel['aid']);
								if ($reserva['reid']!=0) {
									$data_inicio = new DateTime($reserva['inicio']);
									$inicio_string = $reserva['inicio'];
								} // se foi reserva

								$data_devolucao = new DateTime($devolucao['data']);

								// seta o período
								if ( ($data_inicio->format('Y-m-d')>=$data_inicial_periodo->format('Y-m-d')) && ($data_devolucao->format('Y-m-d')<=$data_devolucao_periodo->format('Y-m-d')) ) {

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
										'vid'=>$veiculo['vid'],
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
										'precoconsumidas'=>$totalDiarias*$aluguel['diaria'],
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


				$acionamentos = 0;
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
				$precoconsumidas = 0;
				$precocortesias = 0;
				$valores = [];
				$receitas = [];
				foreach ($concluidos as $concluido) {
					$totalgratuidade = 0;
					$pagoanteriormente = 0;
					$pagoaposfechamento = 0;
					($concluido['acionamento']!=0) ? $acionamentos++ : $acionamentos = $acionamentos;

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

					$precoconsumidas += $concluido['precoconsumidas'];
					$precocortesias += $concluido['precocortesias'];

				} // foreach concluido

				$bubble[] = array (
					'vid'=>$veiculo['vid'],
					'modelo'=>$veiculo['modelo'],
					'ganhos'=>$totalganhos,
					'acionamentos'=>$acionamentos,
					'receitaparticulares'=>$faturamentoparticular,
					'receitaassociados'=>$faturamentoassociado,
					'precoconsumidas'=>$precoconsumidas-$precocortesias,
					'precocortesias'=>$precocortesias,
					'liquido'=>$receitaliquida,
					'despesas'=>$totaldespesas,
					'categoria'=>$categoria,
					'saldo'=>$receitaliquida-$totaldespesas
				);
			} // foreach veiculo
		} else {
			redirectToLogin();
		} // se existe o veículo
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
						<?php tituloPagina('relatório geral'); ?>

						<!-- configuracoes relatorio -->
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
												for ($ano=$anominimo->format('Y');$anominimo->format('Y')<=$agora->format('Y');$anominimo->modify('+1 year')) {
													echo "<option value='".$anominimo->format('Y')."'>".$anominimo->format('Y')."</option>";
													$anos++;
												} // for
												$anominimo->modify('-'.$anos.' years');
											?>
										</select>
										<script>
											$('#anoiniciorelatorio').val('<?php echo $agora->format('Y') ?>');
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
												$anos = 0;
												for ($ano=$anominimo->format('Y');$anominimo->format('Y')<=$agora->format('Y');$anominimo->modify('+1 year')) {
													echo "<option value='".$anominimo->format('Y')."'>".$anominimo->format('Y')."</option>";
													$anos++;
												} // for
												$anominimo->modify('-'.$anos.' years');
											?>
										</select>
										<script>
											$('#anodevolucaorelatorio').val('<?php echo $agora->format('Y') ?>');
										</script>
									</div>
								</div> <!-- até -->
								<script>
									$('#verrelatorio').on('click', function() {
										window.location.href='http://<?php echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) ?>/?de='+$('#mesiniciorelatorio').val()+'/'+$('#anoiniciorelatorio').val()+'&ate='+$('#mesdevolucaorelatorio').val()+'/'+$('#anodevolucaorelatorio').val();
									});
								</script>
							</div> <!-- data definida -->
							<div style='min-width:100%;max-width:100%;display:inline-block;'>
								<p id='verrelatorio'>ver relatório</p>
							</div>
						</div>
						<!-- configuracoes relatorio -->

						<!-- container -->
				                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
							<!-- items -->
							<div class="items">
								<?php tituloPagina('saldo x despesas'); ?>

								<script type="text/javascript">
									google.charts.load("current", {packages:["corechart"]});
									google.charts.setOnLoadCallback(drawSeriesChart);
									function drawSeriesChart() {
										var data = google.visualization.arrayToDataTable([
										  ['Veiculo', 'Saldo', 'Despesas', 'Categoria', 'Receita líquida'],
										  <?php
										  	$grafico = '';
										  	foreach ($bubble as $bolha) {
												$grafico .= "['".$bolha['modelo']."',".$bolha['saldo'].",".$bolha['despesas'].",'".$bolha['categoria']."',".$bolha['liquido']."],";
											} // foreach
											echo rtrim($grafico,',');
										  ?>
										]);

										var options = {
											chartArea: {
												top:'3%',
												width: '72%'
											},
											colorAxis: {
												colors: ['green', 'red'],
												legend: {
													position: 'none'
												}
											},
											legend: {
												position: 'top',
												alignment: 'center'
											},
											series: {
												'Carro': {
													color: '#40C0CB'
												},
												'Moto': {
													color: '#BAE4E5'
												},
												'Utilitário': {
													color: '#E1F5C4'
												},
											},
											backgroundColor: '#FEF9F0',
											hAxis: {title: 'Saldo (R$)'},
											vAxis: {title: 'Despesas (R$)'},
											bubble: {
												textStyle: {
													auraColor: 'none'
												}
											}
										};

										var chart = new google.visualization.BubbleChart(document.getElementById('chart_div'));
										chart.draw(data, options);
									}
								</script>
								<div style='min-width:100%;max-width:100%;display:inline-block;'>
									<?php
										foreach ($bubble as $bolha) {
											echo "
												<div id='vid_".$bolha['vid']."' class='relatoriowrap'>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Veículo:</b></p>
															<p class='infoslotrelatorio'>".$bolha['modelo']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Receita líquida:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($bolha['liquido'])."</p>
															<p class='headerslotrelatorio'><b>Despesas:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($bolha['despesas'])."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Saldo:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($bolha['saldo'])."</p>
														</div>
													</div>
												</div>
											";
										} // foreach
									?>
								</div>
    								<div id="chart_div" style="display:inline-block;"></div>

								<?php tituloPagina('consumidas x cortesias'); ?>

								<script type="text/javascript">
									google.charts.load("current", {packages:["corechart"]});
									google.charts.setOnLoadCallback(drawSeriesChart);
									function drawSeriesChart() {
										var data = google.visualization.arrayToDataTable([
										  ['Veiculo', 'Consumidas', 'Cortesias'],
										  <?php
										  	$grafico = '';
										  	foreach ($bubble as $bolha) {
												$grafico .= "['".$bolha['modelo']."',".$bolha['precoconsumidas'].",".$bolha['precocortesias']."],";
											} // foreach
											echo rtrim($grafico,',');
										  ?>
										]);

										var options = {
											chartArea: {
												top:'3%',
												width: '72%'
											},
											colorAxis: {
												colors: ['#BAE4E5', '#FF4E50'],
												legend: {
													position: 'none'
												}
											},
											legend: {
												position: 'top',
												alignment: 'center'
											},
											backgroundColor: '#FEF9F0',
											hAxis: {title: 'Receita de diárias consumidas (R$)'},
											vAxis: {title: 'Gratuidade de diárias de cortesia (R$)'},
											bubble: {
												textStyle: {
													auraColor: 'none'
												}
											}
										};

										var chart = new google.visualization.BubbleChart(document.getElementById('chart_div2'));
										chart.draw(data, options);
									}
								</script>
								<div style='min-width:100%;max-width:100%;display:inline-block;'>
									<?php
										foreach ($bubble as $bolha) {
											echo "
												<div id='vid_".$bolha['vid']."' class='relatoriowrap'>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Veículo:</b></p>
															<p class='infoslotrelatorio'>".$bolha['modelo']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Valor de diárias consumidas:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($bolha['precoconsumidas'])."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Valor de cortesias em diárias:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($bolha['precocortesias'])."</p>
														</div>
													</div>
												</div>
											";
										} // foreach
									?>
								</div>
    								<div id="chart_div2" style="display:inline-block;"></div>

								<?php tituloPagina('associados x particulares'); ?>

								<script type="text/javascript">
									google.charts.load("current", {packages:["corechart"]});
									google.charts.setOnLoadCallback(drawSeriesChart);
									function drawSeriesChart() {
										var data = google.visualization.arrayToDataTable([
										  ['Veiculo', 'Associados', 'Particulares'],
										  <?php
										  	$grafico = '';
										  	foreach ($bubble as $bolha) {
												$grafico .= "['".$bolha['modelo']."',".$bolha['receitaassociados'].",".$bolha['receitaparticulares']."],";
											} // foreach
											echo rtrim($grafico,',');
										  ?>
										]);

										var options = {
											chartArea: {
												top:'3%',
												width: '72%'
											},
											colorAxis: {
												colors: ['#BAE4E5', '#FF4E50'],
												legend: {
													position: 'none'
												}
											},
											legend: {
												position: 'top',
												alignment: 'center'
											},
											backgroundColor: '#FEF9F0',
											hAxis: {title: 'Receita de aluguéis para associados (R$)'},
											vAxis: {title: 'Receita de aluguéis particulares (R$)'},
											bubble: {
												textStyle: {
													auraColor: 'none'
												}
											}
										};

										var chart = new google.visualization.BubbleChart(document.getElementById('chart_div3'));
										chart.draw(data, options);
									}
								</script>
								<div style='min-width:100%;max-width:100%;display:inline-block;'>
									<?php
										foreach ($bubble as $bolha) {
											echo "
												<div id='vid_".$bolha['vid']."' class='relatoriowrap'>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Veículo:</b></p>
															<p class='infoslotrelatorio'>".$bolha['modelo']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Associados:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($bolha['receitaassociados'])."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Particulares:</b></p>
															<p class='infoslotrelatorio'>".Dinheiro($bolha['receitaparticulares'])."</p>
														</div>
													</div>
												</div>
											";
										} // foreach
									?>
								</div>
    								<div id="chart_div3" style="display:inline-block;"></div>

								<?php tituloPagina('acionamentos'); ?>

								<div style='min-width:100%;max-width:100%;display:inline-block;'>
									<?php
										foreach ($bubble as $bolha) {
											echo "
												<div id='vid_".$bolha['vid']."' class='relatoriowrap'>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Veículo:</b></p>
															<p class='infoslotrelatorio'>".$bolha['modelo']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Acionamentos:</b></p>
															<p class='infoslotrelatorio'>".$bolha['acionamentos']."</p>
														</div>
													</div>
												</div>
											";
										} // foreach
									?>
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

			<script>
				$('.relatoriowrap').on('click', function() {
					vid = $(this).attr('id').split('_')[1];
					veiculoFundamental(vid);
				});
			</script>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../../rodape.php';
?>
