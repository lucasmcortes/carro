<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['vidNovo'])) {
	$vid = $_POST['vidNovo']; // carro para ser modificado e reservado no lugar do atual
	$vidAtual = $_POST['vidAtual']; // carro do aluguel atualmente
	$aid = $_POST['aid'];
	$resultado = '';

	$mudavid = new UpdateRow();
	$mudavid = $mudavid->UpdateVeiculoDoAluguel($vid,$aid);
	if ($mudavid===true) {
		$resultado = "
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p class='respostaalteracao'>
					O veículo reservado foi modificado com sucesso.
				</p>
				<script>
					reservaFundamental(".$aid.", 1);
					setTimeout(function() {
						$('#fecharvestimenta').trigger('click');
					},5000);
				</script>
			</div>
		";
	} else {
		$resultado = "
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p class='respostaalteracao'>
					Erro ao modificar o veículo reservado.
				</p>
				<script>
					setTimeout(function() {
						$('#fecharvestimenta').trigger('click');
					},5000);
				</script>
			</div>
		";
	}// mudavid true

} else {
	$resultado = 0;
}// $_post

$resultado .= '
	<script>
		atualizaCard('.$vid.');
		atualizaCard('.$vidAtual.');
	</script>';

echo $resultado;
?>
