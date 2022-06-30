<?php

	include_once __DIR__.'/../setup.inc.php';

	$AssuntoDaCartinha = 'Ophanim.com.br • Senha redefinida';

	$MensagemDaCartinha = "<div style='min-width:100%;max-width:100%;display:inline-block;background-color:var(--roxo);border-radius:4px;padding:5% 0px;padding-top:3%;'>";

	$MensagemDaCartinha .= "
		<!-- 1 linha -->
		<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;padding:0;'>

			<!-- descrição -->
			<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
				<p style='min-width:100%;max-width:100%;padding:0;font-size:21px;font-weight:400;line-height:18px;color:var(--creme);float:left;'>
					Senha redefinida
				</p>

				<p style='min-width:81%;max-width:81%;padding:0px;font-size:15px;font-weight:400;line-height:18px;color:var(--creme);display:inline-block;'>
					Você acessou o site e criou uma nova senha de acesso, então enviamos esse email pra notificar você dessa atividade.
					<br>
					Você também pode <a style='text-decoration:underline;color:var(--creme);' href='".$dominio."/entrar/recuperar-senha/'>recuperar sua senha</a> sempre que precisar!
					<br><br>
					Clique no botão abaixo para entrar:
				</p>
			</div>
			<!-- descrição -->

		</div>
		<!-- 1 linha -->

		<!-- 2 linha -->
		<div style='min-width:100%;max-width:100%;margin:1% auto;float:left;text-align:center;padding:0;'>

			<!-- botão chamada -->
			<a style='text-decoration:none;color:var(--creme);' href='".$dominio."/entrar/'>
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
