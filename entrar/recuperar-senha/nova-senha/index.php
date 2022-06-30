<?php

	include_once __DIR__.'/../../../includes/setup.inc.php';

	if (isset($_SESSION['l_id'])) {
	        redirectToLogin();
	} else {

		$_SESSION['email'] = $email = base64_decode($_REQUEST['email']);
		$chave = $_REQUEST['chave'];

		$achachave = new ConsultaDatabase($uid);
		$achachave = $achachave->EncontraChave($chave);
		if ($achachave===0) {
			redirectToLogin();
		} else {
			$achaadmin = new ConsultaDatabase($uid);
			$achaadmin = $achaadmin->EncontraAdmin($email);
			if ($achaadmin===0) {
				redirectToLogin();
			} else {
				require_once __DIR__.'/../../../cabecalho.php';
			} // achaadmin = 0
		} // achachave = 0
	} // isset session_uid
?>

<div id='conteudo' class='conteudo'>
	<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
		<?php
			carregaJS();
			tituloPagina('nova senha');
			EnviandoImg();
		?>
		<div id='resposta' class='retorno' style='min-width:100%;max-width:100%;margin:0 auto;margin-bottom:1.2%;'>
			<p id='respostap' class='retorno'>
				Agora sim! Digite sua nova senha:
			</p>
		</div> <!-- resposta -->

		<div id='id03'>
			<div class='container'>
				<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block:'>

					<div style='min-width:100%;max-width:100%;display:inline-block;padding:8% 13%;padding-top:3%;'>
						<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
							  <input style='max-width:100%;min-width:100%;' type='password' placeholder='Nova senha' name='nova' id='nova'>
						</div>
						<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
							  <input style='max-width:100%;min-width:100%;' type='password' placeholder='Confirme a nova senha' name='confirme' id='confirme'>
						</div>
					       <?php MontaBotao('alterar senha','enviarnovasenha'); ?>
					</div>

				</div>
			</div> <!-- container -->
		</div><!--id03-->
	</div> <!-- content -->

</div> <!-- conteudo -->

<script>
	$(document).ready(function() {
		enviandoimg = $('#enviando');
		headerimgrecup = $('#recuperar-top');
		novacontainer = $('.content');
		criarsenha = $('#enviarnovasenha');
		novaresposta = $('#respostap');
		idport = $('#id03');

		function NovaSenha() {
			novasenha = $('#nova').val();
			novaconfirme = $('#confirme').val();

			$.ajax({
				type: 'POST',
				dataType: 'html',
				async: true,
				url: '<?php echo $dominio ?>/entrar/recuperar-senha/includes/novasenha.inc.php',
				data: {
					submitnova: 1,
					nsenha: novasenha,
					nconfirme: novaconfirme
				},
				beforeSend:function() {
					enviandoimg.css('display', 'block');
					window.scrollTo(0, 0);
					novacontainer.css('display', 'none');
				},
				success: function(criandosenha) {
	                                window.scrollTo(0,0);
					enviandoimg.css('display', 'none');
					novacontainer.css('display', 'block');

					if (criandosenha.includes('criando') == true) {
						novaresposta.html('Senha recuperada com sucesso!');
						setTimeout(function() {
							window.location.href='<?php echo $dominio ?>/entrar/';
						}, 5000);
					} else {
						novaresposta.html(criandosenha);
					}
				}
			});
		}
		criarsenha.click(function() {
			NovaSenha();
		});
	}); /* document ready */
</script>

<?php
	include_once __DIR__.'/../../../rodape.php';
?>
