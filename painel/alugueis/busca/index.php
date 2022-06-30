<?php
	require_once __DIR__.'/../../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);

		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->AdminCategoria($adminivel['nivel']);

                $listaadmin = new ConsultaDatabase($uid);
                $listaadmin = $listaadmin->ListaAdmin();

	} else {
		redirectToLogin();
	} // isset uid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina('buscar aluguel');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('addaluguel','criar aluguel','addaluguelicon');
					Icone('alanteriores','aluguéis anteriores','alugueisanterioresicon');
					Icone('alatuais','aluguéis atuais','alugueisicon');
					Icone('verreservas','reservas','reservasicon');
					echo "</div>";
					echo "
						<script>
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#alatuais').on('click',function () {
								window.location.href='".$dominio."/painel/alugueis';
							});
							$('#alanteriores').on('click',function () {
								window.location.href='".$dominio."/painel/alugueis/anteriores';
							});
							$('#verreservas').on('click',function () {
								window.location.href='".$dominio."/painel/reservas/futuras';
							});
						</script>
					";
				?>
				<p id='retorno' class='retorno' style='margin:1.8% auto;'>
			                Encontre o aluguel pelo número de registro
			        </p> <!-- retorno -->

				<div>
					<div id='aluguelwrap' style='min-width: 72%;max-width: 72%;margin: 3px auto;display: inline-block;'>
		                                <div class='labelwrap'>
                                        		<label>Número de registro</label>
		                                        <span class='info infomaior' aria-label='Encontrado na parte superior das informações sobre os aluguéis. Ex.: 00000000-0000-0000-0000-000000000000'>i</span>
		                                </div>
	                                        <div id='aluguelinner' style='min-width:100%;max-width:100%;display:inline-block;'>
	                                                <input type='text' id='aluguel' placeholder='00000000-0000-0000-0000-000000000000'></input>
	                                        </div>
	                                </div> <!-- aluguelwrap -->

	                                <div id='aluguelresult' style='min-width:100%;max-width:100%;display:inline-block;'>
	                                        <p id='aluguelresultp' style='display:none;min-width:100%;max-width:100%;text-align:left;padding:13px;'>
	                                        </p>
	                                </div>

	                                <script>
	                                        typingTimer = '';
	                                        doneTypingInterval = 555;
	                                        $('#aluguel').on('keyup', function () {
	                                                clearTimeout(typingTimer);
	                                                typingTimer = setTimeout(doneTyping, doneTypingInterval);
	                                        });
	                                        $('#aluguel').on('keydown', function () {
	                                                $('#retorno').empty();
	                                                clearTimeout(typingTimer);
	                                        });
	                                        function doneTyping () {
	                                                if ($('#aluguel').val()!='') {
	                                                        BuscaAluguel();
	                                                } else { /* query vazio */
	                                                        $('#retorno').html('Encontre o aluguel pelo número de registro');
								$('#containertabela').empty();
	                                                        $('#aluguelresultp').empty();
	                                                        $('#aluguelresultp').css({
	                                                                'display':'none',
	                                                                'background-color':'transparent'
	                                                        });
	                                                }
	                                        }
	                                        function BuscaAluguel() {
	                                                $.ajax({
	                                                        type: 'POST',
	                                                        url: '<?php echo $dominio ?>/painel/alugueis/busca/includes/buscaaluguel.inc.php',
	                                                        data: { aluguel: $('#aluguel').val() },
					                        beforeSend: function() {
					                                $('#aluguelresultp').html('');
					                                $('#aluguelresultp').html("<div id='enviando' style='display:inline-block;'><div id='enviandospinner'></div></div>");
					                        },
	                                                        success: function(aluguel) {
	                                                                $('#retorno').html(aluguel['resposta']);

					                                $('#aluguelresultp').empty();
					                                $('#aluguelresultp').css('text-align','left');
	                                                                $('#aluguelresultp').css('display','inline-block');
	                                                                $('#aluguelresultp').css('background-color','transparent');

	                                                                if (!$('#aluguelresultp').html().includes('listando')) {
										$('#containertabela').html(aluguel['tabela']);
	                                                                }
	                                                        }
	                                                });
	                                        }
	                                </script>

					<!-- container -->
					<div id='containertabela' style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
					</div>
					<!-- container -->

				</div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
