<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['mid'])) {
	$mid = $_POST['mid'];

	$inicio = $_POST['inicio'];
	$inicio = explode('/',$inicio);
	$inicio = $inicio[2].'-'.$inicio[1].'-'.$inicio[0];
	$inicio_string = $inicio;

	$devolucao = $_POST['devolucao'];
	$devolucao = explode('/',$devolucao);
	$devolucao = $devolucao[2].'-'.$devolucao[1].'-'.$devolucao[0];
	$devolucao_string = $devolucao;

	$manutencao = new ConsultaDatabase($uid);
	$manutencao = $manutencao->Manutencao($mid);

	$manutencaoreserva = new ConsultaDatabase($uid);
	$manutencaoreserva = $manutencaoreserva->ManutencaoReserva($mid);

	$comeco_modificado = new DateTime($inicio_string);
	$conclusao_modificada = new DateTime($devolucao_string);

	$comeco_original = new DateTime($manutencaoreserva['inicio']);
	$conclusao_original = new DateTime($manutencaoreserva['devolucao']);

	if ( ($comeco_original->format('Y-m-d H')==$comeco_modificado->format('Y-m-d H')) && ($conclusao_original->format('Y-m-d H')==$conclusao_modificada->format('Y-m-d H')) ) {
		$dias_agendados = 0;
	} else if ( ($comeco_original->format('Y-m-d H')!=$comeco_modificado->format('Y-m-d H')) || ($conclusao_original->format('Y-m-d H')!=$conclusao_modificada->format('Y-m-d H')) ) {
		// se haverá alguma modificação nas datas da reserva, consulta possibilidade
		$possibilidade = new Conforto($uid);
		$possibilidade = $possibilidade->ModificacaoManutencaoPossivel($mid,$comeco_modificado,$conclusao_modificada);
		if (count($possibilidade)>0) {
			$dias_agendados = 'O(s) dia(s) ';
			foreach ($possibilidade as $dia) {
				$dia = new DateTime($dia);
				$dia = $dia->format('d/m/Y');
				$dias_agendados .= '<b>'.$dia.'</b>, ';
			} // foreach
			$dias_agendados = rtrim($dias_agendados,', ');
			$dias_agendados .= ' já estão agendados para esse veículo.<br>';
		} else {
			$dias_agendados = '';
		} // existem dias desejados nessa modificação que estão agendados por outra reserva
	} // datas iguais

} else {
	$vid = 0;
}// $_post

if ( ($dias_agendados!='') && ($dias_agendados!=0) ) {
	echo "
	<!-- items -->
	<div class='items'>
	";
		tituloPagina('modificar');
	echo "
		<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>
			<div style='min-width:100%;max-width:100%;margin:3% auto;margin-bottom:8%;display:inline-block;'>
			 	".$dias_agendados."
			</div>
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
	";
				MontaBotao('voltar','voltar');
	echo "
			</div>
		</div>
	</div>
	<!-- items -->
	";
} else if ($dias_agendados==0) {
	echo "
	<!-- items -->
	<div class='items'>
	";
		tituloPagina('modificar');
	echo "
		<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>
			<div style='min-width:100%;max-width:100%;margin:3% auto;margin-bottom:8%;display:inline-block;'>
			 	Os dias selecionados são os mesmos da reserva original
			</div>
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
	";
				MontaBotao('voltar','voltar');
	echo "
			</div>
		</div>
	</div>
	<!-- items -->
	";

} else {
	$comeco_modificado = new DateTime($inicio_string);
	$conclusao_modificada = new DateTime($devolucao_string);
	echo "
	<!-- items -->
	<div class='items'>
	";
		tituloPagina('confirmação');
	echo "
		<div id='resultado' style='text-align:center;margin:0 auto;margin-top:5%;'>
			<div style='min-width:100%;max-width:100%;margin:3% auto;margin-bottom:8%;display:inline-block;'>
				<b>Início</b>
				<br>
			 	<b>Original</b>: ".$comeco_original->format('d/m/Y')." às ".$comeco_original->format('H')."h<br>
				<b>Modificado</b>: ".$comeco_modificado->format('d/m/Y')." às ".$comeco_modificado->format('H')."h<br>
				<br>
				<b>Devolução</b>
				<br>
			 	<b>Original</b>: ".$conclusao_original->format('d/m/Y')." às ".$conclusao_original->format('H')."h<br>
				<b>Modificada</b>: ".$conclusao_modificada->format('d/m/Y')." às ".$conclusao_modificada->format('H')."h<br>
			</div>
			<div style='min-width:48%;max-width:48%;display:inline-block;'>
	";
				MontaBotao('voltar','voltar');
	echo "
			</div>
			<div style='min-width:48%;max-width:48%;display:inline-block;'>
	";
				MontaBotao('modificar','modificar')."
			</div>
	";
	echo "
		</div>
	</div>
	<!-- items -->
	";
}
?>

<script>
	abreVestimenta();

	$('#voltar').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});

	$('#modificar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/manutencoes/includes/modificarreservamanutencao.inc.php',
			data: {
				mid: '<?php echo $mid ?>',
				comeco_modificado: '<?php echo $comeco_modificado->format('Y-m-d H:i:s.u'); ?>',
				conclusao_modificada: '<?php echo $conclusao_modificada->format('Y-m-d H:i:s.u'); ?>'
			},
			success: function(modificacao) {
				$('#resultado').html(modificacao);
				if (modificacao.includes('sucesso') == true) {
					$('#resultado').append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});
</script>
