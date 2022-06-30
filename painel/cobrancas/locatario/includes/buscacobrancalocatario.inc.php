<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
	$string_resultado = '';
	$cobrancasstring = '';
	$cobrancaslocatario = [];
	$tabela = "";

	$termo = '%'.$_POST['locatario'].'%';
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->BuscaUmLocatario($termo);
	if ($locatario['lid']!=0) {
		$cobrancas = new ConsultaDatabase($uid);
		$cobrancas = $cobrancas->CobrancasLocatario($locatario['lid']);
		if ($cobrancas[0]['coid']!=0) {
			foreach ($cobrancas as $cobranca) {
				$residual = 0;
				if ($cobranca['valor']!=0) {
					$cobrancaslocatario[] = $cobranca;
					$aluguel = new ConsultaDatabase($uid);
					$aluguel = $aluguel->AluguelInfo($cobranca['aid']);

					$residual = new Conforto($uid);
					$residual = $residual->Residual($cobranca['coid']);

					$tabela .= "
							<div id='cobrancawrap_".$cobranca['coid']."' class='relatoriowrap'>
								<div class='slotrelatoriowrap'>
									<div class='slotrelatorio'>
										<p class='headerslotrelatorio'><b>Locatário:</b></p>
										<p class='infoslotrelatorio'>".$locatario['nome']."</p>
									</div>
								</div>
								<div class='slotrelatoriowrap'>
									<div class='slotrelatorio'>
										<p class='headerslotrelatorio'><b>Valor da fatura:</b></p>
										<p class='infoslotrelatorio'>".Dinheiro($cobranca['valor'])."</p>
									</div>
								</div>
								<div class='slotrelatoriowrap'>
									<div class='slotrelatorio'>
										<p class='headerslotrelatorio'><b>Status:</b></p>
										<p class='infoslotrelatorio'>".$residual['status']."".$residual['valor']."</p>
									</div>
								</div>
							</div>
					";
				} // R$ > 0
			} // foreach
			$string_resultado = 'Listando '.count($cobrancaslocatario).' cobrança(s) em nome de '.$locatario['nome'];
			$cobrancaslocatario['locatario'] = $locatario;
			$tabela .= "
			<script>
				$('.relatoriowrap').on('click', function() {
					coid = $(this).attr('id').split('_')[1];
					cobrancaFundamental(coid);
				});
			</script>
			";
		} // coid > 0
	} else {
		$string_resultado .= 'Locatário não encontrado';
	}
} else {
	$string_resultado .= ':((';
}// $_post

$resultado = array(
	'resposta'=>$string_resultado,
	'cobrancas'=>$cobrancaslocatario,
	'tabela'=>$tabela
);

header('Content-Type: application/json;');
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
