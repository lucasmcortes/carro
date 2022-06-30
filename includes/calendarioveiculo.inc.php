<?php
	include_once __DIR__.'/setup.inc.php';

if (isset($_POST['veiculo'])) {
	$estagio = $_POST['calendario'];
	$lugar = $_POST['lugar'];

	$vid = $_POST['veiculo'];
	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);

	echo "
		<div id='calendarioveiculo' class='items'>
			<div style='display:inline-block;position:absolute;top:45%;width:10%;left:3%;'>
				<h2 id='anterior' style='cursor:pointer;'><</h2>
			</div>

			<div id='atual' style='display:inline-block;width:72%;'>
			</div>

			<div style='display:inline-block;position:absolute;top:45%;width:10%;right:3%;'>
				<h2 id='proximo' style='cursor:pointer;'>></h2>
			</div>
		</div>
	";
	if ($lugar=='vestimenta') {
		BotaoFecharVestimenta();
		echo "
			<script>
				abreVestimenta();
			</script>
		";
	} else {
		BotaoFechar();
		echo "
			<script>
				abreFundamental();
			</script>
		";
	} // onde abrir o calendário
?>

<script>
	$(document).ready(function() {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/includes/datadefinida.inc.php',
			data: {
				veiculo: <?php echo $vid ?>,
				ano: <?php echo $agora->format('Y'); ?>,
				mes: <?php echo $agora->format('m'); ?>,
				estagio: <?php echo $estagio; ?>,
				tipo: 'atual'
			},
			success: function(data) {
				$('#atual').html(data);
			}
		});
	});

	$('#proximo').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/includes/datadefinida.inc.php',
			data: {
				veiculo: <?php echo $vid ?>,
				ano: <?php echo $agora->format('Y'); ?>,
				mes: <?php echo $agora->format('m'); ?>,
				estagio: <?php echo $estagio; ?>,
				tipo: $(this).attr('id')
			},
			success: function(proximo) {
				$('#atual').html(proximo);
			}
		});
	});

	$('#anterior').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/includes/datadefinida.inc.php',
			data: {
				veiculo: <?php echo $vid ?>,
				ano: <?php echo $agora->format('Y'); ?>,
				mes: <?php echo $agora->format('m'); ?>,
				estagio: <?php echo $estagio; ?>,
				tipo: $(this).attr('id')
			},
			success: function(anterior) {
				$('#atual').html(anterior);
			}
		});
	});
</script>

<?php
} // isset post
?>
