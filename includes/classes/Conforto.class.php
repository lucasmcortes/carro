<?php

	include_once __DIR__.'/../setup.inc.php';

	class Conforto extends ConsultaDatabase {
			public $uid;

			public function __construct($user) {
				$this->uid = $user;
			}

			public function NumeroContrato($aid) {
				$aluguel = parent::AluguelInfo($aid);

				return $aluguel['guid'];
			} // NumeroContrato

			public function DevolucaoAtualizada($devolucao) {
					$this->data_agora = date_create()->format('Y-m-d H:i:s.u');
					$this->data_de_hoje = new DateTime($this->data_agora);

					if ( ($devolucao->format('Y-m-d'))<($this->data_de_hoje->format('Y-m-d')) ) {
						$devolucao = new DateTime($this->data_agora);
						$devolucao->modify('+1 day');
					} // se era pra ter devolvido

					return $devolucao;
			} // DevolucaoAtualizada

			public function PossibilidadeManutencao($vid) {
				$this->data_agora = date_create()->format('Y-m-d H:i:s.u');
				$this->data_de_hoje = new DateTime($this->data_agora);

				//$status = 'disponível';
				$disponibilidade_veiculo = [];
				$inicio_status = new DateTime($this->data_agora);
				$devolucao_status = new DateTime($this->data_agora);

				// vê se tá na manutenção
				$manutencao = parent::ManutencaoRecente($vid);
				if ($manutencao['mid']!=0) {
					$inicio_status = new DateTime($manutencao['inicio']);
					$devolucao_status = new DateTime($manutencao['devolucao']);
					$motivo = parent::VeiculoManutencao($manutencao['motivo']);
					$status = mb_strtolower($motivo);
					$reservamanutencao = parent::ManutencaoReserva($manutencao['mid']);
					if ($reservamanutencao['mreid']!=0) {
						$manutencaoativa = parent::ManutencaoAtivacao($reservamanutencao['mreid']);
						if ($manutencaoativa['ativa']=='S') {
							$inicio_status = new DateTime($reservamanutencao['inicio']);
							$devolucao_status = new DateTime($reservamanutencao['devolucao']);
						} else {
							$status = 'disponível';
						} // reserva da manutencao ativa
					} // foi uma reserva de manutencao

					$retorno = parent::Retorno($manutencao['mid']);
					if ($retorno['data']!=0) {
						$devolucao_status = new DateTime($retorno['data']);
						//$devolucao_status = $devolucao_status->modify('+1 day'); // pra contar o dia de hoje também
						$status = 'disponível';
					} // retorno

				} else {
					$status = 'disponível';
				}// mid > 0

				$devolucao_status = $this->DevolucaoAtualizada($devolucao_status);

				return $this->possibilidademanutencao = $possibilidademanutencao = array (
					'mid'=>$manutencao['mid']?:0,
					'inicio_status'=>$inicio_status,
					'devolucao_status'=>$devolucao_status,
					'status'=>$status
				);
			} // PossibilidadeManutencao

			public function Possibilidade($vid) {
				$this->data_agora = date_create()->format('Y-m-d H:i:s.u');
				$this->data_de_hoje = new DateTime($this->data_agora);

				$disponibilidade_veiculo = [];
				$inicio_status = new DateTime($this->data_agora);
				$devolucao_status = new DateTime($this->data_agora);
				$status = 'Disponível';
				$disponibilidade_veiculo[$this->data_de_hoje->format('Y-m-d')]['status'] = $status;
				$aid = 0;

				// vê se tá na manutenção
				$possibilidademanutencao = $this->PossibilidadeManutencao($vid);
				$inicio_status = $possibilidademanutencao['inicio_status'];
				$devolucao_status = $possibilidademanutencao['devolucao_status'];
				if ($possibilidademanutencao['mid']!=0) {
					for ($i=$inicio_status;$i<=$devolucao_status;$i->modify('+1 day')) {
						$disponibilidade_veiculo[$i->format('Y').'-'.$i->format('m').'-'.$i->format('d')] = array(
							'status'=>ucfirst($possibilidademanutencao['status']),
							'id'=>$possibilidademanutencao['mid'],
							'dia'=>$i->format('d')?:$this->data_de_hoje->format('d'),
							'mes'=>$i->format('m')?:$this->data_de_hoje->format('m'),
							'ano'=>$i->format('Y')?:$this->data_de_hoje->format('Y')
						);
					} // for
				} // mid >0

				// vê os dias que tá alugado
				$alugueis_do_veiculo = parent::ListaAlugueisVeiculo($vid);
				if ($alugueis_do_veiculo[0]['aid']!=0) {
					foreach ($alugueis_do_veiculo as $aluguel) {
						$aid = $aluguel['aid'];
						$devolucao = parent::Devolucao($aluguel['aid']);
						if ($devolucao['deid']==0) {
							$inicio_status = new DateTime($aluguel['inicio']);
							$devolucao_status = new DateTime($aluguel['devolucao']);
							if ($inicio_status->format('Y-m-d H:i')>$this->data_de_hoje->format('Y-m-d H:i')) {
								$reserva = parent::Reserva($aluguel['aid']);
								$ativa = parent::Ativacao($reserva['reid']);
								if ($ativa['atid']!=0) {
									if ($ativa['ativa']=='S') {
										$inicio_status = new DateTime($reserva['inicio']);
										$devolucao_status = new DateTime($reserva['devolucao']);
										if ($inicio_status->format('Y-m-d')<$this->data_de_hoje->format('Y-m-d') ) {
											$status = 'alugado';
										} else if ($inicio_status->format('Y-m-d')==$this->data_de_hoje->format('Y-m-d') ) {
											if ($inicio_status->format('H:i')<=$this->data_de_hoje->format('H:i') ) {
												$status = 'alugado';
											} else {
												$status = 'reservado';
											} // hora
										} else {
											$status = 'reservado';
										} // dia
									} else if ($ativa['ativa']=='N') {
										// vê se tá na manutenção
										$possibilidademanutencao = $this->PossibilidadeManutencao($vid);
										$inicio_status = $possibilidademanutencao['inicio_status'];
										$devolucao_status = $possibilidademanutencao['devolucao_status'];
										if ($possibilidademanutencao['mid']!=0) {
											for ($i=$inicio_status;$i<=$devolucao_status;$i->modify('+1 day')) {
												$disponibilidade_veiculo[$i->format('Y').'-'.$i->format('m').'-'.$i->format('d')] = array(
													'status'=>ucfirst($possibilidademanutencao['status']),
													'id'=>$possibilidademanutencao['mid'],
													'dia'=>$i->format('d')?:$this->data_de_hoje->format('d'),
													'mes'=>$i->format('m')?:$this->data_de_hoje->format('m'),
													'ano'=>$i->format('Y')?:$this->data_de_hoje->format('Y')
												);
											} // for
										} // mid >0
									} // ativa
								} // atid > 0
							} else if ($inicio_status->format('Y-m-d H:i')<$this->data_de_hoje->format('Y-m-d H:i')) {
								$reserva = parent::Reserva($aluguel['aid']);
								if ($reserva['aid']!=0) {
									$ativa = parent::Ativacao($reserva['reid']);
									if ($ativa['atid']!=0) {
										if ($ativa['ativa']=='S') {
											$inicio_status = new DateTime($reserva['inicio']);
											$devolucao_status = new DateTime($reserva['devolucao']);
											if ($inicio_status->format('Y-m-d')<$this->data_de_hoje->format('Y-m-d') ) {
												if ($reserva['confirmada']==1) {
													$status = 'alugado';
												}
											} else if ($inicio_status->format('Y-m-d')==$this->data_de_hoje->format('Y-m-d') ) {
												if ($inicio_status->format('H:i')<=$this->data_de_hoje->format('H:i') ) {
													$status = 'alugado';
												} else {
													$status = 'reservado';
												} // hora
											} else {
												$status = 'reservado';
											} // dia
										} else if ($ativa['ativa']=='N') {
											// vê se tá na manutenção
											// $possibilidademanutencao = $this->PossibilidadeManutencao($vid);
											// $inicio_status = $possibilidademanutencao['inicio_status'];
											// $devolucao_status = $possibilidademanutencao['devolucao_status'];
											// for ($i=$inicio_status;$i<=$devolucao_status;$i->modify('+1 day')) {
											// 	$disponibilidade_veiculo[$i->format('Y').'-'.$i->format('m').'-'.$i->format('d')] = array(
											// 		'status'=>ucfirst($possibilidademanutencao['status']),
											// 		'id'=>$possibilidademanutencao['mid'],
											// 		'dia'=>$i->format('d')?:$this->data_de_hoje->format('d'),
											// 		'mes'=>$i->format('m')?:$this->data_de_hoje->format('m'),
											// 		'ano'=>$i->format('Y')?:$this->data_de_hoje->format('Y')
											// 	);
											// } // for
										} // ativa
									} // atid > 0
								} else {
									$status = 'alugado';
								} // reserva
							}  else if ($inicio_status->format('Y-m-d H:i')==$this->data_de_hoje->format('Y-m-d H:i')) {
								if ($inicio_status->format('H:i')>$this->data_de_hoje->format('H:i')) {
									$reserva = parent::Reserva($aluguel['aid']);
									if ($reserva['aid']!=0) {
										$ativa = parent::Ativacao($reserva['reid']);
										if ($ativa['atid']!=0) {
											if ($ativa['ativa']=='S') {
												$inicio_status = new DateTime($reserva['inicio']);
												$devolucao_status = new DateTime($reserva['devolucao']);
												if ($inicio_status->format('Y-m-d')<$this->data_de_hoje->format('Y-m-d') ) {
													$status = 'alugado';
												} else if ($inicio_status->format('Y-m-d')==$this->data_de_hoje->format('Y-m-d') ) {
													if ($inicio_status->format('H:i')<=$this->data_de_hoje->format('H:i') ) {
														$status = 'alugado';
													} else {
														$status = 'reservado';
													} // hora
												} else {
													$status = 'reservado';
												} // dia
											} else if ($ativa['ativa']=='N') {
												// vê se tá na manutenção
												$possibilidademanutencao = $this->PossibilidadeManutencao($vid);
												$inicio_status = $possibilidademanutencao['inicio_status'];
												$devolucao_status = $possibilidademanutencao['devolucao_status'];
												for ($i=$inicio_status;$i<=$devolucao_status;$i->modify('+1 day')) {
													$disponibilidade_veiculo[$i->format('Y').'-'.$i->format('m').'-'.$i->format('d')] = array(
														'status'=>ucfirst($possibilidademanutencao['status']),
														'status'=>ucfirst($possibilidademanutencao['status']),
														'id'=>$possibilidademanutencao['mid'],
														'dia'=>$i->format('d')?:$this->data_de_hoje->format('d'),
														'mes'=>$i->format('m')?:$this->data_de_hoje->format('m'),
														'ano'=>$i->format('Y')?:$this->data_de_hoje->format('Y')
													);
												} // for
											} // ativa
										} else {
											$status = 'alugado';
										} // atid > 0
									} // reserva
								} else {
									// aluguel que começa hoje mas em uma hora antes de agora
									$reserva = parent::Reserva($aluguel['aid']);
									if ($reserva['aid']!=0) {
										$ativa = parent::Ativacao($reserva['reid']);
										if ($ativa['atid']!=0) {
											if ($ativa['ativa']=='S') {
												$inicio_status = new DateTime($reserva['inicio']);
												$devolucao_status = new DateTime($reserva['devolucao']);
												if ($inicio_status->format('Y-m-d')<$this->data_de_hoje->format('Y-m-d') ) {
													$status = 'alugado';
												} else if ($inicio_status->format('Y-m-d')==$this->data_de_hoje->format('Y-m-d') ) {
													if ($inicio_status->format('H:i')<=$this->data_de_hoje->format('H:i') ) {
														$status = 'alugado';
													} else {
														$status = 'reservado';
													} // hora
												} else {
													$status = 'reservado';
												} // dia
											} else if ($ativa['ativa']=='N') {
												// vê se tá na manutenção
												$possibilidademanutencao = $this->PossibilidadeManutencao($vid);
												$inicio_status = $possibilidademanutencao['inicio_status'];
												$devolucao_status = $possibilidademanutencao['devolucao_status'];
												for ($i=$inicio_status;$i<=$devolucao_status;$i->modify('+1 day')) {
													$disponibilidade_veiculo[$i->format('Y').'-'.$i->format('m').'-'.$i->format('d')] = array(
														'status'=>ucfirst($possibilidademanutencao['status']),
														'status'=>ucfirst($possibilidademanutencao['status']),
														'id'=>$possibilidademanutencao['mid'],
														'dia'=>$i->format('d')?:$this->data_de_hoje->format('d'),
														'mes'=>$i->format('m')?:$this->data_de_hoje->format('m'),
														'ano'=>$i->format('Y')?:$this->data_de_hoje->format('Y')
													);
												} // for
											} // ativa
										} // atid > 0
									} // reserva
								} // hora
							} // data inicio aluguel

						} else if ($devolucao['deid']>0) {
							// devolveu
						} else {
							$status = 'alugado';
						} // deid = 0

						$devolucao_status = $this->DevolucaoAtualizada($devolucao_status);

						for ($i=$inicio_status;$i<=$devolucao_status;$i->modify('+1 day')) {
							$disponibilidade_veiculo[$i->format('Y').'-'.$i->format('m').'-'.$i->format('d')] = array(
								'disponibilidade'=>ucfirst($status),
								'status'=>ucfirst($status),
								'id'=>$aid,
								'dia'=>$i->format('d')?:$this->data_de_hoje->format('d'),
								'mes'=>$i->format('m')?:$this->data_de_hoje->format('m'),
								'ano'=>$i->format('Y')?:$this->data_de_hoje->format('Y')
							);

							if (!in_array($i,$disponibilidade_veiculo)) {
							} // in array

						} // for
					} // foreach $alugueis_do_veiculo
				} // alugueis > 0

				// usa a disponibilidade de hoje pra colocar no status
				$status = $disponibilidade_veiculo[$this->data_de_hoje->format('Y-m-d')]['status']??$status;

				return $this->possibilidade = $possibilidade = array (
					'disponibilidade'=>$disponibilidade_veiculo,
					'status'=>ucfirst($status)
				);
			} // Possibilidade

			public function TotalDiarias($inicio,$conclusao) {
				$diarias = $inicio->diff($conclusao);
				$diarias = $diarias->format('%a');
				($diarias==0) ? $diarias = 1 : $diarias = $diarias;
				return $this->darias = $diarias;
			} // TotalDiarias

			public function Tolerancia($devolucao) {
				$this->data_agora = date_create()->format('Y-m-d H:i:s.u');
				$this->data_de_hoje = new DateTime($this->data_agora);

				$tolerancia = new DateTime(date_create()->format('Y-m-d H:i:s.u'));

				//$configuracoes = parent::Configuracoes(); // aqui é a tolerância mais recente configurada
				$minutos = parent::ToleranciaData($devolucao->format('Y-m-d H:i:s.uuuuuu')); // aqui é a tolerância da data da devolução

				$tolerancia->modify("+".$minutos['minutos']." minutes"); // hora de agora com 1h a mais pra dar a tolerancia
				$minutos_tolerancia = $tolerancia->diff($devolucao);

				if ($minutos_tolerancia->i<=$minutos['minutos']) {
					$devolucao->add(new DateInterval('PT'.$minutos['minutos'].'M'));
				} // se tem até $configuracoes['min_tolerancia'] minutos de diferença entre a tolerância e a devolução

				return $this->devolucao = $devolucao;
			} // Tolerancia

			public function RevisaoDezKm($vid) {
				$this->data_agora = date_create()->format('Y-m-d H:i:s.u');
				$this->data_de_hoje = new DateTime($this->data_agora);
				$resultado = 0;
				$veiculo = parent::Veiculo($vid);
				$configuracoes = parent::Configuracoes();
				$kilometragens = parent::Kilometragens($veiculo['vid']);

				$revisoes_feitas = [];
				$revisoes_necessarias = [];
				$data_revisoes_necessarias = [];

				if ($veiculo['categoria']!=3) {
					// carro e utilitario
					if ( ($veiculo['km']>$configuracoes['rev_car_prev']) && ($veiculo['km']<$configuracoes['rev_car_limiar']) ) {
						// revisa a cada 10k
						$multiplo = $configuracoes['rev_car_prev'];
					} else if ($veiculo['km']>=$configuracoes['rev_car_limiar']) {
						// revisa a cada 7k
						$multiplo = $configuracoes['rev_car_apos'];
					} else {
						// aqui tem menos km rodados do que o múltiplo de revisão
						$multiplo = 0;
						$resultado = 0;
					} // km > 10000
				} else if ($veiculo['categoria']==3) {
					// moto
					// revisa a cada 1k
					$multiplo = $configuracoes['rev_moto'];
				} // qual categoria

				($veiculo['revisao']==0) ? $multiplo = $multiplo : $multiplo = $veiculo['revisao'];

				if ($multiplo>0) {
					foreach ($kilometragens as $kilometragem) {
						// quantas vezes chegou ao multiplo determinado de km para revisar
						$dezk = $multiplo * floor($kilometragem['km']/$multiplo);
						if ($dezk>0) {
							$revisoes_necessarias[] = $dezk;
							$data_revisoes_necessarias[] = $kilometragem['data'];
						} // dezk > 0
					} // foreach

					$primeiraVezQueFezOMultiploMaisRecente = array_search(end($revisoes_necessarias), $revisoes_necessarias);
					if (array_key_exists($primeiraVezQueFezOMultiploMaisRecente,$data_revisoes_necessarias)) {
						$dataPrimeiraVezQueFezOMultiploMaisRecente = $data_revisoes_necessarias[$primeiraVezQueFezOMultiploMaisRecente];
						$dataMultiploRecente = new DateTime($dataPrimeiraVezQueFezOMultiploMaisRecente);
					} else {
						$dataMultiploRecente = $this->data_de_hoje;
					}

					$revisoes_necessarias = array_unique($revisoes_necessarias, SORT_REGULAR);

					$manutencoes = parent::VeiculoManutencoes($veiculo['vid']);
					if ($manutencoes[0]!=0) {
						foreach ($manutencoes as $manutencao) {
							// revisão = 5, oficina = 1
							if ($manutencao['motivo']==5) {
								$revisoes_feitas[] = $manutencao['data'];
							} // motivo 5 (revisão)
						} // foreach
					} // se tem manutencao
					$revisoes_feitas = array_unique($revisoes_feitas, SORT_REGULAR);

					$revisao_recente = reset($revisoes_feitas);
					($revisao_recente=='') ? $data_revisao_recente = new DateTime('0000-00-00 00:00:00.000000') : $data_revisao_recente = new DateTime($revisao_recente);

					// se a data que fez a revisao recente for mais antiga que a data que precisou de fazer a revisao (bateu a marca)
					if (count($revisoes_feitas) != count($revisoes_necessarias)) {
						if ($dataMultiploRecente<=$data_revisao_recente) {
							// só diz pra revisar se a primeira vez que deu o múltiplo de 10 mil mais recente foi antes da data da revisão mais recente
							// fica subentendido que a vez mais recente que fez revisão no veículo já revisou tudo de uma vezes
							// mesmo pelos outros milhares de kilometros anteriores
							//$resultado = 'Revisado';
							$resultado = 0;
						} else {
							if ($configuracoes['rev_ativa']=='S') {
								$resultado = "
									<div style='min-width:100%;max-width:100%;display:inline-block;'>
										<p style='display:inline-block;'><b>Fazer revisão</b></p>
									</div>
								";
							}
						} // data revisao feita e revisoes necessarias
					} else {
						$resultado = 0;
					} // revisoes_feitas != revisoes_necessarias

					// if (count($revisoes_necessarias) > count($revisoes_feitas)) {
					// 	$revisao_recente = reset($revisoes_feitas);
					// 	($revisao_recente=='') ? $data_revisao_recente = new DateTime('0000-00-00 00:00:00.000000') : $data_revisao_recente = new DateTime($revisao_recente);
					// 	if ($dataMultiploRecente<=$data_revisao_recente) {
					// 		// só diz pra revisar se a primeira vez que deu o múltiplo de 10 mil mais recente foi antes da data da revisão mais recente
					// 		// fica subentendido que a vez mais recente que fez revisão no veículo já revisou tudo de uma vezes
					// 		// mesmo pelos outros milhares de kilometros anteriores
					// 		//$resultado = 'Revisado';
					// 		$resultado = 0;
					// 	} else {
					// 		if ($configuracoes['rev_ativa']=='S') {
					// 			$resultado = "
					// 				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					// 					<p style='display:inline-block;'><b>Fazer revisão</b></p>
					// 				</div>
					// 			";
					// 		}
					// 	} //
					// } else {
					// 	$resultado = 0;
					// } // se tem mais revisões necessárias do que revisões feitas
				} // multiplo

				return $this->resultado = $resultado;
			} // RevisaoDezKm

			public function Cortesias($pid) {
				$configuracoes = parent::Configuracoes();
				$this->data_de_hoje = new DateTime(date_create()->format('Y-m-d H:i:s.u'));
				$placa = [];
				$placa['pid'] = $pid;
				$cortesias = $configuracoes['dias_cortesia_placa_mes'];
				$cortesias_por_placa = $configuracoes['dias_cortesia_placa_mes'];
				$cortesias_da_placa_utilizadas_no_ultimo_mes = 0;
				$cortesias_da_placa_utilizadas_no_ultimo_ano = 0;
				$cortesias_totais_da_placa = parent::Cortesia($pid);
				if (count($cortesias_totais_da_placa)>0) {
					foreach ($cortesias_totais_da_placa as $cortesia) {
						if ($cortesia['utilizadas']==0) {
							//
						} else if ($cortesia['utilizadas']>0) {
							$trinta_dias_atras = new DateTime(date('Y-m-d H:i:s.u', strtotime(date_create()->format('Y-m-d H:i:s.u'). ' -30 days')));
							$um_ano_atras = new DateTime(date('Y-m-d H:i:s.u', strtotime(date_create()->format('Y-m-d H:i:s.u'). ' -360 days')));
							$data_cortesia = new DateTime($cortesia['data']);
							if ( ($data_cortesia>$trinta_dias_atras) && ($data_cortesia<$this->data_de_hoje) ) {
								// se foi nos últimos 30 dias que usou a cortesia
								// subtrai nas cortesias
								$cortesias -= $cortesia['utilizadas'];
								$cortesias_por_placa -= $cortesia['utilizadas'];
								$cortesias_da_placa_utilizadas_no_ultimo_mes += $cortesia['utilizadas'];
							} else if ( ($data_cortesia<$trinta_dias_atras) && ($data_cortesia>$um_ano_atras) && ($data_cortesia<=$this->data_de_hoje) ) {
								// cortesias utilizadas antes de 30 dias atrás dentro dos últimos 360 dias
								$cortesias_da_placa_utilizadas_no_ultimo_ano += $cortesia['utilizadas'];
							} // datas utilizacao
						} // utilizadas
					} // foreach cortesia
				} // cortesias > 0

				$placa += ['cortesias_da_placa_utilizadas_no_ultimo_mes' => $cortesias_da_placa_utilizadas_no_ultimo_mes ?? 0];
				$placa += ['cortesias_da_placa_utilizadas_no_ultimo_ano' => $cortesias_da_placa_utilizadas_no_ultimo_ano ?? 0];

				if ($placa['cortesias_da_placa_utilizadas_no_ultimo_mes']<$configuracoes['dias_cortesia_placa_mes']) {
					if ($placa['cortesias_da_placa_utilizadas_no_ultimo_ano']<$configuracoes['dias_cortesia_placa_ano']) {
						$cortesias_da_placa_pra_esse_aluguel = $configuracoes['dias_cortesia_placa_mes']-$placa['cortesias_da_placa_utilizadas_no_ultimo_mes'];
						// echo 'a placa '.$placa['placa'].' ainda tem '.$cortesias_da_placa_pra_esse_aluguel.' cortesias nesse mês<br>';
					} else {
						$cortesias_da_placa_pra_esse_aluguel = 0;
					} // <15 ano
				} else {
					$cortesias_da_placa_pra_esse_aluguel = 0;
				} // <5 mes

				$placa += ['cortesias_disponiveis' => $cortesias_da_placa_pra_esse_aluguel ?? 0];

				return $this->placa = $placa;
			} // Cortesias

			public function ModificacaoPossivel($aid,$comeco_mod,$conclusao_mod) {
				$this->data_de_hoje = new DateTime(date_create()->format('Y-m-d H:i:s.u'));
				$dias_reservados_do_veiculo = [];
				$dias_originais = [];

				$aluguel = parent::AluguelInfo($aid);
				$reservaoriginal = parent::Reserva($aluguel['aid']);
				if ($reservaoriginal['reid']!=0) {
					$comeco_original = new DateTime($reservaoriginal['inicio']);
					$conclusao_original = new DateTime($reservaoriginal['devolucao']);

					$ativacaooriginal = parent::Ativacao($reservaoriginal['reid']);
					if ($ativacaooriginal['ativa']=='S') {
						// todos os dias originalmente reservados
						$i=$comeco_original;
						while ($i<=$conclusao_original) {
							$dias_originais[] = $i->format('Y-m-d');
							$i->modify('+1 day');
						} // for
					} // ativa
				} else {
					$comeco_original = new DateTime($aluguel['inicio']);
					$conclusao_original = new DateTime($aluguel['devolucao']);

					// todos os dias originalmente reservados
					$i=$comeco_original;
					while ($i<=$conclusao_original) {
						$dias_originais[] = $i->format('Y-m-d');
						$i->modify('+1 day');
					} // for
				}// se é uma reserva

				$veiculo = parent::Veiculo($aluguel['vid']);
				$disponibilidade_veiculo = $this->Possibilidade($veiculo['vid']);
				$disponibilidade = $disponibilidade_veiculo['status'];

				// vê os dias reservados de todos os alugueis desse veículo
				$consultaralugueis = parent::ListaAlugueisVeiculo($veiculo['vid']);
				if ($consultaralugueis[0]['aid']!=0) {
					foreach ($consultaralugueis as $aluguel) {
						$consultadevolucao = parent::Devolucao($aluguel['aid']);
						if ($consultadevolucao['deid']==0) {
							$consultareserva = parent::Reserva($aluguel['aid']);
							if ($consultareserva['reid']!=0) {
								$inicio_aluguel = new DateTime($consultareserva['inicio']);
								$devolucao_aluguel = new DateTime($consultareserva['devolucao']);

								$ativacao = parent::Ativacao($consultareserva['reid']);
								if ($ativacao['ativa']=='S') {
									$i=$inicio_aluguel;
									while ($i<=$devolucao_aluguel) {
										$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
										$i->modify('+1 day');
									} // for
								} // ativa
							} else {
								$inicio_aluguel = new DateTime($aluguel['inicio']);
								$devolucao_aluguel = new DateTime($aluguel['devolucao']);

								$i=$inicio_aluguel;
								while ($i<=$devolucao_aluguel) {
									$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
									$i->modify('+1 day');
								} // for
							}// se é uma reserva
						} // se devolveu o veículo
					} // foreach aluguel
				} // se tem aluguel do veículo

				// todos os dias que a modificacao quer reservar agora
				$contador = 0;
				for ($possivel=$comeco_mod;$possivel<=$conclusao_mod;$possivel->format('+1 day')) {
					$modificacoes[] = $possivel->format('Y-m-d');
					$possivel->modify('+1 day');
					$contador++;
				} // for
				$possivel->modify('-'.$contador.' days');

				// dias_reservados_do_veiculo = todos os dias reservados do veículo
				$dias_reservados_do_veiculo = array_values(array_unique($dias_reservados_do_veiculo, SORT_REGULAR));
				// dias_originais = dias que tavam reservados nessa reserva (para a qual está sendo solicitada a modificação)
				$dias_originais = array_values(array_unique($dias_originais, SORT_REGULAR));
				// dias_dessa_reserva = dias que tavam reservados nessa reserva (para a qual está sendo solicitada a modificação)
				$dias_dessa_reserva =  array_intersect($dias_reservados_do_veiculo,$dias_originais);
				// modificacoes = todos os dias que vão ser os dias da reserva depois da modificacao de agora
				$dias_possiveis = array_intersect($dias_dessa_reserva,$dias_reservados_do_veiculo);
				// dias que são de outra reserva
				$dias_de_outra_reserva = array_diff($dias_reservados_do_veiculo,$dias_dessa_reserva);
				// dias dessa modificação que são de outra reserva
				$dias_agendados = array_intersect($dias_de_outra_reserva,$modificacoes);
				$dias_agendados = array_values(array_unique($dias_agendados, SORT_REGULAR));

				return $this->possibilidade = $dias_agendados;
			} // ModificacaoPossivel

			public function ModificacaoVeiculoPossivel($aid,$vid) {
				$this->data_de_hoje = new DateTime(date_create()->format('Y-m-d H:i:s.u'));
				$dias_reservados_do_veiculo = [];
				$dias_originais = [];

				$aluguel = parent::AluguelInfo($aid);
				$reservaoriginal = parent::Reserva($aluguel['aid']);
				if ($reservaoriginal['reid']!=0) {
					$comeco_original = new DateTime($reservaoriginal['inicio']);
					$conclusao_original = new DateTime($reservaoriginal['devolucao']);

					$ativacaooriginal = parent::Ativacao($reservaoriginal['reid']);
					if ($ativacaooriginal['ativa']=='S') {
						// todos os dias originalmente reservados
						$i=$comeco_original;
						while ($i<=$conclusao_original) {
							$dias_originais[] = $i->format('Y-m-d');
							$i->modify('+1 day');
						} // for
					} // ativa
				} else {
					$comeco_original = new DateTime($aluguel['inicio']);
					$conclusao_original = new DateTime($aluguel['devolucao']);

					// todos os dias originalmente reservados
					$i=$comeco_original;
					while ($i<=$conclusao_original) {
						$dias_originais[] = $i->format('Y-m-d');
						$i->modify('+1 day');
					} // for
				}// se é uma reserva

				// vê os dias reservados de todos os alugueis desse veículo
				$consultaralugueis = parent::ListaAlugueisVeiculo($vid);
				if ($consultaralugueis[0]['aid']!=0) {
					foreach ($consultaralugueis as $aluguel) {
						$consultadevolucao = parent::Devolucao($aluguel['aid']);
						if ($consultadevolucao['deid']==0) {
							$consultareserva = parent::Reserva($aluguel['aid']);
							if ($consultareserva['reid']!=0) {
								$inicio_aluguel = new DateTime($consultareserva['inicio']);
								$devolucao_aluguel = new DateTime($consultareserva['devolucao']);

								$ativacao = parent::Ativacao($consultareserva['reid']);
								if ($ativacao['ativa']=='S') {
									$i=$inicio_aluguel;
									while ($i<=$devolucao_aluguel) {
										$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
										$i->modify('+1 day');
									} // for
								} // ativa
							} else {
								$inicio_aluguel = new DateTime($aluguel['inicio']);
								$devolucao_aluguel = new DateTime($aluguel['devolucao']);

								$i=$inicio_aluguel;
								while ($i<=$devolucao_aluguel) {
									$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
									$i->modify('+1 day');
								} // for
							}// se é uma reserva
						} // se devolveu o veículo
					} // foreach aluguel
				} // se tem aluguel do veículo

				// dias_reservados_do_veiculo = todos os dias reservados do veículo
				$dias_reservados_do_veiculo = array_values(array_unique($dias_reservados_do_veiculo, SORT_REGULAR));
				// dias_originais = dias que tavam reservados nessa reserva (para a qual está sendo solicitada a modificação)
				$dias_originais = array_values(array_unique($dias_originais, SORT_REGULAR));
				// dias dessa reserva que são de outra reserva do veículo
				$dias_agendados = array_intersect($dias_originais,$dias_reservados_do_veiculo);
				$dias_agendados = array_values(array_unique($dias_agendados, SORT_REGULAR));

				return $this->possibilidade = $dias_agendados;
			} // ModificacaoVeiculoPossivel

			public function ModificacaoManutencaoPossivel($mid,$comeco_mod,$conclusao_mod) {
				$this->data_de_hoje = new DateTime(date_create()->format('Y-m-d H:i:s.u'));
				$dias_reservados_do_veiculo = [];


				$manutencao = parent::Manutencao($mid);
				$comeco_original = new DateTime($manutencao['inicio']);
				$conclusao_original = new DateTime($manutencao['devolucao']);

				$reservamanutencao = parent::ManutencaoReserva($mid);
				if ($reservamanutencao['mreid']!=0) {
					if ($reservamanutencao['ativa']=='S') {
						$comeco_original = new DateTime($reservamanutencao['inicio']);
						$conclusao_original = new DateTime($reservamanutencao['devolucao']);
					} // se ativa
				} // reserva manutencao

				// todos os dias originalmente reservados
				$i=$comeco_original;
				while ($i<=$conclusao_original) {
					$dias_originais[] = $i->format('Y-m-d');
					$i->modify('+1 day');
				} // for

				$veiculo = parent::Veiculo($manutencao['vid']);
				$disponibilidade_veiculo = $this->Possibilidade($veiculo['vid']);
				$disponibilidade = $disponibilidade_veiculo['status'];

				// vê os dias reservados de todos os alugueis desse veículo
				$consultaralugueis = parent::ListaAlugueisVeiculo($veiculo['vid']);
				if ($consultaralugueis[0]['aid']!=0) {
					foreach ($consultaralugueis as $aluguel) {
						$consultadevolucao = parent::Devolucao($aluguel['aid']);
						if ($consultadevolucao['deid']==0) {
							$consultareserva = parent::Reserva($aluguel['aid']);
							if ($consultareserva['reid']!=0) {
								$inicio_aluguel = new DateTime($consultareserva['inicio']);
								$devolucao_aluguel = new DateTime($consultareserva['devolucao']);

								$ativacao = parent::Ativacao($consultareserva['reid']);
								if ($ativacao['ativa']=='S') {
									$i=$inicio_aluguel;
									while ($i<=$devolucao_aluguel) {
										$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
										$i->modify('+1 day');
									} // for
								} // ativa
							} else {
								$inicio_aluguel = new DateTime($aluguel['inicio']);
								$devolucao_aluguel = new DateTime($aluguel['devolucao']);

								$i=$inicio_aluguel;
								while ($i<=$devolucao_aluguel) {
									$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
									$i->modify('+1 day');
								} // for
							}// se é uma reserva
						} // se devolveu o veículo
					} // foreach aluguel
				} // se tem aluguel do veículo

				// todos os dias que a modificacao quer reservar agora
				$contador = 0;
				for ($possivel=$comeco_mod;$possivel<=$conclusao_mod;$possivel->format('+1 day')) {
					$modificacoes[] = $possivel->format('Y-m-d');
					$possivel->modify('+1 day');
					$contador++;
				} // for
				$possivel->modify('-'.$contador.' days');

				// dias_reservados_do_veiculo = todos os dias reservados do veículo
				$dias_reservados_do_veiculo = array_values(array_unique($dias_reservados_do_veiculo, SORT_REGULAR));
				// dias_originais = dias que tavam reservados nessa reserva (para a qual está sendo solicitada a modificação)
				$dias_originais = array_values(array_unique($dias_originais, SORT_REGULAR));
				// dias_dessa_reserva = dias que tavam reservados nessa reserva (para a qual está sendo solicitada a modificação)
				$dias_dessa_reserva =  array_intersect($dias_reservados_do_veiculo,$dias_originais);
				// modificacoes = todos os dias que vão ser os dias da reserva depois da modificacao de agora
				$dias_possiveis = array_intersect($dias_dessa_reserva,$dias_reservados_do_veiculo);
				// dias que são de outra reserva
				$dias_de_outra_reserva = array_diff($dias_reservados_do_veiculo,$dias_dessa_reserva);
				// dias dessa modificação que são de outra reserva
				$dias_agendados = array_intersect($dias_de_outra_reserva,$modificacoes);
				$dias_agendados = array_values(array_unique($dias_agendados, SORT_REGULAR));

				return $this->possibilidade = $dias_agendados;
			} // ModificacaoManutencaoPossivel

			public function AluguelPossivel($vid,$comeco,$conclusao) {
				$this->data_de_hoje = new DateTime(date_create()->format('Y-m-d H:i:s.u'));
				$dias_reservados_do_veiculo = [];
				$intencoes = [];

				$veiculo = parent::Veiculo($vid);
				$disponibilidade_veiculo = $this->Possibilidade($veiculo['vid']);
				$disponibilidade = $disponibilidade_veiculo['status'];

				// vê os dias reservados de todos os alugueis desse veículo
				$consultaralugueis = parent::ListaAlugueisVeiculo($veiculo['vid']);
				if ($consultaralugueis[0]['aid']!=0) {
					foreach ($consultaralugueis as $aluguel) {
						$consultadevolucao = parent::Devolucao($aluguel['aid']);
						if ($consultadevolucao['deid']==0) {
							$consultareserva = parent::Reserva($aluguel['aid']);
							if ($consultareserva['reid']!=0) {
								$inicio_aluguel = new DateTime($consultareserva['inicio']);
								$devolucao_aluguel = new DateTime($consultareserva['devolucao']);

								$devolucao_aluguel = $this->DevolucaoAtualizada($devolucao_aluguel);

								$ativacao = parent::Ativacao($consultareserva['reid']);
								if ($ativacao['ativa']=='S') {
									$i=$inicio_aluguel;
									while ($i<=$devolucao_aluguel) {
										$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
										$i->modify('+1 day');
									} // for
								} // ativa
							} else {
								$inicio_aluguel = new DateTime($aluguel['inicio']);
								$devolucao_aluguel = new DateTime($aluguel['devolucao']);

								$devolucao_aluguel = $this->DevolucaoAtualizada($devolucao_aluguel);

								$i=$inicio_aluguel;
								while ($i<=$devolucao_aluguel) {
									$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
									$i->modify('+1 day');
								} // for
							} // se é uma reserva

						} // se devolveu o veículo
					} // foreach aluguel
				} // se tem aluguel do veículo

				$manutencoesativas = parent::ManutencoesAtivas($vid);
				if ($manutencoesativas[0]['mid']!=0) {
					foreach ($manutencoesativas as $manutencao) {
						$mreidatual = parent::ManutencaoReserva($manutencao['mid']);
						if ($mreidatual['mreid']!=0) {
							$inicio_manutencao = new DateTime($mreidatual['inicio']);
							$devolucao_manutencao = new DateTime($mreidatual['devolucao']);
							if ($mreidatual['ativa']=='S') {
								if ( ($inicio_manutencao->format('Y-m-d H:i'))<($this->data_de_hoje->format('Y-m-d H:i')) ) {
									if ($consultareserva['confirmada']==1) {
										$inicio_manutencao = new DateTime($manutencao['reserva_inicio']);
										$devolucao_manutencao = new DateTime($manutencao['reserva_devolucao']);

										$devolucao_manutencao = $this->DevolucaoAtualizada($devolucao_manutencao);

										$i=$inicio_manutencao;
										while ($i<=$devolucao_manutencao) {
											$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
											$i->modify('+1 day');
										} // for
									} // se confirmou
								} else {
									$inicio_manutencao = new DateTime($manutencao['inicio']);
									$devolucao_manutencao = new DateTime($manutencao['devolucao']);

									$devolucao_manutencao = $this->DevolucaoAtualizada($devolucao_manutencao);

									$i=$inicio_manutencao;
									while ($i<=$devolucao_manutencao) {
										$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
										$i->modify('+1 day');
									} // for
								} // se já deu a data do início
							} // ativa
						} else {
							$inicio_manutencao = new DateTime($manutencao['inicio']);
							$devolucao_manutencao = new DateTime($manutencao['devolucao']);

							$devolucao_manutencao = $this->DevolucaoAtualizada($devolucao_manutencao);

							$i=$inicio_manutencao;
							while ($i<=$devolucao_manutencao) {
								$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
								$i->modify('+1 day');
							} // for
						} // se foi reserva
					} // foreach
				} // id do agendamento

				// // vê se tá na manutenção
				// $possibilidademanutencao = $this->PossibilidadeManutencao($vid);
				// $inicio_status = $possibilidademanutencao['inicio_status'];
				// $devolucao_status = $possibilidademanutencao['devolucao_status'];
				// if ($possibilidademanutencao['mid']!=0) {
				// 	$retorno = parent::Retorno($possibilidademanutencao['mid']);
				// 	if ($retorno['data']!=0) {
				// 		$devolucao_status = new DateTime($retorno['data']);
				// 	} else {
				// 		$i=$inicio_aluguel;
				// 		while ($i<=$devolucao_aluguel) {
				// 			$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
				// 			$i->modify('+1 day');
				// 		} // for
				// 	} // retorno
				// } // mid

				// todos os dias que a modificacao quer reservar agora
				$contador = 0;
				for ($possivel=$comeco;$possivel<=$conclusao;$possivel->format('+1 day')) {
					$intencoes[] = $possivel->format('Y-m-d');
					$possivel->modify('+1 day');
					$contador++;
				} // for
				$possivel->modify('-'.$contador.' days');

				// dias_reservados_do_veiculo = todos os dias reservados do veículo
				$dias_reservados_do_veiculo = array_values(array_unique($dias_reservados_do_veiculo, SORT_REGULAR));
				// dias dessa modificação que são de outra reserva
				$dias_agendados = array_intersect($dias_reservados_do_veiculo,$intencoes);
				$dias_agendados = array_values(array_unique($dias_agendados, SORT_REGULAR));

				return $this->possibilidade = $dias_agendados;
			} // AluguelPossivel

			public function DiasDesejados($vid,$comeco,$conclusao) {
				$this->data_agora = date_create()->format('Y-m-d H:i:s.u');
				$this->data_de_hoje = new DateTime($this->data_agora);
				$dias_indisponiveis_do_veiculo = [];
				$dias_agendados = [];
				$intencoes = [];

				$veiculo = parent::Veiculo($vid);
				$disponibilidade_veiculo = $this->Possibilidade($veiculo['vid']);
				$disponibilidade = $disponibilidade_veiculo['status'];

				$consultaralugueis = parent::ListaAlugueisVeiculo($veiculo['vid']);
				if ($consultaralugueis[0]['aid']!=0) {
					foreach ($consultaralugueis as $aluguel) {
						$consultadevolucao = parent::Devolucao($aluguel['aid']);
						if ($consultadevolucao['deid']==0) {
							$consultareserva = parent::Reserva($aluguel['aid']);
							if ($consultareserva['reid']!=0) {
								$ativacao = parent::Ativacao($consultareserva['reid']);
								if ($ativacao['ativa']=='S') {
									$inicio_aluguel = new DateTime($consultareserva['inicio']);
									$devolucao_aluguel = new DateTime($consultareserva['devolucao']);
									if ( ($inicio_aluguel->format('Y-m-d H:i'))<($this->data_de_hoje->format('Y-m-d H:i')) ) {
										if ($consultareserva['confirmada']==1) {
											$inicio_aluguel = new DateTime($consultareserva['inicio']);
											$devolucao_aluguel = new DateTime($consultareserva['devolucao']);

											$devolucao_aluguel = $this->DevolucaoAtualizada($devolucao_aluguel);

											$i=$inicio_aluguel;
											while ($i<=$devolucao_aluguel) {
												$dias_indisponiveis_do_veiculo[] = $i->format('Y-m-d');
												$i->modify('+1 day');
											} // for
										} // se confirmou
									} else {
										$inicio_aluguel = new DateTime($consultareserva['inicio']);
										$devolucao_aluguel = new DateTime($consultareserva['devolucao']);

										$devolucao_aluguel = $this->DevolucaoAtualizada($devolucao_aluguel);

										$i=$inicio_aluguel;
										while ($i<=$devolucao_aluguel) {
											$dias_indisponiveis_do_veiculo[] = $i->format('Y-m-d');
											$i->modify('+1 day');
										} // for
									} // se já era pra ter começado a reserva
								} // ativa
							} else {
								$inicio_aluguel = new DateTime($aluguel['inicio']);
								$devolucao_aluguel = new DateTime($aluguel['devolucao']);

								$devolucao_aluguel = $this->DevolucaoAtualizada($devolucao_aluguel);

								$i=$inicio_aluguel;
								while ($i<=$devolucao_aluguel) {
									$dias_indisponiveis_do_veiculo[] = $i->format('Y-m-d');
									$i->modify('+1 day');
								} // for
							} // se é uma reserva

						} // se devolveu o veículo
					} // foreach aluguel
				} // se tem aluguel do veículo

				// vê se tá na manutenção
				$manutencoesativas = parent::ManutencoesAtivas($vid);
				if ($manutencoesativas[0]['mid']!=0) {
					foreach ($manutencoesativas as $manutencao) {
						$mreidatual = parent::ManutencaoReserva($manutencao['mid']);
						if ($mreidatual['mreid']!=0) {
							$inicio_manutencao = new DateTime($mreidatual['inicio']);
							$devolucao_manutencao = new DateTime($mreidatual['devolucao']);
							if ($mreidatual['ativa']=='S') {
								if ( ($inicio_manutencao->format('Y-m-d H:i'))<($this->data_de_hoje->format('Y-m-d H:i')) ) {
									if ($consultareserva['confirmada']==1) {
										$inicio_manutencao = new DateTime($manutencao['reserva_inicio']);
										$devolucao_manutencao = new DateTime($manutencao['reserva_devolucao']);

										$devolucao_manutencao = $this->DevolucaoAtualizada($devolucao_manutencao);

										$i=$inicio_manutencao;
										while ($i<=$devolucao_manutencao) {
											$dias_indisponiveis_do_veiculo[] = $i->format('Y-m-d');
											$i->modify('+1 day');
										} // for
									} // se confirmou
								} else {
									$inicio_manutencao = new DateTime($manutencao['inicio']);
									$devolucao_manutencao = new DateTime($manutencao['devolucao']);

									$devolucao_manutencao = $this->DevolucaoAtualizada($devolucao_manutencao);

									$i=$inicio_manutencao;
									while ($i<=$devolucao_manutencao) {
										$dias_indisponiveis_do_veiculo[] = $i->format('Y-m-d');
										$i->modify('+1 day');
									} // for
								} // se já deu a data do início
							} // ativa
						} else {
							$inicio_manutencao = new DateTime($manutencao['inicio']);
							$devolucao_manutencao = new DateTime($manutencao['devolucao']);

							$devolucao_manutencao = $this->DevolucaoAtualizada($devolucao_manutencao);

							$i=$inicio_manutencao;
							while ($i<=$devolucao_manutencao) {
								$dias_indisponiveis_do_veiculo[] = $i->format('Y-m-d');
								$i->modify('+1 day');
							} // for
						} // se foi reserva
					} // foreach
				} // id do agendamento

				// todos os dias que a modificacao quer reservar agora
				$contador=0;
				for ($possivel=$comeco;$possivel<=$conclusao;$possivel->format('+1 day')) {
					$intencoes[] = $possivel->format('Y-m-d');
					$possivel->modify('+1 day');
					$contador++;
				} // for
				// restabelece a data do possivel
				$possivel->modify('-'.$contador.' days');

				// dias_reservados_do_veiculo + dias em manutenção
				$dias_indisponiveis_do_veiculo = array_values(array_unique($dias_indisponiveis_do_veiculo, SORT_REGULAR));
				// dias dessa modificação que são de outra reserva
				$dias_agendados = array_intersect($dias_indisponiveis_do_veiculo,$intencoes);
				$dias_agendados = array_values(array_unique($dias_agendados, SORT_REGULAR));

				return $this->possibilidade = $dias_agendados;
			} // DiasDesejados

			public function AluguelAtual($vid) {
				$this->data_de_hoje = new DateTime(date_create()->format('Y-m-d H:i:s.u'));
				$dias_reservados_do_veiculo = [];
				$dias_agendados = [];
				$alugueis = [];

				$veiculo = parent::Veiculo($vid);

				$consultaralugueis = parent::ListaAlugueisVeiculo($veiculo['vid']);
				if ($consultaralugueis[0]['aid']!=0) {
					foreach ($consultaralugueis as $aluguel) {
						$consultadevolucao = parent::Devolucao($aluguel['aid']);
						if ($consultadevolucao['deid']==0) {
							$consultareserva = parent::Reserva($aluguel['aid']);
							if ($consultareserva['reid']!=0) {
								$inicio_aluguel = new DateTime($consultareserva['inicio']);
								$devolucao_aluguel = new DateTime($consultareserva['devolucao']);
								$devolucao_aluguel = $this->DevolucaoAtualizada($devolucao_aluguel);

								$ativacao = parent::Ativacao($consultareserva['reid']);
								if ($ativacao['ativa']=='S') {
									if ($consultareserva['confirmada']==1) {
										$i=$inicio_aluguel;
										while ($i<=$devolucao_aluguel) {
											$alugueis[$i->format('Y-m-d')] = $aluguel['aid'];
											$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
											$i->modify('+1 day');
										} // for
									} // confirmada
								} // ativa
							} else {
								$inicio_aluguel = new DateTime($aluguel['inicio']);
								$devolucao_aluguel = new DateTime($aluguel['devolucao']);
								$devolucao_aluguel = $this->DevolucaoAtualizada($devolucao_aluguel);

								$i=$inicio_aluguel;
								while ($i<=$devolucao_aluguel) {
									$alugueis[$i->format('Y-m-d')] = $aluguel['aid'];
									$dias_reservados_do_veiculo[] = $i->format('Y-m-d');
									$i->modify('+1 day');
								} // for
							}// se é uma reserva
						} // se devolveu o veículo
					} // foreach aluguel
				} // se tem aluguel do veículo

				$aluguel_atual = $alugueis[$this->data_de_hoje->format('Y-m-d')]??0;

				return $this->aluguel_atual = $aluguel_atual;
			} // AluguelAtual

			public function DiaLegivelReserva($aid) {
				$this->data = date_create()->format('Y-m-d H:i:s.u');
				$this->data_de_hoje = new DateTime($this->data);

				$reserva = parent::Reserva($aid);
				$inicio = new DateTime($reserva['inicio']);
				$devolucao = new DateTime($reserva['devolucao']);

				if ($this->data_de_hoje->diff($inicio)->format('%a')<1) {
					//$disponibilidade = 'Reservado para amanhã';
				} else if ($this->data_de_hoje->diff($inicio)->format('%a')<2) {
					//$disponibilidade = 'Reservado para depois de amanhã';
					//$disponibilidade = 'Reservado para daqui a '.$this->data_de_hoje->diff($inicio)->format('%a').' dia(s) e '.$this->data_de_hoje->diff($inicio)->format('%H').'h';
				} else if ($this->data_de_hoje->diff($inicio)->format('%a')<7) {
					//$disponibilidade = 'Reservado para essa semana, de '.strftime('%A', strtotime($reserva['inicio'])).' ('.$inicio->format('d').') às '.$inicio->format('H').'h até '.$devolucao->format('d/m');
					//$disponibilidade = 'Reservado para essa semana';
					$disponibilidade = 'Reservado';
				} else if ( ($this->data_de_hoje->diff($inicio)->format('%a')>7) && ($this->data_de_hoje->diff($inicio)->format('%a')<30)) {
					//$disponibilidade = 'Reservado para esse mês, de '.$inicio->format('d/m').'  às '.$inicio->format('H').'h até '.$devolucao->format('d/m');
					//$disponibilidade = 'Reservado para esse mês';
					//$disponibilidade = 'Reservado';
				} // quando no futuro é a reserva

				return $this->disponibilidade = $disponibilidade;
			} // DiaLegivelReserva

			public function Paginacao($array) {
				global $_GET;

				$filtroVal = !empty($_GET['filtro']) ? (int) $_GET['filtro'] : 1;
				$pagina = !empty($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
				$porPagina = !empty($_GET['itens']) ? (int) $_GET['itens'] : 10;
				$total = count($array); //total items in array
				$totalDePaginas = ceil($total/$porPagina); //calculate total pages
				$pagina = max($pagina, 1); //get 1 page when $_GET['page'] <= 0
				$pagina = min($pagina, $totalDePaginas); //get last page when $_GET['page'] > $totalPages
				$offset = ($pagina - 1) * $porPagina;
				if ($offset<0) {
					$offset = 0;
				} // offset

				/******  build the pagination links ******/
				// range of num links to show
				$range = 3;
				$quantidadeAtual = $offset+$porPagina;
				($quantidadeAtual>=$total) ? $quantidadeAtual = $total : $quantidadeAtual = $quantidadeAtual;
				$quantidadeAnterior = $offset+1;
				// if not on page 1, don't show back links
				$botoes = "
					<div style='min-width:100%;max-width:100%;float:left;margin-bottom:21px;margin-top:21px;'>
						<table>
							<tr>
				";

				if ($pagina > 1) {
					// show << link to go back to page 1
					$botoes .= "
								<td class='link-pagina'>
									<a class='link-pagina' href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?pagina=1&filtro=".$filtroVal."&itens=".$porPagina."'>
										<p class='link-pagina'>
											<<
										</p>
									</a>
								</td>
					";

					// get previous page num
					$anterior = $pagina - 1;

					// show < link to go back to 1 page
					$botoes .= "
								<td class='link-pagina'>
									<a class='link-pagina' href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?pagina=".$anterior."&filtro=".$filtroVal."&itens=".$porPagina."'>
										<p class='link-pagina'>
											<
										</p>
									</a>
								</td>
					";

				} // end if

				// loop to show links to range of pages around current page
				for ($x = ($pagina - $range); $x < (($pagina + $range) + 1); $x++) {
					// if it's a valid page number...
					if (($x > 0) && ($x <= $totalDePaginas)) {
						// if we're on current page...
						if ($x == $pagina) {
							// 'highlight' it but don't make a link
							$botoes .= "
								<td class='link-pagina paginaatual'>
									<p class='link-pagina paginaatual'>
										".$x."
									</p>
								</td>
							";
						} else { // if not current page...
							// make it a link
							$botoes .= "
								<td class='link-pagina'>
									<a class='link-pagina' href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?pagina=".$x."&filtro=".$filtroVal."&itens=".$porPagina."'>
										<p class='link-pagina'>
											".$x."
										</p>
									</a>
								</td>
							";
						} // end else
					} // end if
				} // end for

				// if not on last page, show forward and last page links
				if ($pagina != $totalDePaginas) {
					// get next page
					$proxima = $pagina + 1;

					if ($totalDePaginas > 1) {
						// echo forward link for next page, mas só se tiver mais que 1 página
						$botoes .= "
									<td class='link-pagina'>
										<a class='link-pagina' href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?pagina=".$proxima."&filtro=".$filtroVal."&itens=".$porPagina."'>
											<p class='link-pagina'>
												>
											</p>
										</a>
									</td>
						";
					} // maximo > 1

					if ($totalDePaginas > 1) {
						// echo forward link for lastpage
						$botoes .= "
									<td class='link-pagina'>
										<a class='link-pagina' href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?pagina=".$totalDePaginas."&filtro=".$filtroVal."&itens=".$porPagina."'>
											<p class='link-pagina'>
												>>
											</p>
										</a>
									</td>
						";
					} // total > 1

				} // end if

				$botoes .= "
							</tr>
						</table>
						<p style='min-width:100%;max-width:100%;margin-top:13px;font-size:13px;display:inline-block;text-align:center;'>".$quantidadeAnterior."-".$quantidadeAtual." de ".$total." registro(s)</p>
						<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:1%;'>
							<p style='display:inline-block;font-size:13px;'>(exibindo</p>
							<select id='porpagina' class='filtro' style='display:inline-block;min-width:90px;max-width:90px;padding:1px 3px;vertical-align:bottom;'>
								<option value='5'>5</option>
								<option value='10'>10</option>
								<option value='25'>25</option>
								<option value='50'>50</option>
								<option value='100'>100</option>
							</select>
							<p style='display:inline-block;font-size:13px;'>por página)</p>
						</div>
					</div>
				";

				$botoes .= "
					<script>
						$('#porpagina').val('".$porPagina."');
						$('#porpagina').on('change', function() {
							if ($(this).val()!=".$porPagina.") {
								 window.location.href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?pagina=".$pagina."&filtro=".$filtroVal."&itens='+$(this).val();
							}
						});
					</script>
				";
				/****** end build pagination links ******/

				$array = array_slice($array, $offset, $porPagina);
				return $paginacao = array(
					'itens'=>$array,
					'botoes'=>$botoes,
					'totalDePaginas'=>$totalDePaginas,
					'porPagina'=>$porPagina
				);
			} // Paginacao

			public function Exibicao($array) {
				global $_GET;

				$filtroVal = !empty($_GET['filtro']) ? (int) $_GET['filtro'] : 1;
				$pagina = !empty($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
				$porPagina = !empty($_GET['itens']) ? (int) $_GET['itens'] : 5;
				$total = count($array); //total items in array
				$totalDePaginas = ceil($total/$porPagina); //calculate total pages
				$pagina = max($pagina, 1); //get 1 page when $_GET['page'] <= 0
				$pagina = min($pagina, $totalDePaginas); //get last page when $_GET['page'] > $totalPages

				$itens = [];
				$i=0;
				if ($filtroVal==1) {
					// todos os aluguéis
					foreach ($array as $item) {
						$i++;
						$itens[] = $item;
					} // foreach
				} else if ($filtroVal==2) {
					// apenas associados
					foreach ($array as $item) {
						$associado = parent::Associado($item['lid']);
						if ($associado['associado']=='S') {
							$i++;
							$itens[] = $item;
						} // associado = s
					} // foreach
				} else if ($filtroVal==3) {
					// apenas aluguéis particulares
					foreach ($array as $item) {
						$associado = parent::Associado($item['lid']);
						if ($associado['associado']=='N') {
							$i++;
							$itens[] = $item;
						} // associado = s
					} // foreach
				} // get filtro

				$botoes = '';
				// $botoes = "
				// 	<div style='min-width:100%;max-width:100%;display:inline-block;margin:1.8% auto;'>
				// 		<p style='display:inline-block;font-size:13px;'>exibindo</p>
				// 		<select id='exibicao' class='filtro' style='display:inline-block;min-width:55%;max-width:55%;padding:1px 3px;vertical-align:bottom;'>
				// 			<option value='1'>todos</option>
				// 			<option value='2'>apenas associados</option>
				// 			<option value='3'>apenas particulares</option>
				// 		</select>
				// 	</div>
				// 	<script>
				// 		$('#exibicao').val('".$filtroVal."');
				// 		$('#exibicao').on('change', function() {
				// 			if ($(this).val()!=".$filtroVal.") {
				// 				window.location.href='http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/?pagina=".$pagina."&filtro='+$(this).val()+'&itens=".$porPagina."';
				// 			}
				// 		});
				// 	</script>
				// ";

				return $exibicao = array(
					'botoes'=>$botoes,
					'filtro'=>$filtroVal,
					'itens'=>$itens,
					'i'=>$i
				);

			} // Exibicao

			public function FormatoTelefone($telefone,$formato='') {
				$telefone = str_split(str_replace(array('(',')',' ','-'),'',$telefone));
				($formato=='br') ? $padrao = '(%%) %%%%%-%%%%' : $padrao = '(%%) %%%-%%%-%%%';
				$telefone = vsprintf(str_replace('%', '%s', $padrao), $telefone);

				return $telefone;
			} // FormatoTelefone

			public function FormatoPlaca($placa) {
				$placa = str_split(str_replace(array('(',')',' ','-'),'',$placa));
				$padrao = '%%%-%%%%';
				$placa = mb_strtoupper(vsprintf(str_replace('%', '%s', $padrao), $placa));

				return $placa;
			} // FormatoPlaca

			public function FormatoNascimento($nascimento) {
				$nascimento = str_split(str_replace(array('(',')',' ','-','/','.'),'',$nascimento));
				$padrao = '%%/%%/%%%%';
				$nascimento = mb_strtoupper(vsprintf(str_replace('%', '%s', $padrao), $nascimento));

				return $nascimento;
			} // FormatoNascimento

			public function FormatoCPF($cpf) {
				$cpf = str_split(str_replace(array('(',')',' ','-','/','.'),'',$cpf));
				$padrao = '%%%.%%%.%%%-%%';
				$cpf = mb_strtoupper(vsprintf(str_replace('%', '%s', $padrao), $cpf));

				return $cpf;
			} // FormatoCPF

			public function DisponivelPeriodo($inicio,$devolucao) {
					$sql = "
						SELECT vid
						FROM veiculo
						WHERE veiculo.vid NOT IN (
								SELECT veiculo.vid
								FROM aluguel
								INNER JOIN veiculo
									ON veiculo.vid=aluguel.vid
								WHERE aluguel.inicio > ?
								AND aluguel.devolucao < ?
							)
							AND veiculo.vid NOT IN (
									SELECT reserva.reid
									FROM reserva
									INNER JOIN ativacao
										ON ativacao.reid=reserva.reid
									INNER JOIN aluguel
										ON aluguel.aid=reserva.aid
									INNER JOIN veiculo
										ON veiculo.vid=aluguel.vid
									WHERE reserva.inicio > ?
									AND reserva.devolucao < ?
									AND ativacao.ativa='S'
								)
						ORDER BY veiculo.vid
						DESC
					";
					$stmt = $this->conectar()->prepare($sql);
					$stmt->execute([$inicio,$devolucao,$inicio,$devolucao]);
					$disponivel = $stmt->fetchAll();
					if (count($disponivel)>=1) {
						foreach ($disponivel as $veiculo) {
							$resultado[] = array(
								'vid'=>$this->vid = $veiculo['vid']??0
							);
						} // foreach
					} else {
						$resultado[] = array(
							'vid'=>$this->vid = 0
						);
					}
					return $this->resultado = $resultado;
			} // DisponivelPeriodo

			public function DatasAtualizadas($aid) {
				$reserva = parent::Reserva($aid);
				if ($reserva['reid']!=0) {
					$ativacao = parent::Ativacao($reserva['reid']);
					if ($ativacao['ativa']=='S') {
						$consultadevolucao = parent::Devolucao($reserva['aid']);
						if ($consultadevolucao['deid']==0) {
							$inicio = new DateTime($reserva['inicio']);
							$devolucao =new DateTime($reserva['devolucao']);
							$devolucao = $this->DevolucaoAtualizada($devolucao);
						} // sem devolver ainda
					} // reserva ativa
				} else {
					$aluguel = parent::AluguelInfo($aid);
					$consultadevolucao = parent::Devolucao($aluguel['aid']);
					if ($consultadevolucao['deid']==0) {
						$inicio = new DateTime($aluguel['inicio']);
						$devolucao = new DateTime($aluguel['devolucao']);
						$devolucao = $this->DevolucaoAtualizada($devolucao);
					} // sem devolver ainda
				} // é reserva

				return $this->datas_atualizadas = array(
					'inicio'=>$inicio,
					'devolucao'=>$devolucao
				);
			} // DatasAtualizadas

			public function SomaParciais($coid) {
				$somaparciais = 0;
				$parciais = parent::CobrancaParcial($coid);
				if ($parciais[0]['coid']!=0) {
					foreach($parciais as $pagamento) {
						$somaparciais += $pagamento['valor'];
					} // pagamento
				} // parciais > 0

				return $this->somatoria = $somaparciais;
			} // SomaParciais

			public function SomaPagamentosAluguel($aid) {
				$pagamentosparciais = 0;

				$aluguel = parent::AluguelInfo($aid);
				$valorinicial = $aluguel['valor'];

				$parciais = parent::PagamentosParciais($aid);
				if ($parciais[0]['papid']!=0) {
					foreach ($parciais as $pagamento) {
						$pagamentosparciais += $pagamento['valor'];
					} // foreach
				} // papid > 0

				$total = $valorinicial+$pagamentosparciais;
				return $this->total = $total;
			} // PagamentosAluguel

			public function Residual($coid) {
				$residual = 0;
				$cobranca = parent::Cobranca($coid);
				if ($cobranca['tid']!=0) {
					$data_pagamento = new DateTime($cobranca['data_pagamento']);
					$status = 'Pago dia '.$data_pagamento->format('d/m/Y');
				} else {
					$data_pagamento = new DateTime($cobranca['data_cobranca']);
					($cobranca['forma']==0) ? $status = 'À pagar' : $status = 'Pago dia '.$data_pagamento->format('d/m/Y');

					$somaparciais = $this->SomaParciais($cobranca['coid']);
					$pagamentosaluguel = $this->SomaPagamentosAluguel($cobranca['aid']);

					if ($somaparciais!=$cobranca['valor']) {
						$residual = $cobranca['valor']-$somaparciais;
					}

					if ($cobranca['valor']-$pagamentosaluguel-$somaparciais<0) {
						$devolverlocatario = $cobranca['valor']-$pagamentosaluguel-$somaparciais;
						$residual -= $pagamentosaluguel;
					} else if ($cobranca['valor']-$pagamentosaluguel-$somaparciais>0) {
						$residual = $cobranca['valor']-$pagamentosaluguel-$somaparciais;
					} if ($cobranca['valor']-$pagamentosaluguel-$somaparciais==0) {
						$status = 'Pago dia '.$data_pagamento->format('d/m/Y');
						$residual = $cobranca['valor']-$pagamentosaluguel-$somaparciais;
					} // valor na devolucao < valor pago inicialmente
				} // tid > 0

				$residualfloat = $residual;

				if ($residual<0) {
					$status = 'Devolvido ao locatário';
				} // residual negativo (devolver pro locatario)

				($residual==0) ? $residual = '' : $residual = ': '.Dinheiro(str_replace('-','',$residual));

				return $this->resultado = $resultado = array(
					'residual'=>$residualfloat,
					'valor'=>$residual,
					'status'=>$status,
					'data_pagamento'=>$data_pagamento
				);
			} // Residual

			public function CarimboPago($coid) {
				global $dominio;

				$cobranca = parent::Cobranca($coid);
				$carimbo = "<img style='position:absolute;top:18%;left:55%;width:100%;max-width:120px;height:auto;transform:rotate(18deg);opacity:0.18;' src='".$dominio."/img/pagoicon.png'></img>";

				$pago = 0;
				if ($cobranca['tid']!=0) {
					if ($cobranca['valor']!=0) {
						if ($cobranca['forma']!=0) {
							// pagou
							$pago = 1;
						} else if ($cobranca['forma']=='Cortesia') {
							// ganhou gratuidade
							$pago = 1;
						} else {
							// tem que pagar
							$pago = 0;
						} // se pagou
					} else {
						// ficou em 0 reais a conta do aluguel por conta das cortesias da placa
						$pago = 2;
					}// R$>0
				} else {
					// se não fez transação mas já pagou antes e ficou em 0 reais
					$somaparciais = $this->SomaParciais($coid);
					$pagamentosaluguel = $this->SomaPagamentosAluguel($cobranca['aid']);

					$pagoateomomento = $pagamentosaluguel+$somaparciais;
					if ($pagoateomomento>=$cobranca['valor']) {
						$pago = 1;
					} // se já pagou tudo
				} // se houve transacao

				if ($pago>0) {echo $carimbo;}
				return $this->pago = $pago;
			} // CarimboPago

			public function Permissao($permissao) {
				$retorno = false;
				switch ($permissao) {
					case 'leitura':
						$permissao = 1;
						break;
					case 'registro':
						$permissao = 2;
						break;
					case 'modificacao':
						$permissao = 3;
						break;
					default:
						$permissao = 0;
						break;
				}
				$admin = parent::AdminInfo($this->uid);
				if ($admin!=0) {
					$nivel = $admin['nivel'];
					if ($nivel>=$permissao) {
						$retorno = true;
					} // se nivel admin meets permissao
				} // admin != 0

				return $this->$retorno = $retorno;
			} // Permissao

			public function DataBrasil($data,$rumo) {
				if ($rumo=='exibicao') {
					 echo $data->format('d/m/Y');
				} else if ($rumo=='insercao') {
					 $data = explode('/',$data);
					 $data = $data[2].'-'.$data[1].'-'.$data[0];
					 return $data;
				} // rumo
			} // DataBrasil

			public function SwitchForma($forma) {
				switch ($forma) {
					case 1:
						$forma = 'Dinheiro';
						break;
					case 2:
						$forma = 'Débito';
						break;
					case 3:
						$forma = 'Cartão de crédito';
						break;
					case 4:
						$forma = 'Pix';
						break;
					case 5:
						$forma = 'Cortesia';
						break;
					case 6:
						$forma = 'Cheque';
						break;
					case 7:
						$forma = 'Promissória';
						break;
					case 8:
						$forma = 'Outro';
						break;
					default:
						$forma = 'Outro';
						break;
				} // switch
				return $this->forma = $forma;
			} // SwitchForma

			public function Potencia($vid) {
				$veiculo = parent::Veiculo($vid);

				($veiculo['potencia']==0) ? $potencia = 0 : $potencia = $veiculo['potencia'];
				($veiculo['potencia']==1) ? $potencia = 0 : $potencia = $veiculo['potencia'];
				if ($potencia!=0) {
					$potencia = str_split($veiculo['potencia']);
					$potencia = $potencia[0].'.'.$potencia[1];
				} else {
					$potencia = '';
				}

				return $potencia;
			} // Potencia

			public function ExcedenteData($aid) {
				$aluguel = parent::AluguelInfo($aid);
				$veiculo = parent::Veiculo($aluguel['vid']);
				$categoria = parent::VeiculoCategoria($veiculo['categoria']);

				$diaria_excedente_data = parent::DiariaExcedenteData($aluguel['data']);
				switch ($categoria) {
					case 'Carro':
						$preco_diaria_excedente = $diaria_excedente_data['excedente_carro'];
						break;
					case 'Moto':
						$preco_diaria_excedente = $diaria_excedente_data['excedente_moto'];
						break;
					case 'Utilitário':
						$preco_diaria_excedente = $diaria_excedente_data['excedente_utilitario'];
						break;
					default:
						$preco_diaria_excedente = $diaria_excedente_data['excedente_carro'];
						break;
				}// switch excedente/categoria

				return $this->preco_diaria_excedente = $preco_diaria_excedente;
			} // ExcedenteData

			public function is_booked_date($inicio,$devolucao) {
			        $sql = "
					SELECT *
					FROM aluguel
					LEFT JOIN reserva ON aluguel.aid=reserva.aid
					WHERE COALESCE(? NOT BETWEEN aluguel.inicio AND aluguel.devolucao, TRUE)
					AND COALESCE(? NOT BETWEEN aluguel.inicio AND aluguel.devolucao, TRUE)
					AND COALESCE(? NOT BETWEEN reserva.inicio AND reserva.devolucao, TRUE)
					AND COALESCE(? NOT BETWEEN reserva.inicio AND reserva.devolucao, TRUE)
				";

				$stmt = $this->conectar()->prepare($sql);
				$stmt->execute([$inicio,$devolucao,$inicio,$devolucao]);
				$disponivel = $stmt->fetchAll();
				if (count($disponivel)>=1) {
					foreach ($disponivel as $veiculo) {
						$resultado[] = array(
							'vid'=>$this->vid = $veiculo['vid']??0
						);
					} // foreach
				} else {
					$resultado[] = array(
						'vid'=>$this->vid = 0
					);
				}
				return $this->resultado = $resultado;
			} // is_booked_date

			public function BoletoHabil($uid) {
				global $dominio;

				$this->data = date_create()->format('Y-m-d H:i:s.u');
				$this->data_de_hoje = new DateTime($this->data);

				$boletousuario = new ConsultaDatabase($uid);
				$boletousuario = $boletousuario->BoletoUsuario($uid);

				$boleto = new ConsultaDatabase($uid);
				$boleto = $boleto->PagamentoBoletoPagSeguro($boletousuario['data']);
				if ($boleto['data']!=0) {
					$vencimentoBoleto = new DateTime($boleto['paymentmethodtypeboletoduedate']);
					$licencaBoleto = explode('_',$boleto['licencaTipo']);
					($licencaBoleto[2]=='Anual') ? $licencaBoleto = 'anual' : $licencaBoleto = 'vitalícia';
					if ( ($vencimentoBoleto>$this->data_de_hoje) && ($boleto['status']!='CANCELADO') ) {
						// boleto hábil
						echo "<div>";
							MontaBotao('ver boleto','verboletohabil');
							echo '
								<p style="font-size:12px;">
									Vencimento em <b>'.$vencimentoBoleto->format('d/m/y').'</b> para aquisição da <b>licença '.$licencaBoleto.'.</b>
								</p>
								<p class="cancelarboletobutton">cancelar boleto</p>
								<script>
									$.ajax({
								                type: "POST",
								                url: "'.$dominio.'/minhaconta/plano/consulta-boleto.inc.php",
								                data: {
											//
								                },
								                success: function(consultaboleto) {
											console.log(consultaboleto["resposta"]);
										}
									});
									$("#verboletohabil").on("click",function() {
										window.open(
											"'.$boleto['linkshref'].'",
											"_blank"
										);
									});
									$(".cancelarboletobutton").on("click",function() {
										loadFundamental("'.$dominio.'/minhaconta/plano/cancelarboletopopup.inc.php");
									});
								</script>
							';

							echo "<div>";MontaBotaoSecundario('mudar pagamento','comprarplano');echo"</div>";
						echo"</div>";
					} else {
						// boleto cancelado ou vencido
						echo "<div>";MontaBotao('comprar','comprarplano');echo"</div>";
					} // boleto ainda não vencido
				} else {
					// não criou boleto
					echo "<div>";MontaBotao('comprar','comprarplano');echo"</div>";
				} // existe boleto criado
			} // BoletoHabil

			public function RenovacaoLicenca($uid) {
				$licenca = parent::LicencaUsuario($uid);

				if ($licenca['data']!=0) {
					$datalicenca = new DateTime($licenca['data']);
					$validadelicenca = new Datetime($licenca['data']);
					$validadelicenca->modify('+1 year');
					$validadelicenca = $validadelicenca->format('d/m/y');
				} else {
					$validadelicenca = 0;
				}// datalicenca

				return $this->validadelicenca = $validadelicenca;
			} // RenovacaoLicenca

	} // conforto

?>
