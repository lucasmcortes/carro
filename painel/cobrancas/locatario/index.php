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
					tituloPagina('cobranças');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('vertodascobrancas','todas as faturas','verfaturaicon');
						Icone('veramaberto','recebíveis','addpagamentoicon');
					echo "</div>";
					echo "
						<script>
							$('#veramaberto').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas/aberto';
							});
							$('#vertodascobrancas').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas';
							});
						</script>
					";
				?>

				<p id='retorno' class='retorno' style='margin:1.8% auto;'>
			                Encontre o locatário pelo nome, CPF, CNH, telefone, email, placa ou cadastre um novo locatário
			        </p> <!-- retorno -->

				<div>
					<div id='locatariowrap' style='min-width:72%;max-width:72%;margin:3px auto;display:inline-block;'>
	                                        <label>Locatário</label>
	                                        <div id='locatarioinner' style='min-width:100%;max-width:100%;display:inline-block;'>
	                                                <input type='text' id='locatario' placeholder='Locatário'></input>
	                                        </div>
	                                </div> <!-- locatariowrap -->

	                                <div id='locatarioresult' style='min-width:100%;max-width:100%;display:inline-block;'>
	                                        <p id='locatarioresultp' style='display:none;min-width:100%;max-width:100%;text-align:left;padding:13px;'>
	                                        </p>
	                                </div>

	                                <script>
	                                        typingTimer = '';
	                                        doneTypingInterval = 555;
	                                        $('#locatario').on('keyup', function () {
	                                                clearTimeout(typingTimer);
	                                                typingTimer = setTimeout(doneTyping, doneTypingInterval);
	                                        });
	                                        $('#locatario').on('keydown', function () {
	                                                $('#retorno').empty();
	                                                clearTimeout(typingTimer);
	                                        });
	                                        function doneTyping () {
	                                                if ($('#locatario').val()!='') {
	                                                        BuscaCobrancasLocatario();
	                                                } else { /* query vazio */
	                                                        $('#retorno').html('Encontre o locatário pelo nome, CPF, CNH, telefone, email ou placa');
								$('#containertabela').empty();
	                                                        $('#locatarioresultp').empty();
	                                                        $('#locatarioresultp').css({
	                                                                'display':'none',
	                                                                'background-color':'transparent'
	                                                        });
	                                                }
	                                        }
	                                        function BuscaCobrancasLocatario() {
	                                                $.ajax({
	                                                        type: 'POST',
	                                                        url: '<?php echo $dominio ?>/painel/cobrancas/locatario/includes/buscacobrancalocatario.inc.php',
	                                                        data: { locatario: $('#locatario').val() },
					                        beforeSend: function() {
					                                $('#locatarioresultp').html('');
					                                $('#locatarioresultp').html("<div id='enviando' style='display:inline-block;'><div id='enviandospinner'></div></div>");
					                        },
	                                                        success: function(locatario) {
	                                                                $('#retorno').html(locatario['resposta']);

					                                $('#locatarioresultp').empty();
					                                $('#locatarioresultp').css('text-align','left');
	                                                                $('#locatarioresultp').css('display','inline-block');
	                                                                $('#locatarioresultp').css('background-color','transparent');

	                                                                if (!$('#locatarioresultp').html().includes('listando')) {
										$('#containertabela').html(locatario['tabela']);

	                                                                        $('#locatarioresultp').on('click',function() {
	                                                                                $('#locatarioresultp').html(locatario['resposta']);
	                                                                                $('#locatariowrap').css('display','none');
	                                                                                $(this).css('background-color','var(--verde)');
	                                                                                vallocatario = $('#locatarioresultspan').data('lid');
											console.log(locatario['cobrancas']);
	                                                                                $(this).on('click', function () {
	                                                                                        $('#locatarioresultp').css('background-color','transparent');
	                                                                                        $('#locatariowrap').css('display','inline-block');
	                                                                                        $('#retorno').empty();
	                                                                                        $('#locatarioresultp').empty();
	                                                                                });
	                                                                        });
	                                                                }
	                                                        }
	                                                });
	                                        }
	                                </script>

					<!-- container -->
					<div id='containertabela' style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;overflow:auto;'>
					</div>
					<!-- container -->

				</div>
			</div>
                </div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
