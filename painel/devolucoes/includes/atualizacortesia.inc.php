<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['cortesias_utilizadas_nesse_aluguel'])) {

	if ($_POST['numero_real_de_dias_na_devolucao']>$_POST['dias_previstos']) {
		$diferenca_adicionais = $_POST['numero_real_de_dias_na_devolucao'] - $_POST['dias_previstos'];

		$preco_previsto = 0;
		for ($dc=1;$dc<=$_POST['dias_previstos'];$dc++) {
			$preco_previsto += $_POST['diaria'];
		} // valor das diárias previstas

		$preco_adicionais = 0;
		for ($da=1;$da<=$diferenca_adicionais;$da++) {
			$preco_adicionais += $_POST['preco_diaria_excedente'];
		} // valor das diárias adicionais

		$preco_desconto_cortesias = 0;
		for ($dd=1;$dd<=$_POST['cortesias_utilizadas_nesse_aluguel'];$dd++) {
			if ($dd>$_POST['dias_previstos']) {
				$preco_desconto_cortesias += $_POST['preco_diaria_excedente'];
			} else {
				$preco_desconto_cortesias += $_POST['diaria'];
			}
		} // valor pra descontar pelas diárias de cortesia

		$preco_final = $preco_previsto + $preco_adicionais - $preco_desconto_cortesias;

	} else {
		$preco_final = $_POST['diaria'] * ($_POST['numero_real_de_dias_na_devolucao']-$_POST['cortesias_utilizadas_nesse_aluguel']);
	} // se tem diárias excedentes

	$valor_total = $preco_final+$_POST['valor_km_a_mais']+$_POST['limpezavalor']+$_POST['valor_adicional'];

	$valor_mostrado = $valor_total-$_POST['pagamentosaluguel'];
	if (($valor_mostrado)>0) {
		$resposta_valor_mostrado = "
			<p class='headerslotrelatorio'><b>Total a ser pago:</b></p>
			<p class='infoslotrelatorio'>".Dinheiro($valor_mostrado)."</p>
		";
	} else {
		$resposta_valor_mostrado = "
			<p class='headerslotrelatorio'><b>Total a ser devolvido:</b></p>
			<p class='infoslotrelatorio'>".Dinheiro(str_replace('-','',$valor_mostrado))."</p>
		";
	} // valor total > 0

	if ($_POST['cortesias_utilizadas_nesse_aluguel']>0) {
		if ($_POST['cortesias_utilizadas_nesse_aluguel']>$_POST['dias_previstos']) {
			$esclarecimento_diarias = $_POST['dias_previstos'].' x '.Dinheiro($_POST['diaria']). ' + '.$_POST['cortesias_utilizadas_nesse_aluguel']-$_POST['dias_previstos'].' x '.Dinheiro($_POST['preco_diaria_excedente']);
			$exibe_preco_final = Dinheiro($preco_final?:0).' (desconto de '.$esclarecimento_diarias.')';
		} else {
			$exibe_preco_final = Dinheiro($preco_final?:0).' (desconto de '.$_POST['cortesias_utilizadas_nesse_aluguel'].' cortesia(s) x '.Dinheiro($_POST['diaria']).')';
		}
	} else {
		$exibe_preco_final = Dinheiro($preco_final?:0);
	}


	$resultado = array(
		'preco_final'=>$exibe_preco_final,
		'valor_total'=>Dinheiro($valor_total?:0),
		'valor_mostrado'=>$resposta_valor_mostrado
	);

	header('Content-Type: application/json;');
	echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

} // $_post
?>
