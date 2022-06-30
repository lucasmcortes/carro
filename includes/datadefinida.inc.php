<?php
	include_once __DIR__.'/setup.inc.php';

if (isset($_POST['veiculo'])) {
	$vid = $_POST['veiculo']??0;

	if ($_POST['estagio']==0) {
		// mostra o calendário na vestimenta do vinfo
		$momento = 0;
	} else if ($_POST['estagio']==1) {
		$momento = 'inicio';
	} else if ($_POST['estagio']==2) {
		$momento = 'devolucao';
	} else if ($_POST['estagio']==3) {
		$momento = 'periodo';
	} // estágio

	$ano = $_POST['ano'];

	$tipo =  $_POST['tipo'];
	if ($tipo=='proximo') {
		$mes = $_SESSION['mes']++;
		if ($_SESSION['mes']==13) {
			$mes = $_SESSION['mes'] = 1;
			$_SESSION['ano']++;
		}
	} else if ($tipo=='anterior'){
		$mes = $_SESSION['mes']--;
		if ($_SESSION['mes']==-1) {
			$mes = $_SESSION['mes'] = 12;
			$_SESSION['ano']--;
		}
	} else if ($tipo=='atual') {
		$_SESSION['mes'] = $mes = $_POST['mes'];
		$_SESSION['ano'] = $ano = $_POST['ano'];
	}

	if (!isset($_SESSION['mes'])) {
		$mes = $_SESSION['mes'] = $_POST['mes'];
	} else {
		$mes = $_SESSION['mes'];
	}

	if (!isset($_SESSION['ano'])) {
		$ano = $_SESSION['ano'] = $_POST['ano'];
	} else {
		$ano = $_SESSION['ano'];
	}

	if ($mes <= 9) {
		$mes = '0'.$mes;
		if ($mes==00) {
			$mes = $_SESSION['mes'] = 12;
			$ano--;
			$_SESSION['ano'] = $ano;
		}
	} else if ($mes > 9) {
		$mes = $mes;
	} // mes leading zeros

	$escolha = $ano.'-'.$mes;
	$data_definida = DateTime::createFromFormat('Y-m', $escolha);

	$resultado = "
		<div id='calendarioouterwrap' style='min-height:260px;'>
			<h2 style='min-width:100%;max-width:100%;display:inline-block;margin:1.2% auto;margin-bottom:3%;vertical-align:middle;'>
				".ucfirst(strftime('%B',strtotime($escolha)))." de ".$data_definida->format('Y')."
			</h2>
	";
	$resultado .= CalendarioVeiculo($mes,$ano,$vid);
	$resultado .= "</div>";
	$resultado .= "<div id='respostadisponibilidadepordata' style='font-size:12px;text-align:left;'></div>";
	if ($momento!==0) {
		if ($momento=='periodo') {
			// pra escolher por período
			$resultado .= "
				<script>
					dataDesejada();
				</script>
			";
		} else {
			// $momento = 'inicio' ou $momento = 'devolucao'
			// é um calendário pra escolher uma data
			$resultado .= "
				<script>
					$('.escolhido').on('click',function () {
						dia = $(this).find('.day-number').attr('id').split('_')[1];
						diaid = $(this).find('.day-number').attr('id');
						dia = diaid.split('_')[1];
						dianumero = dia.split('-')[2];
						mesnumero = dia.split('-')[1];
						anonumero = dia.split('-')[0];
						dia = dianumero+'/'+mesnumero+'/'+anonumero;

						if ( (!$(this).hasClass('calendar-day-ocupado')) && (!$(this).hasClass('calendar-day-antes')) ) {
							$('#".$momento."').val(dia);
							$('.calendar-day-disponivel').css({
								'background-color':'var(--verde)',
								'color':'var(--preto)'
							});
							$('.calendar-day-devolvido').css({
								'background-color':'var(--amarelo)',
								'color':'var(--preto)'
							});
							$(this).css({
								'background-color':'var(--preto)',
								'color':'var(--branco)'
							});
						}
					});
				</script>
			";
		}
	} // momento > 0

	echo $resultado;
} else {
	echo ':((';
} // isset veiculo

?>
