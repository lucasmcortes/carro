<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];
	if ($aid==0) {
		echo 'Aluguel não encontrado';
		return;
	} //

	$coid = 0;
	$pago = 0;
	$resativa = 0;
	$foireserva = 0;
	$somaparciais = 0;
	$pagamentosaluguel = 0;

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
	$inicio = new DateTime($aluguel['inicio']);
	$data_devolucao = new DateTime($aluguel['devolucao']);

	$valorinicial = $aluguel['valor'];
	$forma = $aluguel['forma'];
	$caucao = $aluguel['valor_caucao'];
	$formacaucao = $aluguel['forma_caucao'];

	$pagamentosaluguel = new Conforto($uid);
	$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aid);

	$devolucao = new ConsultaDatabase($uid);
	$devolucao = $devolucao->Devolucao($aluguel['aid']);
	if ($devolucao['deid']!=0) {
		$inicio = new DateTime($aluguel['inicio']);
		$data_devolucao = new DateTime($devolucao['data']);
		$diarias = $inicio->diff($data_devolucao);

		$cobranca = new ConsultaDatabase($uid);
		$cobranca = $cobranca->CobrancaAluguel($aid);
		$coid = $cobranca['coid'];

		$somaparciais = new Conforto($uid);
		$somaparciais = $somaparciais->SomaParciais($cobranca['coid']);

		$valor_mostrado = $cobranca['valor']-$pagamentosaluguel;

		($cobranca['data_pagamento']==0) ?  : $data_pagamento = new DateTime($cobranca['data_pagamento']);

		$pago = new Conforto($uid);
		$pago = $pago->CarimboPago($coid);

	} // se devolveu, mostra a data de devolução como devolução na tabela

	$pagoateomomento = $pagamentosaluguel+$somaparciais;

	$locador = new ConsultaDatabase($uid);
	$locador = $locador->AdminInfo($aluguel['uid']);

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($aluguel['lid']);

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($aluguel['vid']);

	$limpeza_momento_aluguel = new ConsultaDatabase($uid);
	$limpeza_momento_aluguel = $limpeza_momento_aluguel->MomentoLimpeza($aluguel['vid'],$aluguel['inicio']);
	($limpeza_momento_aluguel['status']=='S') ? $limpeza = 'Limpo' : $limpeza = 'À lavar';

	$categoria = new ConsultaDatabase($uid);
	$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

	$disponibilidade_veiculo = new Conforto($uid);
	$disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($veiculo['vid']);
	$disponibilidade = $disponibilidade_veiculo['status'];

	if ($disponibilidade=='Alugado') {
		$inicio = new DateTime($aluguel['inicio']);
		$data_devolucao = new DateTime($aluguel['devolucao']);

		$reserva = new ConsultaDatabase($uid);
		$reserva = $reserva->Reserva($aluguel['aid']);
		if ($reserva['reid']!=0) {
			$ativacao = new ConsultaDatabase($uid);
			$ativacao = $ativacao->Ativacao($reserva['reid']);
			if ($ativacao['ativa']=='S') {
				$devolucao_aluguel = new ConsultaDatabase($uid);
				$devolucao_aluguel = $devolucao_aluguel->Devolucao($reserva['aid']);
				if ($devolucao_aluguel['deid']==0) {
					$resativa = 1;
					$inicio = new DateTime($reserva['inicio']);
					$data_devolucao = new DateTime($reserva['devolucao']);
				} else {
					$inicio = new DateTime($reserva['inicio']);
					$data_devolucao = new DateTime($devolucao['data']);
				} // sem devolver ainda
			} // reserva ativa
		}

		if ($inicio->format('Y-m-d')>$agora->format('Y-m-d')) {
			// reserva
		} else if ($inicio->format('Y-m-d')<$agora->format('Y-m-d')) {
			$disponibilidade = 'Alugado';
		} else if ($inicio->format('Y-m-d')==$agora->format('Y-m-d')) {
			// reservado pra hoje
			if ($inicio->format('H')>$agora->format('H')) {
				// reservado pra hoje mais tarde
			} // pra hoje
		} // datas
	} else {
		$reserva = new ConsultaDatabase($uid);
		$reserva = $reserva->Reserva($aluguel['aid']);
		if ($reserva['reid']!=0) {
			$foireserva = 1;
			$ativacao = new ConsultaDatabase($uid);
			$ativacao = $ativacao->Ativacao($reserva['reid']);
			if ($ativacao['ativa']=='S') {
				$devolucao_aluguel = new ConsultaDatabase($uid);
				$devolucao_aluguel = $devolucao_aluguel->Devolucao($reserva['aid']);
				if ($devolucao_aluguel['deid']==0) {
					$resativa = 1;
					$inicio = new DateTime($reserva['inicio']);
					$data_devolucao = new DateTime($reserva['devolucao']);
				} else {
					$inicio = new DateTime($reserva['inicio']);
					$data_devolucao = new DateTime($devolucao['data']);
				} // sem devolver ainda
			} // reserva ativa
		} else {
			$reserva = new ConsultaDatabase($uid);
			$reserva = $reserva->ReservaDevolvida($aluguel['aid']);
			if ($reserva['reid']!=0) {
				$foireserva = 1;
				$ativacao = new ConsultaDatabase($uid);
				$ativacao = $ativacao->Ativacao($reserva['reid']);
				if ($ativacao['ativa']=='S') {
					$devolucao_aluguel = new ConsultaDatabase($uid);
					$devolucao_aluguel = $devolucao_aluguel->Devolucao($reserva['aid']);
					if ($devolucao_aluguel['deid']==0) {
						$resativa = 1;
						$inicio = new DateTime($reserva['inicio']);
						$data_devolucao = new DateTime($reserva['devolucao']);
					} else {
						$inicio = new DateTime($reserva['inicio']);
						$data_devolucao = new DateTime($devolucao['data']);
					} // sem devolver ainda
				} // reserva ativa
			} // reservadevolvida
		} // reserva
	} // alugado

	if ( ($_POST['ativo']==0) || ($_POST['ativo']==1) ) {
		echo "
			<script>
				$('.alinfo').prop('disabled','disabled');
				$('.alinfo').css('cursor','not-allowed');
			</script>
		";
	} // ativa

	$contrato_numero = new Conforto($uid);
	$contrato_numero = $contrato_numero->NumeroContrato($aluguel['aid']);
} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloCarro($veiculo['modelo']); ?>
	<div style='display:flex;flex-direction: column;'>
		<p><?php echo $veiculo['placa'].' - '.$limpeza ?></p>
		<p class='numregistro'>
			<?php echo $contrato_numero ?>
		</p>
		<p style='font-size:12px;'>Criado por <b><?php echo $locador['nome'].'</b>, em '.strftime('%d/%m/%Y às %Hh', strtotime($aluguel['data'])) ?></p>
		<?php
			if ($caucao>0) {
				echo "<p style='font-size:12px;'>Caução de <b>".Dinheiro($caucao)."</b>, pago com ".mb_strtolower($formacaucao)."</p>";
			} // caucao > 0
		?>
	</div>

	<div class='listawrap' style='margin-top:3%;'>

		<?php
			//Icone('vercontrato','contrato','contratoicon');
			echo "
				<script>
					$('#vercontrato').on('click', function() {
						window.open(
							'".$dominio."/painel/alugueis/pdf/?aid=".$aid."&tipo=contrato',
							'_blank'
						);
					});
				</script>
			";
			Icone('verchecklist','checklist','checklisticon');
			echo "
				<script>
					$('#verchecklist').on('click', function() {
						window.open(
							'".$dominio."/painel/alugueis/pdf/?aid=".$aid."&tipo=checklist',
							'_blank'
						);
					});
				</script>
			";
			Icone('verpromissoria','promissória','promissoriaicon');
			echo "
				<script>
					$('#verpromissoria').on('click', function() {
						window.open(
							'".$dominio."/painel/alugueis/pdf/?aid=".$aid."&tipo=promissoria',
							'_blank'
						);
					});
				</script>
			";
		?>


	</div>
	<div class='listawrap' style='margin-top:3%;'>
		<div id='iniciowrap' style='min-width:49%;max-width:49%;display:inline-block;margin:3px auto;'>
			<label>Início</label>
			<div id='inicioinner' style='min-width:85%;max-width:85%;display:inline-block;'>
				<input class='alinfo' type='text' id='inicio' placeholder='Início' value='<?php echo $inicio->format('d/m/Y'); ?>'></input>
			</div>
			<div class='abrecalendario'>
				<span class="info" aria-label="calendário">
					<img style='width:100%;max-width:26px;height:auto;' src='<?php echo $dominio ?>/img/calendarioformicon.png'></img>
				</span>
			</div>
		</div> <!-- iniciowrap -->

		<div id='devolucaowrap' style='min-width:49%;max-width:49%;display:inline-block;margin:3px auto;'>
			<label>Devolução</label>
			<div id='devolucaoinner' style='min-width:85%;max-width:85%;display:inline-block;'>
				<input class='alinfo' type='text' id='devolucao' placeholder='Devolução' value='<?php echo $data_devolucao->format('d/m/Y'); ?>'></input>
			</div>
			<div class='abrecalendario'>
				<span class="info" aria-label="calendário">
					<img style='width:100%;max-width:26px;height:auto;' src='<?php echo $dominio ?>/img/calendarioformicon.png'></img>
				</span>
			</div>
		</div> <!-- devolucaowrap -->

		<div id='horawrap' style='min-width:49%;max-width:49%;display:inline-block;margin:3px auto;'>
			<label>Horário</label>
			<div id='horainner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<select class='alinfo' id='hora' placeholder='Hora'>
					<option value=''>--ESCOLHA--</option>
					<option value='06'>06h</option>
					<option value='07'>07h</option>
					<option value='08'>08h</option>
					<option value='09'>09h</option>
					<option value='10'>10h</option>
					<option value='11'>11h</option>
					<option value='12'>12h</option>
					<option value='13'>13h</option>
					<option value='14'>14h</option>
					<option value='15'>15h</option>
					<option value='16'>16h</option>
					<option value='17'>17h</option>
					<option value='18'>18h</option>
					<option value='19'>19h</option>
					<option value='20'>20h</option>
					<option value='21'>21h</option>
					<option value='22'>22h</option>
					<option value='23'>23h</option>
					<option value='00'>00h</option>
					<option value='01'>01h</option>
					<option value='02'>02h</option>
					<option value='03'>03h</option>
					<option value='04'>04h</option>
					<option value='05'>05h</option>
				</select>
			</div>
		</div> <!-- horawrap -->

		<div id='diariawrap' style='min-width:49%;max-width:49%;display:inline-block;margin:3px auto;'>
			<label>Preço por diária</label>
			<div id='diariainner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<p style='text-align:right;font-weight:900;'><?php echo Dinheiro($aluguel['diaria']) ?></p>
				<!--<input class='alinfo' type='text' id='diaria' placeholder='Diária' value='<?php echo $aluguel['diaria'] ?>'></input>-->
			</div>
		</div> <!-- diariawrap -->

		<?php

			if (isset($cobranca)) {
				if ($cobranca['tid']!=0) {
					$faturaok = 1;
					$data_pagamento = new DateTime($cobranca['data_pagamento']);

					$valor_mostrado = $cobranca['valor']-$pagamentosaluguel-$somaparciais;

					$recebedor = new ConsultaDatabase($uid);
					$recebedor = $recebedor->AdminInfo($cobranca['recebedor']);
					$transacao = new ConsultaDatabase($uid);
					$transacao = $transacao->Transacao($cobranca['tid']);

					echo "
						<div style='min-width:100%;max-width:100%;display:inline-block;'>
							<p style='display:inline-block;font-size:12px;'>
								Pagamento da fatura quitado dia ".$data_pagamento->format('d/m/Y')." às ".$data_pagamento->format('H')."h".$data_pagamento->format('i')."
							</p>
						</div>
					";

				} else {
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
			} else {
				if ( ($pagamentosaluguel>0) || ($somaparciais>0) ) {
					if ( (isset($pago)) && ($pago!=1) ) {
						echo "
							<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
								<p style='display:inline-block;'><b>Valor total pago até o momento: ".Dinheiro($pagamentosaluguel+$somaparciais)."</b></p>
							</div>
						";
					} // pago != 1
				} // pagamentosaluguel > 0

				if ($valorinicial>0) {
					if (isset($cobranca)) {
						if (($cobranca['valor']-$valorinicial)<0) {
							$devolvidolocatario = 1;
							echo "
								<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
									<p style='display:inline-block;'><b>".Dinheiro(str_replace('-','',$cobranca['valor']-$pagamentosaluguel+$somaparciais))." devolvidos ao locatário</b></p>
								</div>
							";
						} // valor_mostrado > 0
					} // se existe cobrança
				} // valorinicial > 0
			} // isset cobranca
		?>
	<?php

		if ( (isset($pago)) && ($pago==0) ) {
			if (!isset($devolvidolocatario)) {
				if (isset($cobranca)) {
					//Icone('pagarcobranca','pagamento','addpagamentoicon');
				} // existe cobrança criada
			} // ainda existe valor a ser pago após o pagamento inicial
		} else if ( (isset($pago)) && ($pago==1) ) {
			// echo "
			// 	<div style='min-width:100%;max-width:100%;display:inline-block;'>
			// 		<p style='display:inline-block;font-size:12px;'>
			// 			Pagamento da fatura quitado dia ".$data_pagamento->format('d/m/Y')." às ".$data_pagamento->format('H')."h".$data_pagamento->format('i')."
			// 		</p>
			// 	</div>
			// ";
		} else if ( (isset($pago)) && ($pago==2) ) {
			echo "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p style='display:inline-block;font-size:12px;'>
						Aluguel totalizou no valor de ".Dinheiro($cobranca['valor'])."
					</p>
				</div>
			";
		} // pagou

		echo "<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>";
		if ( ($pagamentosaluguel>0) || ($somaparciais>0) ) {
			Icone('verpagamentos','pagamentos','verpagamentosicon');
		} // se existiu algum pagamento

		if ($_POST['ativo']!=0) {
			Icone('addpagamento','pagamento','addpagamentoicon');
			if ($inicio<$agora) {
				Icone('devolverveiculo','devolver','devolvericon');
			} // se esse é o aluguel corrente
		} else {
			Icone('verfatura','fatura','verfaturaicon');
		} // ativa

		if ($foireserva==1) {
			//Icone('verreserva','reserva','reservainfoicon');
		} else {
			//Icone('linfo','locatário','linfoicon');
		} // se foi reserva
		Icone('linfo','locatário','linfoicon');

		Icone('vinfo','veículo','vinfoicon');
		echo "</div>";
	?>

</div> <!-- listawrap -->
</div>
<!-- items -->

<script>
	abreFundamental();
	$('#hora').val('<?php echo $inicio->format('H') ?>');

	$('.abrecalendario').on('click',function () {
		calendarioPop(0,'vestimenta',<?php echo $aluguel['vid'] ?>);
	});

	$('#inicio').on('click',function () {
		calendarioPop(1,'vestimenta',<?php echo $aluguel['vid'] ?>);
	});

	$('#devolucao').on('click',function () {
		calendarioPop(2,'vestimenta',<?php echo $aluguel['vid'] ?>);
	});

	$('#devolverveiculo').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/devolucoes/novo?v=<?php echo $aluguel['vid'] ?>&a=<?php echo $aluguel['aid'] ?>';
	});

	$('#pagarcobranca').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/cobrancas/novo/?c=<?php echo $coid ?>';
	});

	$('#addpagamento').on('click',function () {
		addPagamentoAluguel(<?php echo $aluguel['aid'] ?>,<?php echo $_POST['ativo'] ?>);
	});

	$('#verpagamentos').on('click',function () {
		verPagamentoAluguel(<?php echo $aluguel['aid'] ?>,<?php echo $_POST['ativo'] ?>);
	});

	$('#verreserva').on('click', function() {
		reservaFundamental(<?php echo $aluguel['aid'] ?>, <?php echo $resativa ?>);
	});

	$('#verfatura').on('click',function () {
		cobrancaFundamental(<?php echo $coid ?>);
	});

	$('#vinfo').on('click', function() {
		veiculoFundamental(<?php echo $aluguel['vid'] ?>);
	});

	$('#linfo').on('click',function () {
		locatarioFundamental(<?php echo $aluguel['lid'] ?>);
	});

</script>
