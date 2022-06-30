<?php
	include_once __DIR__.'/setup.inc.php';
	BotaoFecharVestimenta();
?>

	<!-- items -->
	<div style="overflow:auto;height:auto;max-height:81vh;">
		<img class='fotoimg' style='max-width:300%;max-height:300%;' src='<?php echo $dominio ?>/painel/configuracoes/logo/<?php echo $logo_empresa."?".rand(1, 999) ?>'></img>
	</div>
	<!-- items -->

        <script>
        	abreVestimenta();
		$('#vestimenta').css('max-width','900px');
        </script>
