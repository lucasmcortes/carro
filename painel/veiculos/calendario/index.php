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
					tituloPagina('veículos');
				?>
				<div id='novoveiculowrap' style='min-width:48%;max-width:48%;display:inline-block;'>
					<?php BotaoPainel('adicionar veículo','adicionarveiculo','veiculos/novo'); ?>
				</div>
				<div id='novoaluguelwrap' style='min-width:48%;max-width:48%;display:inline-block;'>
					<?php BotaoPainel('criar novo aluguel','alugarnovoaluguel','alugueis/novo'); ?>
				</div>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
						<!-- container -->
				                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							<?php
								$inicio_data = '2021-09-13 08:00:00.000000';
								$devolucao_data = '2021-09-28 08:00:00.000000';
								$veiculodisponivel = new Conforto($uid);
								print_r($veiculodisponivel = $veiculodisponivel->is_booked_date($inicio_data,$devolucao_data));

								$date = $agora->format('Y').'-'.$agora->format('m').'-01';
								$end = $agora->format('Y').'-'.$agora->format('m').'-' . date('t', strtotime($date)); //get end date of month
								while(strtotime($date) <= strtotime($end)) {
								        $day_num = date('d', strtotime($date));
								        $day_name = date('l', strtotime($date));
									$calendario[$date]['dia'] = $day_num;
									$calendario[$date]['mes'] = $agora->format('m');
									$calendario[$date]['ano'] = $agora->format('Y');

									$status = '';
									$vid = '';
									$veiculos = new ConsultaDatabase($uid);
									$veiculos = $veiculos->ListaVeiculos();
									if ($veiculos[0]['vid']!=0) {
										foreach ($veiculos as $veiculo) {
											$possibilidade = new Conforto($uid);
											$possibilidade = $possibilidade->Possibilidade($veiculo['vid']);
											if (isset($possibilidade['disponibilidade'][$date])) {
												$vid .= $veiculo['vid'].',';
												$status .= $possibilidade['disponibilidade'][$date]['status'].',';
											} else {
												$vid .= $veiculo['vid'].',';
												$status .= 'Disponível,';
											}
										} // foreach
									} // vid > 0
									$calendario[$date]['vid'] = rtrim($vid,',');
									$calendario[$date]['status'] = rtrim($status,',');
								        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
								} // while

								echo CalendarioDisponibilidade($agora->format('m'),$agora->format('Y'),$calendario);
							?>
						</div>
						<!-- container -->

						<div id='verveiculoswrap' style='min-width:100%;max-width:100%;display:inline-block;'>
							<?php BotaoPainel('ver veículos','verveiculos','veiculos'); ?>
						</div>
					</div>
				</div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
