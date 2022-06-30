<?php

	include_once __DIR__.'/setup.inc.php';
	BotaoFecharVestimenta();

	echo "<div style='margin-bottom:13%;'>";
		EnviandoImg();
	echo '</div>';

?>
<script>
	abreVestimenta();
	$('#enviando').css('display','inline-block');
</script>
