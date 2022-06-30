<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['devolucaoconfirmada'])) {
	$aid = $_POST['aid'];
	$devolvendo = '';
	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);

	$diaria = $_POST['diaria'];
	$numero_real_de_dias_na_devolucao = $_POST['numero_real_de_dias_na_devolucao'];
	$limpezavalor = $_POST['limpezavalor'];
	$cortesias_devolucao = $_POST['cortesias_devolucao'];
	$kilometragem_devolucao = $_POST['kilometragem_devolucao'];
	$pid = $_POST['pid'];
	$dias_previstos = $_POST['dias_previstos'];
	$preco_diaria_excedente = $_POST['preco_diaria_excedente'];
	$cortesias_utilizadas_nesse_aluguel = $_POST['cortesias_utilizadas_nesse_aluguel'];
	$input_cortesias_utilizadas_nesse_aluguel = $_POST['input_cortesias_utilizadas_nesse_aluguel'];

	$vid = $_POST['vid'];
	$limpeza_devolucao = $_POST['limpeza_devolucao'];
	$valor_km_a_mais = $_POST['valor_km_a_mais'];
	$kilometragem_atual = $_POST['kilometragem_atual'];
	$valor_adicional = $_POST['valor_adicional'];
	$descricao_valor_adicional = $_POST['descricao_valor_adicional'];
	$valor_total = $_POST['valor_total'];
	$pagamentosaluguel = $_POST['pagamentosaluguel'];
	$valor_mostrado = $_POST['valor_mostrado'];

	// seta as cortesias pra tabela das cortesias da placa pra ser igual a do input quando ele tiver um número diferente
	//do número automático de dias que usa o limite das configurações pra definir a quantidade de cortesias pra placa disponíveis para esse aluguel
	if ($input_cortesias_utilizadas_nesse_aluguel!=$cortesias_utilizadas_nesse_aluguel) {
		$cortesias_utilizadas_nesse_aluguel = $input_cortesias_utilizadas_nesse_aluguel;

		// calcula o preço do valor da cobrança de novo com o valor das cortesias atualizados pelo input
		if ($numero_real_de_dias_na_devolucao>$dias_previstos) {
			$diferenca_adicionais = $numero_real_de_dias_na_devolucao - $dias_previstos;

			$preco_previsto = 0;
			for ($dc=1;$dc<=$dias_previstos;$dc++) {
				$preco_previsto += $diaria;
			} // valor das diárias previstas

			$preco_adicionais = 0;
			for ($da=1;$da<=$diferenca_adicionais;$da++) {
				$preco_adicionais += $preco_diaria_excedente;
			} // valor das diárias adicionais

			$preco_desconto_cortesias = 0;
			for ($dd=1;$dd<=$cortesias_utilizadas_nesse_aluguel;$dd++) {
				if ($dd>$dias_previstos) {
					$preco_desconto_cortesias += $preco_diaria_excedente;
				} else {
					$preco_desconto_cortesias += $diaria;
				}
			} // valor pra descontar pelas diárias de cortesia

			$preco_final = $preco_previsto + $preco_adicionais - $preco_desconto_cortesias;

		} else {
			$preco_final = $diaria * ($numero_real_de_dias_na_devolucao-$cortesias_utilizadas_nesse_aluguel);
		} // se tem diárias excedentes

		$valor_total = $preco_final+$valor_km_a_mais+$limpezavalor+$valor_adicional;
	} // cortesias_utilizadas_nesse_aluguel !=

	// atualiza a quantidade de cortesias da tabela da devolucao com base na nova quantidade de cortesias que não foram por acionamento
	$cortesias_devolucao = $cortesias_utilizadas_nesse_aluguel;

	$adddevolucao = new setRow();
	$adddevolucao = $adddevolucao->Devolucao($uid,$aid,$limpezavalor,$cortesias_devolucao,$kilometragem_devolucao,$data);
	if ($adddevolucao===true) {
		$consultadevolucao = new ConsultaDatabase($uid);
		$consultadevolucao = $consultadevolucao->Devolucao($aid);
		$addcortesias = new setRow();
		$addcortesias = $addcortesias->Cortesia($uid,$pid,$cortesias_utilizadas_nesse_aluguel,$data);
		if ($addcortesias===true) {
			$addlimpeza = new setRow();
			$addlimpeza = $addlimpeza->Limpeza($uid,$vid,$limpeza_devolucao,$data);
			if ($addlimpeza===true) {
				$addkilometragem = new setRow();
				$addkilometragem = $addkilometragem->Kilometragem($uid,$vid,$kilometragem_atual,$data);
				if ($addkilometragem===true) {
					$addvaloradicional = new setRow();
					$addvaloradicional = $addvaloradicional->ValorAdicional($uid,$consultadevolucao['deid'],$valor_adicional,$descricao_valor_adicional,$data);
					if ($addvaloradicional===true) {
						$addcobranca = new setRow();
						$addcobranca = $addcobranca->Cobranca($uid,$consultadevolucao['deid'],$valor_total,$data);
						if ($addcobranca===true) {
							RespostaRetorno('sucessodevolucao');
							return;
						} else {
							RespostaRetorno('regcobranca');
							return;
						} // addcobranca
					} else {
						RespostaRetorno('regvaloradicional');
						return;
					} // valoradicional true
				} else {
					RespostaRetorno('regkm');
					return;
				} // addkilometragem true
			} else {
				RespostaRetorno('reglimpeza');
				return;
			} // addlimpeza true
		} else {
			RespostaRetorno('regcortesias');
			return;
		} // addcortesias true
	} else {
		RespostaRetorno('regdevolucao');
		return;
	} // adddevolucao true
} else {
	$devolvendo = ':((';
} // isset post submit

echo $devolvendo;

?>
