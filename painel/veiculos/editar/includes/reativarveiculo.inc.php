<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['veiculo'])) {
	$vid = $_POST['veiculo'];
	$resultado = '';

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);
	if ($veiculo['vid']!=0) {
		$addmodificacao = new UpdateRow();
		$addmodificacao = $addmodificacao->UpdateVeiculoAtivo('S',$vid);
		if ($addmodificacao===true) {
			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						Veículo reativado com sucesso.
					</p>
				</div>
			";
		} else {
			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						Erro ao ativar veículo.
					</p>
					<script>
						setTimeout(function() {
							$('#fecharvestimenta').trigger('click');
						},5000);
					</script>
				</div>
			";
		} // addativacao true
	} // vid != 0
} else {
	$resultado = 0;
}// $_post

echo $resultado;
?>
