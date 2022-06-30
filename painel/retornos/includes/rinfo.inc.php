<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['retorno'])) {
	$rid = $_POST['retorno'];
	$retorno = new ConsultaDatabase($uid);
	$retorno = $retorno->Retorno($rid);

	$kilometragemretorno = new ConsultaDatabase($uid);
	$kilometragemretorno = $kilometragemretorno->KilometragemData($retorno['data']);

	$manutencao = new ConsultaDatabase($uid);
	$manutencao = $manutencao->Manutencao($retorno['mid']);

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($manutencao['vid']);

	$categoria = new ConsultaDatabase($uid);
	$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);

	$disponibilidade_veiculo = new Conforto($uid);
	$disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($veiculo['vid']);
	$disponibilidade = $disponibilidade_veiculo['status'];

	$enviador = new ConsultaDatabase($uid);
	$enviador = $enviador->AdminInfo($manutencao['uid']);

	$retornador = new ConsultaDatabase($uid);
	$retornador = $retornador->AdminInfo($retorno['uid']);

	$inicio = new DateTime($manutencao['inicio']);
	$devolucao = new DateTime($manutencao['devolucao']);
} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloCarro($veiculo['modelo']); ?>

	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p style='display:inline-block;'><?php echo $veiculo['placa'] ?></p>
	</div>
	<div style='text-align:center;margin:8% auto;margin-bottom:13%;padding:0 3%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:5%;'>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='text-align:left;'>Onde estava:<b> <?php echo $manutencao['estabelecimento'] ?></b></p>
			</div>
		</div>

		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:5%;'>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='text-align:left;'>Enviado por: <b><?php echo $enviador['nome'] ?></b> </p>
			</div>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='text-align:left;'>Enviado em:<b> <?php echo $inicio->format('d/m/Y') ?></b></p>
			</div>
		</div>

		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:5%;'>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='text-align:left;'>Retornado por:<b> <?php echo $retornador['nome'] ?></b> </p>
			</div>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='text-align:left;'>Retornou em:<b> <?php echo $devolucao->format('d/m/Y') ?></b></p>
			</div>
			<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
				<p style='text-align:left;'>Retornou com:<b> <?php echo Kilometragem($kilometragemretorno['km']) ?></b></p>
			</div>
		</div>
		
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='text-align:left;'>Observação:<b> <?php echo $retorno['observacao'] ?></b></p>
		</div>
	</div>

	<?php Icone('vinfo','veículo','vinfoicon'); ?>
</div>
<!-- items -->

<script>

	abreFundamental();

	$('#vinfo').on('click', function() {
		veiculoFundamental(<?php echo $manutencao['vid'] ?>);
	});

</script>
