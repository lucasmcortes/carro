<?php

	include_once __DIR__.'/../setup.inc.php';

	$AssuntoDaCartinha = 'Suporte para sistema de aluguel';

	$MensagemDaCartinha = "
		<!-- 1 linha -->
		<div style='min-width:100%;max-width:100%;display:inline-block;'>

			<!-- descrição -->
			<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
				<p style='min-width:100%;max-width:100%;padding:0;font-size:21px;font-weight:400;line-height:18px;color:var(--preto);display:inline-block;'>
					<span style='font-size:34px;'>Mensagem</span>
				</p>
			</div>
			<!-- descrição -->

		</div>
		<!-- 1 linha -->
	";

	$MensagemDaCartinha .= "
		<!-- 1 linha -->
		<div style='min-width:100%;max-width:100%;display:inline-block;border-top:3px solid var(--cinza);padding-top:3%;'>

			<!-- descrição -->
			<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
				<div style='width:auto;max-width:720px;margin:0 auto;display:inline-block;padding:5% 0px;padding-top:3.6%;background-color:lightgoldenrodyellow;border-left:13px solid var(--rosa);'>
					<div style='display:inline-block;vertical-align:super;'>
						<img style='width:100%;max-width:55px;height:auto;' src='".$dominio."/img/msgsuporteicon.png'></img>
					</div>
					<p style='min-width:81%;max-width:81%;padding:3%;font-size:18px;font-weight:400;line-height:23px;text-align:left;color:var(--preto);display:inline-block;'>
						<span style='font-size:23px;'>O</span> <b>".$_SESSION['suporte']['nome']."</b> enviou o seguinte:
						<br><br>
						".$_SESSION['suporte']['mensagem']."
					</p>
				</div>
			</div>
			<!-- descrição -->

		</div>
		<!-- 1 linha -->
	";

	if (isset($_SESSION['suporte']['img'])) {
		$MensagemDaCartinha .= "
			<!-- 1 linha -->
			<div style='min-width:100%;max-width:100%;display:inline-block;border-top:3px solid var(--cinza);padding-top:3%;'>

				<!-- descrição -->
				<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
					<div style='width:auto;max-width:720px;margin:0 auto;display:inline-block;padding:5% 0px;padding-top:3.6%;background-color:lightgoldenrodyellow;border-left:13px solid var(--rosa);'>
						<img style='width:100%;max-width:55px;height:auto;' src='".$_SESSION['suporte']['img']."'></img>
					</div>
				</div>
				<!-- descrição -->

			</div>
			<!-- 1 linha -->
		";
	} // tem img

	$MensagemDaCartinha .= "

		<!-- 2 linha -->
		<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;padding:0;padding-bottom:3%;'>
			<p style='min-width:81%;max-width:81%;padding:3% auto;font-size:18px;font-weight:400;line-height:23px;text-align:center;color:var(--preto);display:inline-block;'>
				Clique no botão abaixo para responder:
			</p>

			<!-- botão chamada -->
			<div style='cursor:pointer;min-width:270px;max-width:270px;margin:0 auto;display:inline-block;cursor:pointer;'>
				<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;'>
					<p style='font-size:21px;margin:0 auto;border-radius:34px;padding:7px 9px;background-color:var(--preto);'>
						<a style='text-decoration:none;color:var(--branco);' href='mailto:".$_SESSION['suporte']['email']."'>
							responder
						</a>
					</p>
				</div>
			</div>
			<!-- botão chamada -->

		</div>
		<!-- 2 linha -->
	";

?>
