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
		<p class='retorno'></p>
		<div id='formulariocartao'>
			<div style='border-radius: var(--radius);'>
				<div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>
					<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
						<label>Nome no cartão</label>
						<input style='max-width:100%;min-width:100%;' type='text' placeholder='Nome no cartão' name='nomecartao' id='nomecartao'>
					</div>

					<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
						<label>CPF do titular</label>
						<input style='max-width:100%;min-width:100%;' type='text' onkeyup=\"maskIt(this,event,'###.###.###-##')\" placeholder='999.999.999-99' name='cpfcartao' id='cpfcartao'>
					</div>

					<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
						<label>Número do cartão</label>
						<input style='max-width:100%;min-width:100%;' type='text' onkeyup=\"maskIt(this,event,'#### #### #### ####')\" placeholder='XXXX XXXX XXXX XXXX' name='numerocartao' id='numerocartao'>
					</div>

					<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
						<div style='max-width:49%;min-width:49%;margin:0 auto;float:left;'>
							<label>Expiração</label>
							<input type='text' onkeyup=\"maskIt(this,event,'##/##')\" placeholder='MM/AA' name='expiracaocartao' id='expiracaocartao' style='max-width:100%;min-width:100%;'>
						</div>
						<div style='max-width:49%;min-width:49%;margin:0 auto;float:right;'>
							<label>CVC</label>
							<input type='text' onkeyup=\"maskIt(this,event,'###')\" placeholder='CVC' name='cvccartao' id='cvccartao' style='max-width:100%;min-width:100%;'>
						</div>
					</div>
				</div>
			</div>

			<div class='contalinha'>
				<div>";MontaBotaoSecundario('fechar','fecharcardid');echo"</div>
				<div>";MontaBotao('cadastrar','cadastrocartao');echo"</div>
			</div>
		</div>

	</div>
	<!-- items -->

	<script>
		abreFundamental();

		$('#fecharcardid').on('click',function() {
			$('#fechar').trigger('click');
		});

		$('#cadastrocartao').on('click',function () {
			nomecartao = $('#nomecartao').val()||0;
			cpfcartao = $('#cpfcartao').val()||0;
			numerocartao = $('#numerocartao').val()||0;
			expiracaocartao = $('#expiracaocartao').val()||0;
			cvccartao = $('#cvccartao').val()||0;

			$.ajax({
				type: 'POST',
				url: '".$dominio."/minhaconta/cartao/addcartao.inc.php',
				data: {
					nome: nomecartao,
					cpf: cpfcartao,
					numero: numerocartao,
					expiracao: expiracaocartao,
					cvc: cvccartao
				},
				success: function(addcartao) {
					if (addcartao['resposta']!='') {
						if (window['addcartao']==1) {
							// veio do fluxo de comprar a licença
							loadFundamental('".$dominio."/minhaconta/plano');
						} else {
							// fluxo de ver/adicionar um cartao
							$('.retorno').html('<p class=\"respostaalteracao\">'+addcartao['resposta']+'</p>');
							$('#formulariocartao').html('<img id=\"sucessogif\" src=\"".$dominio."/img/sucesso.gif\">');
							mostraFooter();
							setTimeout(function() {
								window.location.href = '".$dominio."/minhaconta/';
							}, 10000);
						}
					} else {
						$('.retorno').html('Preencha os campos corretamente');
					}
				}
			});
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
