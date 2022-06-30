<?php

	include_once __DIR__.'/../setup.inc.php';

	$AssuntoDaCartinha = 'Ophanim.com.br • Compra de licença efetuada';

	$MensagemDaCartinha = "<div style='min-width:100%;max-width:100%;display:inline-block;background-color:var(--roxo);border-radius:4px;padding:5% 0px;padding-top:3%;'>";

	$MensagemDaCartinha .= "
		<!-- 1 linha -->
		<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;padding:0;'>

			<!-- descrição -->
			<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
				<p style='min-width:100%;max-width:100%;padding:0;font-size:21px;font-weight:400;line-height:18px;color:var(--creme);float:left;'>
					<span style='font-size:34px;'>O</span>lá, ".$_SESSION['cartao']['nome'].",
				</p>

				<p style='min-width:81%;max-width:81%;padding:0px;font-size:15px;font-weight:400;line-height:18px;color:var(--creme);display:inline-block;'>
					Sua licença ".$_SESSION['cartao']['licenca']." da aplicação <b>Ophanim</b> foi adquirida com sucesso e já está ativa em sua conta.
					<br><br>
					Código de referência da licença:
					<br>
					<span style='font-weight:700;font-size:18px;'>".$_SESSION['cartao']['reference_id']."</span>
					<br><br>
					Data de início: ".$_SESSION['cartao']['inicio']."
					<br>
					Data para renovação: ".$_SESSION['cartao']['renovacao']."
					<br>
					Valor aprovado: ".$_SESSION['cartao']['preco']."
					<br><br>
					Acesse sua conta para ver sua licença pelo botão abaixo:
				</p>
			</div>
			<!-- descrição -->

		</div>
		<!-- 1 linha -->

		<!-- 2 linha -->
		<div style='min-width:100%;max-width:100%;margin:1% auto;float:left;text-align:center;padding:0;'>

			<!-- botão chamada -->
			<a style='text-decoration:none;color:var(--creme);' href='".$dominio."/minhaconta'>
				<div style='min-width:270px;max-width:270px;margin:0 auto;display:inline-block;cursor:pointer;'>
					<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;'>
						<p style='font-size:21px;margin:0 auto;border-radius:34px;padding:7px 9px;background-color:var(--rosa);'>
							acessar conta
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
