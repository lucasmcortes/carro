<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_POST['licid'])) {

	$cancelaplano = new UpdateRow();
	$cancelaplano = $cancelaplano->CancelaPlano($_POST['licid']);

	if ($cancelaplano===true) {
		$resultado = "
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p class='respostaalteracao'>
					A licença foi cancelada com sucesso.
				</p>
				
				<script>
					setTimeout(function() {
						window.location.href = '".$dominio."/minhaconta/';
					}, 10000);
				</script>
			</div>
		";
	} else {
		$resultado = "
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p class='respostaalteracao'>
					Erro ao cancelar a licença.
				</p>

				<script>
					setTimeout(function() {
						$('#fechar).trigger('click');
					},5000);
				</script>
			</div>
		";
	} // cancelaplano true

} else {
	$resultado = 0;
}// $_post

echo $resultado;

?>
