<?php

include_once __DIR__.'/../../includes/setup.inc.php';
BotaoFechar();

if (isset($uid)) {
	echo "
	<!-- items -->
	<div class='items'>
	";

	tituloPagina('cartão');
	EnviandoImg();
	
	echo "
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
						$('.retorno').html(addcartao['resposta']);
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
