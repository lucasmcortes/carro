<?php

include_once __DIR__.'/../../includes/setup.inc.php';
BotaoFechar();

if (isset($uid)) {
	echo "
	<!-- items -->
	<div class='items'>
	";

	tituloPagina('comprar');
	EnviandoImg();

	$cartao = new ConsultaDatabase($uid);
	$cartao = $cartao->UserCartao($uid);
	if ($cartao['cardid']==0) {
		echo "
			<script>
				cartaocadastrado = 0;
				$('#cartaobtn').trigger('click');
			</script>
		";
	} else {
		echo "
			<script>
				cartaocadastrado = 1;
			</script>
		";
	} // se tem cartão

	if ( (isset($_GET['e'])) && ($_GET['e']=='erro') ) {
		echo "
			<p id='retorno' class='retorno'>Tente novamente</p>
		";
	} else {
		echo "
			<p id='retorno' class='retorno'>Escolha sua licença</p>
		";
	} // se abriu depois de tentar e dar erro

	echo "
		<script>
			window['addcartao'] = 0;
		</script>

		<div id='formulariopagamento'>
			<div style='display:flex;flex-direction:row;align-items:center;'>
				<p class='opcaoplano' data-modalidade='anual'>Anual</p>
				<p class='opcaoplano opcaoselecionada' data-modalidade='vitalícia'>Vitalícia</p>
			</div>
			<div style='display:flex;flex-direction:row;align-items:center;border-bottom-right-radius:var(--radius);border-bottom-left-radius:var(--radius);background-color:var(--verdedois);'>
				<div style='flex:1;display:flex;flex-direction:column;align-items:center;padding:43px;margin:18px auto;margin-top:0;'>
					<div style='flex:1;'>
						<p>Licença <span id='modalidadelicenca'>vitalícia</span></p>
					</div>
					<div style='flex:1;'>
						<p class='opcaoselecionadapreco'>R$<span id='precomodalidade'>".$preco_vital_vista."</span></p>
					</div>
					<div style='flex:1;'>
						<p>Validade: <span id='validademodalidade' style='font-weight:700;'>".$validade_plano_vital."</span></p>
					</div>
				</div>
			</div>
			<div class='formainnerwrap wrappinho'>
				<p class='listatitulo' data-lista='pagamento'>
				       Forma de pagamento
				</p>
				<div style='padding-bottom:3%;'>
					<div class='listacontainer' style='align-items:center;'>
						<div class='lista' data-lista='pagamento' style='min-width:100%;'>
							<p class='opcoes' data-opcao='cartao'>
								<img class='pagamentoicon' src='".$dominio."/img/cartao.png'></img>Cartão
							</p>
							<!--<p class='opcoes' data-opcao='boleto'>
								<img class='pagamentoicon' src='".$dominio."/img/boleto.png'></img>Boleto
							</p>
							<p class='opcoes' data-opcao='pix'>
								<img class='pagamentoicon' src='".$dominio."/img/pix.png'></img>Pix
							</p> -->
						</div>
					</div>

					<div class='detalhespagamento'>
						<div class='formadepagamento'></div>
					</div>
				</div>
			</div> <!-- formainnerwrap -->
			<div class='contalinha'>
				<div>";MontaBotaoSecundario('voltar','voltarbtn');echo"</div>
				<div>
					<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
						<p class='montabotao sombraabaixo .progresso' id='comprarbotao'>
							comprar
						</p>
					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- items -->

	<script>
		abreFundamental();
		setEscolhas();
		Progresso();
		Selecionadas();

		$('#voltarbtn').on('click',function() {
			$('#fechar').trigger('click');
		});

		modalidade = 'Vitalicia';
		$('.opcaoplano').on('click',function() {
			$('.opcaoplano').removeClass('opcaoselecionada');
			$(this).addClass('opcaoselecionada');
			modalidade = $(this).data('modalidade');
			$('#modalidadelicenca').html(modalidade);
			if (modalidade=='anual') {
				$('#precomodalidade').html('".$preco_anual_vista."');
				$('#validademodalidade').html('".$validade_plano_anual."');
			} else if (modalidade=='vitalícia') {
				$('#precomodalidade').html('".$preco_vital_vista."');
				$('#validademodalidade').html('".$validade_plano_vital."');
			}
		});

		$('#comprarbotao').on('click',function () {
			formulariopagamento = $('#formulariopagamento').html();
			if (escolhas===selecionadas) {
				ComprarLicenca();
			} else {
				$('.retorno').html('Escolha uma forma de pagamento');
			} // opcao de pagamento escolhida
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
