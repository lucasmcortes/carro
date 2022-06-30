<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];
	$valor = Sanitiza($_POST['valor']?:0);

	$forma = $_POST['forma'];
	$formapagamento = new Conforto($uid);
	$formapagamento = $formapagamento->SwitchForma($forma);
	$forma = $formapagamento;
	
	$ativo = $_POST['ativo'];

	if ( ($valor!=0) && (is_numeric($valor)) && (!empty($forma))) {
		$addpagamentoparcial = new setRow();
		$addpagamentoparcial = $addpagamentoparcial->PagamentoParcial($uid,$aid,$valor,$forma,$data);
		if ($addpagamentoparcial===true) {
			$resultado = 'Pagamento de '.Dinheiro($valor).' registrado';
			$resultado .= "
				<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
					<p class='montabotao' id='ok'>
						ok
					</p>
				</div>
			";
			$resultado .= "
			<script>
				$('#ok').on('click',function () {
					aluguelFundamental(".$aid.",".$ativo.");
					$('#fecharvestimenta').trigger('click');
				});
			</script>
			";
		} else {
			$resultado = 'Tente novamente';
			$resultado .= "
			<script>
				$('#ok').on('click',function () {
					aluguelFundamental(".$aid.",".$ativo.");
					$('#fecharvestimenta').trigger('click');
				});
			</script>
			";
		} // addpagamentoparcial true
	} else {
		$resultado = 'Preencha os dados corretamente';
		$resultado .= "
			<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
				<p class='montabotao' id='voltar'>
					voltar
				</p>
			</div>
		";
		$resultado .= "
		<script>
			$('#voltar').on('click',function () {
				addPagamentoAluguel(".$aid.",".$ativo.");
			});
		</script>
		";
	} // dados inseridos corretamente
} else {
	$resultado = 0;
}// $_post

echo $resultado;
?>
