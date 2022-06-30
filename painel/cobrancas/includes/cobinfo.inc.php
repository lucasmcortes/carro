<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['cobranca'])) {
	$coid = $_POST['cobranca'];

	$cobranca = new ConsultaDatabase($uid);
	$cobranca = $cobranca->Cobranca($coid);
	($cobranca['data_pagamento']==0) ?  : $data_pagamento = new DateTime($cobranca['data_pagamento']);

	$parciais = new ConsultaDatabase($uid);
	$parciais = $parciais->CobrancaParcial($coid);

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($cobranca['lid']);

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($cobranca['aid']);

	$somaparciais = new Conforto($uid);
	$somaparciais = $somaparciais->SomaParciais($coid);

	$pagamentosaluguel = new Conforto($uid);
	$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aluguel['aid']);

	$pagoateomomento = $pagamentosaluguel+$somaparciais;

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($aluguel['vid']);

	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->Reserva($aluguel['aid']);
	if ($reserva['aid']!=0) {
		$ativa = new ConsultaDatabase($uid);
		$ativa = $ativa->Ativacao($reserva['reid']);
		if ($ativa['ativa']=='S') {
			$inicio_string = $reserva['inicio'];
			$devolucao_string = $reserva['devolucao'];
			$data_inicio = new DateTime($reserva['inicio']);
			$data_devolucao = new DateTime($reserva['devolucao']);
		} // ativa
	} else {
	       $reserva = new ConsultaDatabase($uid);
	       $reserva = $reserva->ReservaDevolvida($aluguel['aid']);
	       if ($reserva['reid']!=0) {
		       $ativacao = new ConsultaDatabase($uid);
		       $ativacao = $ativacao->Ativacao($reserva['reid']);
		       if ($ativacao['ativa']=='S') {
			       $devolucao_aluguel = new ConsultaDatabase($uid);
			       $devolucao_aluguel = $devolucao_aluguel->Devolucao($reserva['aid']);
			       if ($devolucao_aluguel['deid']==0) {
				       $inicio_string = $reserva['inicio'];
				       $devolucao_string = $reserva['devolucao'];
				       $data_inicio = new DateTime($reserva['inicio']);
				       $data_devolucao = new DateTime($reserva['devolucao']);
			       } else {
					$devolucao = new ConsultaDatabase($uid);
					$devolucao = $devolucao->Devolucao($reserva['aid']);
					$inicio_string = $reserva['inicio'];
					$devolucao_string = $devolucao['data'];
					$data_inicio = new DateTime($reserva['inicio']);
					$data_devolucao = new DateTime($devolucao['data']);
			       } // sem devolver ainda
		       } // reserva ativa
	       } else {
			$inicio_string = $aluguel['inicio'];
			$devolucao_string = $aluguel['devolucao'];
			$data_inicio = new DateTime($aluguel['inicio']);
			$data_devolucao = new DateTime($aluguel['devolucao']);
	       } // reservadevolvida
	} // reserva

	// DATA PREVISTA
	$data_inicio_aluguel_string = $inicio_string;
	$data_inicio_aluguel = new DateTime($data_inicio_aluguel_string);
	$data_devolucao_prevista_string = $devolucao_string;
	$data_devolucao_prevista = new DateTime($data_devolucao_prevista_string);

	$devolucao = new ConsultaDatabase($uid);
	$devolucao = $devolucao->Devolucao($aluguel['aid']);
	$devolucao_string = $devolucao['data'];
	$data_devolucao = new DateTime($devolucao['data']);
	if ($devolucao['deid']!=0) {
		$inicio = new DateTime($aluguel['inicio']);
		$data_devolucao = new DateTime($devolucao['data']);
		$diarias = $inicio->diff($data_devolucao);

		$devolucao_string = $devolucao['data'];

		$cobranca = new ConsultaDatabase($uid);
		$cobranca = $cobranca->CobrancaAluguel($aluguel['aid']);
		$coid = $cobranca['coid'];

		$somaparciais = new Conforto($uid);
		$somaparciais = $somaparciais->SomaParciais($cobranca['coid']);

		$valor_mostrado = $cobranca['valor']-$pagamentosaluguel;

		($cobranca['data_pagamento']==0) ?  : $data_pagamento = new DateTime($cobranca['data_pagamento']);

		$pago = new Conforto($uid);
		$pago = $pago->CarimboPago($coid);

	} // se devolveu, mostra a data de devolução como devolução na tabela

	 $valoradicional = new ConsultaDatabase($uid);
	 $valoradicional = $valoradicional->ValorAdicional($devolucao['deid']);

 	$limpeza = new ConsultaDatabase($uid);
 	$limpeza = $limpeza->LimpezaTipo($devolucao['data'],$devolucao['limpeza']);

	 $precokm = new ConsultaDatabase($uid);
	 $precokm = $precokm->PrecoKMData($devolucao['data']);
	$km_anterior = new ConsultaDatabase($uid);
	$km_anterior = $km_anterior->KilometragemAnterior($aluguel['vid'],$inicio_string,$devolucao_string);
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

	$totalHoras = round((strtotime($devolucao_string) - strtotime($inicio_string))/3600, 0);
	$totalDiarias = ceil($totalHoras/24);
	($totalDiarias==0) ? $totalDiarias = 1 : $totalDiarias = $totalDiarias;

	$previsao_diarias = $data_inicio_aluguel->diff($data_devolucao_prevista);
	$total_de_dias_previsao = $previsao_diarias->format('%a');

	$preco_diaria_excedente = new Conforto($uid);
	$preco_diaria_excedente = $preco_diaria_excedente->ExcedenteData($aluguel['aid']);

	$diferenca_adicionais = $totalDiarias - $total_de_dias_previsao;

	if ($totalDiarias>$total_de_dias_previsao) {
		$data_prevista = $total_de_dias_previsao." x ".Dinheiro($aluguel['diaria'])." + ".$diferenca_adicionais." x ".Dinheiro($preco_diaria_excedente);
	} else {
		$data_prevista = $totalDiarias." x ".Dinheiro($aluguel['diaria']);
	} // se tem diárias excedentes

	if ($cobranca['cortesias']>0) {
		if ($cobranca['cortesias']>$total_de_dias_previsao) {
			if ($cobranca['cortesias']>=$totalDiarias) {
				$cortesias_exibidas = $totalDiarias;
				$exibe_preco_final = $data_prevista;
			} else {
				$cortesias_exibidas = $cobranca['cortesias'];
				$esclarecimento_diarias = $total_de_dias_previsao.' x '.Dinheiro($aluguel['diaria']). ' + '.$cobranca['cortesias']-$total_de_dias_previsao.' x '.Dinheiro($preco_diaria_excedente);
				$exibe_preco_final = $esclarecimento_diarias;
			}
		} else {
			if ($cobranca['cortesias']>=$totalDiarias) {
				$cortesias_exibidas = $totalDiarias;
				$exibe_preco_final = $data_prevista;
			} else {
				$cortesias_exibidas = $cobranca['cortesias'];
				$exibe_preco_final = $cobranca['cortesias'].' x '.Dinheiro($aluguel['diaria']);
			}
		}
	} else {
		$cortesias_exibidas = 0;
		$exibe_preco_final = Dinheiro($preco_final??0);
	} // esclarecimento das cortesias

} else {
	$coid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloPagina('detalhes'); ?>

	<div style='text-align:center;margin:0 auto;margin-top:5%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;'>Referente ao aluguel do veículo: <b><?php echo $veiculo['modelo'] ?></b></p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;'>Do dia: <b><?php echo $data_inicio->format('d/m/Y').' às '.$data_inicio->format('H').'h'; ?></b></p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;'>Até o dia: <b><?php echo $data_devolucao->format('d/m/Y').' às '.$data_devolucao->format('H').'h'; ?></b></p>
		</div>
	</div>

	<div style='text-align:center;margin:0 auto;margin-top:5%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;'><b><?php echo $totalDiarias; ?> diária(s) (<?php echo $data_prevista ?>)</b></p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;'><b>Com <?php echo $cortesias_exibidas; ?> diária(s) de cortesia (<?php echo $exibe_preco_final ?>)</b></p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;'><b>• <?php echo Dinheiro($kmExcedentes*$precokm['preco']); ?> pelo uso de <?php echo Kilometragem($kmExcedentes); ?> excedente(s)</b></p>
		</div>
		<?php
			if ($devolucao['limpeza']>0) {
				echo "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p style='display:inline-block;'>• <b>".Dinheiro($devolucao['limpeza'])." pela ".$limpeza['tipo']." </b></p>
					</div>
				";
			} // se pagou limpeza

			if ($valoradicional['valor']>0) {
				echo "
					<div style='min-width:100%;max-width:100%;display:inline-block;'>
						<p style='display:inline-block;'>• <b>".$valoradicional['descricao']."</b>: <b>".Dinheiro($valoradicional['valor'])."</b></p>
					</div>
				";
			} // adicional > 0

		?>
	</div>

	<div style='text-align:center;margin:0 auto;margin-top:3%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
			<p style='display:inline-block;' class='bloquinho'>Valor total da fatura: <b><?php echo Dinheiro($cobranca['valor']) ?></b></p>
		</div>


		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<?php
			if ($cobranca['tid']!=0) {
				$faturaok = 1;
				$data_pagamento = new DateTime($cobranca['data_pagamento']);

				$valor_mostrado = $cobranca['valor']-$pagamentosaluguel-$somaparciais;

				$recebedor = new ConsultaDatabase($uid);
				$recebedor = $recebedor->AdminInfo($cobranca['recebedor']);
				$transacao = new ConsultaDatabase($uid);
				$transacao = $transacao->Transacao($cobranca['tid']);

				if ($pagoateomomento>0) {
					($transacao['desconto']==0) ? $valorintegraldoresidual = $valor_mostrado - ($valor_mostrado * ($transacao['desconto']/100)) : $valorintegraldoresidual = $valor_mostrado;
					echo "
						<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
							<p style='display:inline-block;'>Pagamento integral do residual no valor de <b>".Dinheiro($valor_mostrado - ($valor_mostrado * ($transacao['desconto']/100)))."  em ".mb_strtolower($transacao['forma'])."</b>
					";
							if ($transacao['desconto']>0) {
								echo "<b>com ".$transacao['desconto']."% de desconto (sobre o valor ".Dinheiro($cobranca['valor']-$pagoateomomento).")</b>";
							} // com desconto
					echo " no dia <b>".$data_pagamento->format('d/m/Y')."</b> às <b>".$data_pagamento->format('H')."h".$data_pagamento->format('i')."</b> recebidos por ".$recebedor['nome'];
					echo "
							</p>
						</div>
					";
				} else {
					echo "
						<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
							<p style='display:inline-block;'>Pagamento integral da fatura por: <b>".Dinheiro(($valor_mostrado - ($valor_mostrado * ($transacao['desconto']/100))))." em ".mb_strtolower($transacao['forma'])."</b>
					";
							if ($transacao['desconto']>0) {
								echo "<b>com ".$transacao['desconto']."% de desconto</b>";
							} // com desconto
					echo " no dia <b>".$data_pagamento->format('d/m/Y')."</b> às <b>".$data_pagamento->format('H')."h".$data_pagamento->format('i')."</b> recebidos por ".$recebedor['nome'];
					echo "
							</p>
						</div>
					";
				} // se existiu pagamento parcial antes

			} else {
				$mostrariconefatura = 1;
				if ($pagoateomomento>0) {
					$valor_mostrado = $cobranca['valor']-$pagoateomomento;
					echo "
						<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
							<p style='display:inline-block;'>Descontando <b>".Dinheiro($pagoateomomento)."</b> pagos até o momento</p>
						</div>
					";
					if (($valor_mostrado)>0) {
						echo "
							<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
							<p style='display:inline-block;' class='bloquinho'>Totalizando <b>".Dinheiro(str_replace('-','',$valor_mostrado))."</b> a serem pagos pelo locatário</p>
							</div>
						";
					} else {
						$faturaok = 1;
						echo "
							<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
								<p style='display:inline-block;' class='bloquinho'>Totalizando <b>".Dinheiro(str_replace('-','',$valor_mostrado))."</b> devolvidos ao locatário</p>
							</div>
						";
					} // valor_mostrado > 0
				} // valorinicial > 0
			} // tid > 0
			?>
		</div>
	</div>

	<div id='pagarcobrancawrap' style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
		<?php
			if (isset($mostrariconefatura)) {
				if (!isset($faturaok)) {
					Icone('pagarcobranca','pagar','addpagamentoicon');
				}
			}

			if ( ($pagamentosaluguel>0) || ($somaparciais>0) ) {
				Icone('verpagamentos','pagamentos','verpagamentosicon');
			} // se existiu algum pagamento

			Icone('veralinfo','aluguel','infoicon');
			Icone('linfo','locatário','linfoicon');
			Icone('vinfo','veículo','vinfoicon');
		?>
	</div>

</div>
<!-- items -->

<script>
	abreFundamental();

	$('#veralinfo').on('click', function() {
		aluguelFundamental(<?php echo $aluguel['aid'] ?>,0);
	});

	$('#vinfo').on('click', function() {
		veiculoFundamental(<?php echo $aluguel['vid'] ?>);
	});

	$('#linfo').on('click',function () {
		locatarioFundamental(<?php echo $aluguel['lid'] ?>);
	});

	$('#pagarcobranca').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/cobrancas/novo/?c=<?php echo $coid ?>';
	});

	$('#verpagamentos').on('click',function () {
		verPagamentoAluguel(<?php echo $aluguel['aid'] ?>,0);
	});

</script>
