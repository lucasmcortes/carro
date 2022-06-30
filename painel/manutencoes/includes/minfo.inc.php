<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['manutencao'])) {
	$mid = $_POST['manutencao'];
	$manutencao = new ConsultaDatabase($uid);
	$manutencao = $manutencao->Manutencao($mid);
	$agendamento = new DateTime($manutencao['data']);
	$inicio = new DateTime($manutencao['inicio']);
	$devolucao = new DateTime($manutencao['devolucao']);


	echo "
		<script>
			$('#datacancelamentowrap').css('display','none');
		</script>
	";

	$reservamanutencao = new ConsultaDatabase($uid);
	$reservamanutencao = $reservamanutencao->ManutencaoReserva($manutencao['mid']);
	if ($reservamanutencao['mreid']!=0) {
		$manutencaoativa = new ConsultaDatabase($uid);
		$manutencaoativa = $manutencaoativa->ManutencaoAtivacao($reservamanutencao['mreid']);
		if ($manutencaoativa['ativa']=='N') {
			$data_cancelamento = new DateTime($reservamanutencao['data']);
			echo "
				<script>
					$(':input').prop('disabled','disabled');
					$(':input').css('cursor','not-allowed');
					$('#datacancelamentowrap').css('display','inline-block');
					$('#datacancelamento').val('".$data_cancelamento->format('d/m/Y')."');
				</script>
			";
		} else {
			$inicio = new DateTime($reservamanutencao['inicio']);
			$devolucao = new DateTime($reservamanutencao['devolucao']);
		} // se ativa
	} // reserva manutencao

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($manutencao['vid']);

	$categoria = new ConsultaDatabase($uid);
	$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

	$disponibilidade_veiculo = new Conforto($uid);
	$disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($veiculo['vid']);
	$disponibilidade = $disponibilidade_veiculo['status'];

	$administrador = new ConsultaDatabase($uid);
	$administrador = $administrador->AdminInfo($manutencao['uid']);
} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloPagina($veiculo['modelo']); ?>
	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p style='display:inline-block;'><?php echo $veiculo['placa'] ?></p>
	</div>

	<div class='listawrap' style='margin-top:3%;'>

		<div style='text-align:center;margin:8% auto;margin-bottom:8%;padding:0 3%;'>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='display:inline-block;'>Estabelecimento: <b><?php echo $manutencao['estabelecimento'] ?></b></p>
			</div>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='display:inline-block;'>Agendado por: <b><?php echo $administrador['nome'] ?></b> </p>
			</div>
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p style='display:inline-block;'>Agendado em: <b><?php echo $agendamento->format('d/m/Y') ?></b></p>
			</div>
		</div>
		<div id='iniciowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Início</label>
			<div id='inicioinner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<input style='display:inline-block;min-width:89%;max-width:89%;' type='text' id='inicio' placeholder='Início' value='<?php echo $inicio->format('d/m/Y') ?>'></input>
				<div id='calendarioinicio' class='abrecalendario'>
					<span class="info" aria-label="calendário">
						<img style='width:100%;max-width:26px;height:auto;' src='<?php echo $dominio ?>/img/calendarioformicon.png'></img>
					</span>
				</div>
			</div>
		</div> <!-- iniciowrap -->

		<div id='devolucaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Previsão para retorno</label>
			<div id='devolucaoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<input style='display:inline-block;min-width:89%;max-width:89%;' type='text' id='devolucao' placeholder='Devolução' value='<?php echo $devolucao->format('d/m/Y') ?>'></input>
				<div id='calendariodevolucao' class='abrecalendario'>
					<span class="info" aria-label="calendário">
						<img style='width:100%;max-width:26px;height:auto;' src='<?php echo $dominio ?>/img/calendarioformicon.png'></img>
					</span>
				</div>
			</div>
		</div> <!-- devolucaowrap -->

		<div id='datacancelamentowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Data de cancelamento</label>
			<div id='datacancelamentoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
				<input type='text' id='datacancelamento' placeholder='Data de cancelamento'></input>
			</div>
		</div> <!-- datacancelamentowrap -->
	</div> <!-- listawrap -->


	<?php
		if ($inicio->format('Y-m-d H:i')<$agora->format('Y-m-d H:i')) {
			Icone('adicionarretorno','disponibilizar','disponibilizaricon');
		} else {
			Icone('modificarreservamanutencao','modificar','modmanutencaoicon');
			Icone('cancelarreservamanutencao','cancelar','cancelarmanutencaoicon');
		} // se já começou
		Icone('vinfo','veículo','vinfoicon');
	?>
</div>
<!-- items -->

<script>
	abreFundamental();

	$('#calendarioinicio').on('click',function () {
		calendarioPop(1,'vestimenta',<?php echo $manutencao['vid'] ?>);
	});
	$('#calendariodevolucao').on('click',function () {
		calendarioPop(2,'vestimenta',<?php echo $manutencao['vid'] ?>);
	});

	$('#adicionarretorno').on('click', function() {
		window.location.href='<?php echo $dominio ?>/painel/retornos/novo/?v=<?php echo $manutencao['vid'] ?>';
	});
	$('#vinfo').on('click', function() {
		veiculoFundamental(<?php echo $manutencao['vid'] ?>);
	});

	$('#modificarreservamanutencao').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/manutencoes/includes/modificarreservamanutencaopopup.inc.php',
			data: {
				mid: <?php echo $manutencao['mid'] ?>,
				inicio: $('#inicio').val(),
				devolucao: $('#devolucao').val()
			},
			beforeSend: function() {
				/* loadVestimenta('<?php echo $dominio ?>/includes/carregandovestimenta.inc.php'); */
			},
			success: function(modificacao) {
				$('#vestimenta').html(modificacao);
			}
		});
	});

	$('#cancelarreservamanutencao').on('click',function () {
		cancelaReservaManutencao(<?php echo $manutencao['mid'] ?>);
	});

</script>
