<?php

include_once __DIR__.'/../../includes/setup.inc.php';
carregaJS();
BotaoFechar();

include_once __DIR__.'/../includes/entrar-slot.inc.php';

?>
<script>
	abreFundamental();
	$(document).ready(function() {
		setFulldamental();
	});
</script>
