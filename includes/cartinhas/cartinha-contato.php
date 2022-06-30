<?php

	include_once __DIR__.'/../setup.inc.php';

	$AssuntoDaCartinha = 'Ophanim.com.br • Formulário de contato';

	$MensagemDaCartinha = "<div style='min-width:100%;max-width:100%;display:inline-block;background-color:var(--roxo);border-radius:4px;padding:5% 0px;padding-top:3%;'>";

	$MensagemDaCartinha .= "
		<!-- 1 linha -->
		<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;padding:0;'>

			<!-- descrição -->
			<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
				<p style='min-width:100%;max-width:100%;padding:0;font-size:21px;font-weight:400;line-height:18px;color:var(--creme);float:left;'>
					Oi!
				</p>

				<p style='min-width:81%;max-width:81%;padding:0px;font-size:15px;font-weight:400;line-height:18px;color:var(--creme);display:inline-block;'>
					A(o) ".$_SESSION['nome_contato']." mandou uma mensagem pelo formulário de contato do site dizendo o seguinte:
						<br>
						".$_SESSION['mensagem_contato']."
						<br>
						E o email dela(e) é: ".$_SESSION['email_contato']."
						<br>
						E o telefone dela(e) é: ".$_SESSION['telefone_contato']."
				</p>
			</div>
			<!-- descrição -->

		</div>
		<!-- 1 linha -->

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
