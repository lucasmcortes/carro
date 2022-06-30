<?php

	include_once __DIR__.'/../../includes/setup.inc.php';

	if (isset($_SESSION['l_id'])) {
	        redirectToLogin();
	} else {
		include_once __DIR__.'/../../cabecalho.php';

	} // isset $_SESSION['uid']

?>
 <corpo>
	<div id='conteudo' class='conteudo'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
		        <?php
				carregaJS();
			 	tituloPagina('recuperar senha');
				EnviandoImg();
		        ?>
			<div id='resposta' class='retorno' style='min-width:100%;max-width:100%;margin:0 auto;margin-bottom:1.2%;'>
				<p id='respostap' class='retorno'>
					Digite seu email abaixo para criar uma nova senha.
				</p>
			</div> <!-- resposta -->

		        <div id='id03'>
		                <div class='container'>
		                        <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block:'>

		                                <div style='min-width:100%;max-width:100%;display:inline-block;padding:8% 13%;padding-top:3%;'>
		                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
		                                                  <input style='max-width:100%;min-width:100%;' type='email' placeholder='E-mail' name='email' id='email'>
		                                        </div>
		                                       <?php MontaBotao('recuperar','enviarrecuperar'); ?>
		                                </div>

		                        </div>
		                </div> <!-- container -->
		        </div><!--id03-->
		</div> <!-- content -->

        </div> <!-- conteudo -->

	<script>
		$(document).ready(function() {
			enviandoimg = $('#enviando');
		        id03div = $('#id03');
			recuperar = $('#enviarrecuperar');
			recresposta = $('#respostap');
			resposta = $('#resposta');

			function RecuperarSenha() {
				recemail = $('#email').val();

				$.ajax({
					type: 'POST',
					dataType: 'html',
					async: true,
					url: '<?php echo $dominio ?>/entrar/recuperar-senha/includes/recuperar.inc.php',
				data: {
					submitrecup: 1,
					emailrecup: recemail
				},
				beforeSend:function() {
					window.scrollTo(0, 0);
					id03div.css('display', 'none');
					resposta.css('display', 'none');
					enviandoimg.css('display', 'block');
				},
				success: function(recuperandosenha) {
	                                window.scrollTo(0,0);
					resposta.css('display', 'inline-block');
					if (recuperandosenha.includes('enviando') == true) {
						enviandoimg.css('display', 'none');
						id03div.css('display', 'block');
						recresposta.html('Agora acesse o link no seu email enviado para criar uma nova senha!');
					} else {
						enviandoimg.css('display', 'none');
						recresposta.html(recuperandosenha);
						id03div.css('display', 'block');
					}
				}
			});
		}
		recuperar.click(function() {
			RecuperarSenha();
		});
	}); /* document ready */
</script>

<?php include_once __DIR__.'/../../rodape.php'; ?>
