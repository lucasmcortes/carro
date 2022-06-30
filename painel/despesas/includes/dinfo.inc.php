<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['retorno'])) {
	$rid = $_POST['retorno'];
	$retorno = new ConsultaDatabase($uid);
	$retorno = $retorno->Retorno($rid);

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
} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloPagina($veiculo['modelo']); ?>
	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p style='display:inline-block;'><?php echo $veiculo['placa'] ?></p>
	</div>
	<div style='text-align:center;margin:8% auto;margin-bottom:13%;padding:0 3%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
			<p style='display:inline-block;'>Onde estava:<br><b><?php echo $manutencao['estabelecimento'] ?></b></p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
			<p style='display:inline-block;'>Enviado por:<br><b><?php echo $enviador['nome'] ?></b> </p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
			<p style='display:inline-block;'>Enviado em:<br><b><?php echo strftime('%d de %B de %Y às %Hh%M', strtotime($manutencao['data'])) ?></b></p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
			<p style='display:inline-block;'>Retornado por:<br><b><?php echo $retornador['nome'] ?></b> </p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:3%;'>
			<p style='display:inline-block;'>Retornou em:<br><b><?php echo strftime('%d de %B de %Y às %Hh%M', strtotime($retorno['data'])) ?></b></p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;'>Custou:<br><b><?php echo Dinheiro($retorno['valor']); ?></b></p>
		</div>
	</div>
</div>
<!-- items -->

<script>
	abreFundamental();

</script>
