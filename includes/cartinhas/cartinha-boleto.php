<?php

	include_once __DIR__.'/../setup.inc.php';

	$AssuntoDaCartinha = 'Ophanim.com.br • Seu boleto está aqui';

	$MensagemDaCartinha = "<div style='min-width:100%;max-width:100%;display:inline-block;background-color:var(--roxo);border-radius:4px;padding:5% 0px;padding-top:3%;'>";

	$MensagemDaCartinha .= "
		<!-- 1 linha -->
		<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;padding:0;'>

			<!-- descrição -->
			<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
				<p style='min-width:100%;max-width:100%;padding:0;font-size:21px;font-weight:400;line-height:18px;color:var(--creme);float:left;'>
					<span style='font-size:34px;'>O</span>lá, ".$_SESSION['boleto']['nome'].",
				</p>

				<p style='min-width:81%;max-width:81%;padding:0px;font-size:15px;font-weight:400;line-height:18px;color:var(--creme);display:inline-block;'>
					Seu boleto para pagamento da licença ".$_SESSION['boleto']['licenca']." da aplicação <b>Ophanim</b> foi gerado com sucesso!
					<br><br>
					Assim que o pagamento for processado e confirmado [geralmente em até 3 dias úteis] sua licença será liberada automaticamente em sua conta.
					<br><br>
					O cógigo de barras para pagamento é esse aqui:
					<br>
					<b>".$_SESSION['boleto']['formatted_barcode']."</b>
					<br><br>
					Você também pode ver e imprimir o seu boleto através do botão abaixo:
				</p>
			</div>
			<!-- descrição -->

		</div>
		<!-- 1 linha -->

		<!-- 2 linha -->
		<div style='min-width:100%;max-width:100%;margin:1% auto;float:left;text-align:center;padding:0;'>

			<!-- botão chamada -->
			<a style='text-decoration:none;color:var(--creme);' href='".$_SESSION['boleto']['link']."'>
				<div style='min-width:270px;max-width:270px;margin:0 auto;display:inline-block;cursor:pointer;'>
					<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;'>
						<p style='font-size:21px;margin:0 auto;border-radius:34px;padding:7px 9px;background-color:var(--rosa);'>
							ver boleto
						</p>
					</div>
				</div>
			</a>
			<!-- botão chamada -->

		</div>
		<!-- 2 linha -->

		<!-- 3 linha -->
		<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;padding:1% 0px;padding-top:0;'>

		<!-- descrição -->
		<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
			<p style='min-width:81%;max-width:81%;padding:0px;font-size:15px;font-weight:400;line-height:18px;color:var(--creme);display:inline-block;'>
				Caso tenha alguma dúvida, você pode <a style='text-decoration:underline;color:var(--creme);' href='".$dominio."/contato/' target='_blank'>entrar em contato</a>.
			</p>
		</div>
		<!-- descrição -->

		</div>
		<!-- 3 linha -->
	";

	$MensagemDaCartinha .= "</div>";

?>
