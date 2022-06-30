<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
	$caucao = $aluguel['valor_caucao'];
	$formacaucao = $aluguel['forma_caucao'];

	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->Reserva($aid);

	$locador = new ConsultaDatabase($uid);
	$locador = $locador->AdminInfo($aluguel['uid']);

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($aluguel['lid']);

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($aluguel['vid']);

	$inicio = new DateTime($reserva['inicio']);
	if ( ($inicio->format('Y-m-d')==$agora->format('Y-m-d')) ) {
		$lembrete = 1;
	} //

	$limpeza_momento_aluguel = new ConsultaDatabase($uid);
	$limpeza_momento_aluguel = $limpeza_momento_aluguel->MomentoLimpeza($aluguel['vid'],$aluguel['data']);
	($limpeza_momento_aluguel['status']=='S') ? $limpeza = 'Limpo' : $limpeza = 'Lavar';

	$categoria = new ConsultaDatabase($uid);
	$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

	$disponibilidade_veiculo = new Conforto($uid);
	$disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($veiculo['vid']);
	$disponibilidade = $disponibilidade_veiculo['status'];

	if ($disponibilidade=='Alugado') {
		$devolucao = new DateTime($reserva['devolucao']);
	} else {
		$devolvido = new ConsultaDatabase($uid);
		$devolvido = $devolvido->Devolucao($aid);
		if ($devolvido['deid']!=0) {
			$devolucao = new DateTime($devolvido['data']);
		} else {
			$devolucao = new DateTime($reserva['devolucao']);
		}// devolvido
	} // alugado

	$ativa = new ConsultaDatabase($uid);
	$ativa = $ativa->Ativacao($reserva['reid']);

	$devolvido = new ConsultaDatabase($uid);
	$devolvido = $devolvido->Devolucao($aid);
	if ($devolvido['deid']==0) {
		if ($inicio->format('Y-m-d')>$agora->format('Y-m-d')) {
			// reserva
			if ($ativa['atid']!=0) {
				if ($ativa['ativa']=='S') {
					$disponibilidade = 'Reservado para o dia<br><b>'.strftime('%d/%m/%Y às %Hh', strtotime($reserva['inicio'])).'</b>';
				} else {
					$disponibilidade = 'Reserva cancelada';
				} // ativa
			} // atid
		} else if ($inicio->format('Y-m-d')<$agora->format('Y-m-d')) {
			$disponibilidade = 'Alugado';
		} else if ($inicio->format('Y-m-d')==$agora->format('Y-m-d')) {
			if ($inicio->format('H')>$agora->format('H')) {
				if ($ativa['atid']!=0) {
					if ($ativa['ativa']=='S') {
						$disponibilidade = 'Reservado à partir de hoje às '.$inicio->format('H').'h até '.$devolucao->format('d/m/Y');
					} else {
						$disponibilidade = 'Reserva cancelada';
					} // ativa
				} // atid
			} // pra hoje
		} // datas
	} else {
		// devolvido
		if ($inicio->format('Y-m-d')>$agora->format('Y-m-d')) {
			// reserva
			if ($ativa['atid']!=0) {
				if ($ativa['ativa']=='S') {
					$disponibilidade = 'Reservado para o dia<br><b>'.strftime('%d/%m/%Y às %Hh', strtotime($reserva['inicio'])).'</b>';
				} else {
					$disponibilidade = 'Reserva cancelada';
				} // ativa
			} // atid
		} else if ($inicio->format('Y-m-d')<$agora->format('Y-m-d')) {
			$disponibilidade = 'Alugado';
		} else if ($inicio->format('Y-m-d')==$agora->format('Y-m-d')) {
			if ($inicio->format('H')>$agora->format('H')) {
				if ($ativa['atid']!=0) {
					if ($ativa['ativa']=='S') {
						$disponibilidade = 'Reservado à partir de hoje às '.$inicio->format('H').'h até '.$devolucao->format('d/m/Y');
					} else {
						$disponibilidade = 'Reserva cancelada';
					} // ativa
				} // atid
			} // pra hoje
		} // datas
	} // devolucao = 0

	if ($_POST['ativa']==0) {
		echo "
			<script>
				$(':input').prop('disabled','disabled');
				$(':input').css('cursor','not-allowed');
			</script>
		";
		if ($ativa['ativa']=='N') {
			$data_cancelamento = new DateTime($ativa['data']);
			echo "
				<script>
					$(':input').prop('disabled','disabled');
					$(':input').css('cursor','not-allowed');
					$('#datacancelamentowrap').css('display','inline-block');
					$('#datacancelamento').val('".$data_cancelamento->format('d/m/Y')."');
				</script>
			";
		} else {
			echo "
				<script>
					$('#datacancelamentowrap').css('display','none');
				</script>
			";
		}// se ativa
	} else {
		echo "
			<script>
				$('#datacancelamentowrap').css('display','none');
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
	<?php
		tituloCarro($veiculo['modelo']);
		Icone('verhistorico','histórico de modificações','verhistoricoreservaicon');
		if ( (isset($lembrete)) && ($_POST['ativa']==1) ) {
			$enviado = new ConsultaDatabase($uid);
			$enviado = $enviado->LembreteEnviado($aid);
			if ($enviado['resposta']!='Enviado') {
				Icone('enviarlembrete','enviar lembrete','lembreteicon');
				echo "
					<script>
						$('#enviarlembrete').on('click',function() {
							lembreteReserva(".$aid.");
						});
					</script>
				";
			} else {
				Icone('lembreteenviado','lembrete enviado','lembreteenviadoicon');
				echo "
					<script>
						$('#lembreteenviado').css('cursor','not-allowed');
					</script>
				";
			}
		} else {
			Icone('enviarlembrete','lembrete disponível no dia','lembreteicon');
			echo "
				<script>
					$('#enviarlembrete').find('.icone').css({'cursor':'not-allowed', 'opacity':'0.18'});
				</script>
			";
		} // se a reserva é pra hoje
	?>

	<div style='display:flex;flex-direction: column;'>
		<p><?php echo $veiculo['placa'].' - '.$limpeza ?></p>
		<p class='numregistro'>
			<?php echo $contrato_numero ?>
		</p>
		<p style='margin:0;font-size:13px;text-align:left;'>Criada por <b><?php echo $locador['nome'].'</b> em '.strftime('%d/%m/%Y às %Hh', strtotime($aluguel['data'])) ?></p>
		<?php
			if ($caucao>0) {
				echo "<p style='margin:0;font-size:13px;text-align:left;'>Caução de <b>".Dinheiro($caucao)."</b>, pago com ".mb_strtolower($formacaucao)."</p>";
			} // caucao > 0
		?>
	</div>

	<?php
		echo "
		<div style='display:flex;flex-direction:column;margin:1.8% auto;'>
			<label>Veículo reservado</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput'></div>
					<select id='mudaveiculo' class='wrappedinput'>
		";
						$listaVeiculos = new ConsultaDatabase($uid);
						$listaVeiculos = $listaVeiculos->ListaVeiculos();
						if ($listaVeiculos[0]['vid']!=0) {
							foreach($listaVeiculos as $carro) {
								if ( ($carro['ativo']=='S') ) {
									// vê se o veículo tem os dias dessa reserva disponíveis
									$possibilidade = new Conforto($uid);
									$possibilidade = $possibilidade->ModificacaoVeiculoPossivel($aid,$carro['vid']);

									if ($carro['vid']!=$aluguel['vid']) {
										if (count($possibilidade)==0) {
											echo "
												<option data-vid='".$carro['vid']."'>
													".$carro['modelo']."
												</option>
											";
										} // se o carro tá liberado nos dias, mostra
									} else {
										echo "
											<option data-vid='".$carro['vid']."'>
												".$carro['modelo']."
											</option>
										";
									} // se é esse veículo
								} // ativo
							} // foreach carro
						} // vid !=0
		echo "
					</select>
					<div class='posinput'></div>
				</div>
			</div>
		</div>

		<script>
			$('#mudaveiculo').val('".$veiculo['modelo']."');
			$('.posinput').on('click', function() {
				elemento = $(this).siblings('.wrappedinput').attr('id');
				velemento = $('#'+elemento+' option:selected').data('vid');
				$.ajax({
					type: 'POST',
					url: '".$dominio."/painel/reservas/includes/mudaveiculopopup.inc.php',
					data: {
						vid: velemento,
						aid: '".$aid."'
					},
					success: function(mudavid) {
						$('#vestimenta').html(mudavid);
					}
				});
			});
		</script>
		";
	?>

	<div class='listawrap' style='margin-top:3%;'>

		<div id='iniciowrap' style='min-width:49%;max-width:49%;display:inline-block;margin:3px auto;'>
			<label>Início</label>
			<div id='inicioinner' style='min-width:85%;max-width:85%;display:inline-block;'>
				<input type='text' id='inicio' placeholder='Início' value='<?php echo $inicio->format('d/m/Y'); ?>'></input>
			</div>
			<div id='calendarioinicio' class='abrecalendario'>
				<span class="info" aria-label="calendário">
					<img style='width:100%;max-width:26px;height:auto;' src='<?php echo $dominio ?>/img/calendarioformicon.png'></img>
				</span>
			</div>
		</div> <!-- iniciowrap -->

		<div id='devolucaowrap' style='min-width:49%;max-width:49%;display:inline-block;margin:3px auto;'>
			<label>Devolução</label>
			<div id='devolucaoinner' style='min-width:85%;max-width:85%;display:inline-block;'>
				<input type='text' id='devolucao' placeholder='Devolução' value='<?php echo $devolucao->format('d/m/Y'); ?>'></input>
			</div>
			<div id='calendariodevolucao' class='abrecalendario'>
				<span class="info" aria-label="calendário">
					<img style='width:100%;max-width:26px;height:auto;' src='<?php echo $dominio ?>/img/calendarioformicon.png'></img>
				</span>
			</div>
		</div> <!-- devolucaowrap -->

		<div id='datacancelamentowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Data de cancelamento</label>
			<div id='datacancelamentoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<input type='text' id='datacancelamento' placeholder='Data de cancelamento'></input>
			</div>
		</div> <!-- datacancelamentowrap -->

		<div id='horawrap' style='min-width:49%;max-width:49%;display:inline-block;margin:3px auto;'>
			<label>Horário</label>
			<div id='horainner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<select id='hora' placeholder='Hora'>
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
				<!--<input type='text' id='diaria' placeholder='Diária' value='<?php echo $aluguel['diaria'] ?>'></input>-->
			</div>
		</div> <!-- diariawrap -->
	</div> <!-- listawrap -->

	<?php
		if ($_POST['ativa']==1) {
			Icone('alinfo','aluguel','infoicon');
		}
		$pagamentosaluguel = new Conforto($uid);
		$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aid);
		if ($pagamentosaluguel>0) {
			//Icone('verpagamentos','pagamentos','verpagamentosicon');
		} // se existiu algum pagamento

	        $permissao = new Conforto($uid);
	        $permissao = $permissao->Permissao('registro');
	        if ($permissao===true) {
			if ($_POST['ativa']==1) {
				Icone('modificarreserva','modificar','modreservaicon');
				Icone('cancelarreserva','cancelar','cancelarreservaicon');
			}
	        } // permitido

		Icone('linfo','locatário','linfoicon');
		Icone('vinfo','veículo','vinfoicon');
	?>

</div>
<!-- items -->

<script>
	abreFundamental();
	$('#hora').val('<?php echo $inicio->format('H') ?>');

	$('#verhistorico').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/reservas/historico/?aid=<?php echo $aluguel['aid'] ?>';
	});

	$('#verpagamentos').on('click',function () {
		verPagamentoAluguel(<?php echo $aluguel['aid'] ?>,1);
	});

	$('#alinfo').on('click',function () {
		aluguelFundamental(<?php echo $aluguel['aid'] ?>,<?php echo $_POST['ativa'] ?>);
	});

	$('#linfo').on('click',function () {
		locatarioFundamental(<?php echo $aluguel['lid'] ?>);
	});

	$('#vinfo').on('click',function () {
		veiculoFundamental(<?php echo $aluguel['vid'] ?>);
	});

	$('#calendarioinicio').on('click',function () {
		calendarioPop(1,'vestimenta',<?php echo $aluguel['vid'] ?>);
	});
	$('#calendariodevolucao').on('click',function () {
		calendarioPop(2,'vestimenta',<?php echo $aluguel['vid'] ?>);
	});

	$('#modificarreserva').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/reservas/includes/modificarreservapopup.inc.php',
			data: {
				aluguel: <?php echo $aid ?>,
				inicio: $('#inicio').val(),
				devolucao: $('#devolucao').val(),
				horario: $('#hora').val()
			},
			beforeSend: function() {
				/* loadVestimenta('<?php echo $dominio ?>/includes/carregandovestimenta.inc.php'); */
			},
			success: function(modificacao) {
				$('#vestimenta').html(modificacao);
			}
		});
	});

	$('#cancelarreserva').on('click',function () {
		cancelaReserva(<?php echo $aid ?>);
	});

</script>
