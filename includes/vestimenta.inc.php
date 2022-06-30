<?php
	include_once __DIR__.'/setup.inc.php';
?>

	<div id='vestimenta' style='display:none;'></div>

	<script>
		$(document).ready(function() {
			larguraInterna();
			visitaWidth();
			setVestimenta();
		});
	</script>
