<?php
	include_once __DIR__.'/../cabecalho.php';
?>

	<div class="conteudo">

		<div style='min-width:90%;max-width:90%;margin:0 auto;'>

			<?php
	                        tituloPagina('contato');
	                        EnviandoImg();
	                ?>

		       <p class="retorno">
			       Conte o que você precisa para iniciarmos nosso contato
		       </p>

		       <div style='background-image:url(../img/0501221201.png);background-size:contain;background-repeat:no-repeat;background-position:center;'>
			       <div style='display:flex;flex-direction:column;align-items:center;'>
					<div id="id03">
			                    	<form id="form">
							<div id="container-cadastro" class="container-cadastro">
								<label>Nome</label>
								<input type="text" value="<?php echo $_SESSION['l_nome']??'' ?>" id="nome" placeholder="Seu nome completo" name="nome">

			                    			<div style="display:flex;gap:2%;">
									<div style="flex:1;">
										<label>E-mail</label>
					                        		<input type='text' value="<?php echo $_SESSION['l_email']??'' ?>" id="email" placeholder="Seu e-mail" name='email'>
									</div>
									<div style="flex:1;">
										<label>Telefone</label>
				                        			<input onkeyup="maskIt(this,event,'(##) ###-###-###')" type='text' value="<?php echo $_SESSION['l_telefone']??'' ?>" id="telefone" placeholder="(99) 999-999-999" name='telefone'>
				                    			</div>
								</div>

								<label>Mensagem</label>
			                    			<textarea style='margin-bottom:13px;' rows="5" id="mensagem" name="mensagem" placeholder="O que você precisa?"></textarea>

			                    			<?php MontaBotao('enviar','enviarcontato'); ?>

							</div> <!--container cadastro-->
			                  	</form>
			                </div> <!--id03-->
				</div>
			</div>
		</div>
	</div> <!-- conteudo -->

	<script>

		$(document).ready(function() {

			var enviandoimg = $('#enviando');
			var enviar = $('#enviarcontato');
			var cresposta = $('.retorno');
			var id02div = $('#id03');
			var ccontainer = $('#container-cadastro');
			var headerimg = $('#contato-top');

			function EnviarContato() {
				var cnome = $('#nome').val();
				var cemail = $('#email').val();
				var ctelefone = $('#telefone').val();
				var cmensagem = $('#mensagem').val();

				$.ajax({
					type: "POST",
					dataType: "html",
					async: true,
					url: "<?php echo $dominio ?>/contato/includes/contato.inc.php",
					data: {
						submitcontato: enviar.html(),
						connome: cnome,
						conemail: cemail,
						contelefone: ctelefone,
						conmensagem: cmensagem
					},
					beforeSend:function(){
						window.scrollTo(0, 0);
						id02div.css('display', 'none');
						cresposta.css('display', 'none');
						enviandoimg.css('display', 'block');
					},
					success: function(enviocontato) {
						if (enviocontato.includes("todos") == true) { /* preencher */
							enviandoimg.css('display', 'none');
							id02div.css('display', 'block');
							cresposta.empty();
							cresposta.css('display', 'inline-block');
							cresposta.html(enviocontato);
							window.scrollTo(0, 0);
						} else { /* enviado */
							enviandoimg.css('display', 'none');
							headerimg.css('display', 'none');
							ccontainer.css('display', 'none');
							cresposta.empty();
							cresposta.css('display', 'inline-block');
							cresposta.html(enviocontato);
							id02div.css('display', 'block');
							window.scrollTo(0, 0);
						}
					},
					error: function() {
						alert(':((');
					}
				});
			}

			enviar.click(function() {
				EnviarContato();
			});
		}); /* document ready */

	</script>

<?php
	include_once __DIR__.'/../rodape.php';
?>
