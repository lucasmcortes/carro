<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($uid)) {
	echo "
	<!-- items -->
	<div class='items'>
	";

	tituloPagina('cartão');
	EnviandoImg();

	$cartao = new ConsultaDatabase($uid);
	$cartao = $cartao->UserCartao($uid);

	echo "
		<div style='padding:3%;border-radius: var(--radius);background-color:var(--creme);'>
			<div style='display:flex;flex-direction:column;gap:8px;'>
				<div style='display:flex;flex-direction:column;gap:2%;'>
					<p style='flex:1;margin:unset;font-size:12px;'>Nome no cartão</p>
					<p style='flex:1;margin:unset;'>".$cartao['nome']."</p>
				</div>

				<div style='display:flex;flex-direction:column;gap:2%;'>
					<p style='flex:1;margin:unset;font-size:12px;'>CPF do titular</p>
					<p style='flex:1;margin:unset;'>".$cartao['cpf']."</p>
				</div>

				<div style='display:flex;flex-direction:column;gap:2%;'>
					<p style='flex:1;margin:unset;font-size:12px;'>Número do cartão</p>
					<p style='flex:1;margin:unset;'>".$cartao['numero']."</p>
				</div>

				<div style='display:flex;flex-direction:row;gap:2%;'>
					<div style='flex:1;display:flex;flex-direction:column;gap:2%;'>
						<p style='flex:1;margin:unset;font-size:12px;'>Expiração</p>
						<p style='flex:1;margin:unset;'>".$cartao['dataexp']."</p>
					</div>
					<div style='flex:1;display:flex;flex-direction:column;gap:2%;'>
						<p style='flex:1;margin:unset;font-size:12px;'>CVC</p>
						<p style='flex:1;margin:unset;'>".$cartao['cvc']."</p>
					</div>
				</div>
			</div>
		</div>

		<div class='contalinha'>
			<div>";MontaBotaoSecundario('fechar','fecharcardid');echo"</div>
			<div>";MontaBotao('novo cartão','editarcartao');echo"</div>
		</div>

	</div>
	<!-- items -->

	<script>
		abreFundamental();

		$('#fecharcardid').on('click',function() {
			$('#fechar').trigger('click');
		});

		$('#editarcartao').on('click',function () {
			loadFundamental('".$dominio."/minhaconta/cartao/adicionar');
		});
	</script>
	";
} else {
	echo "
		<script>
			$('#fechar').trigger('click');
		</script>
	";
}// $_post
?>
