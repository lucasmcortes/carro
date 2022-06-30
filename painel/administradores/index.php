<?php
	require_once __DIR__.'/../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);
		if ($adminivel['nivel']!=3) {
			redirectToLogin();
		} // nivel != 3

		$permissao = new Conforto($uid);
		$permissao = $permissao->Permissao('modificacao');
		if ($permissao!==true) {
			redirectToLogin();
		} // permitido

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
					tituloPagina('administradores');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('addadmin','adicionar administrador','addadminsicon');
						Icone('verconfig','configurações','configuracoesicon');
					echo "</div>";
					echo "
						<script>
							$('#addadmin').on('click',function () {
								window.location.href='".$dominio."/painel/administradores/novo';
							});
							$('#verconfig').on('click',function () {
								window.location.href='".$dominio."/painel/configuracoes';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
                                        <div class='items'>
                                                <div id='adminswrap' style='min-width:100%;max-width:100%;margin:1.2% auto;'>
                                                        <label>Administradores</label>
                                                        <div id='admininner' style='min-width:99%;max-width:99%;display:inline-block;'>
                                                                <select id='admins'>
                                                                        <option value='0'>-- ESCOLHA --</option>
                                                                <?php
                                                                        foreach ($listaadmin as $admin) {
                                                                                echo "<option value='".$admin['email']."'>".$admin['nome']."</option>";
                                                                        } // foreach
                                                                ?>
                                                                </select>
                                                        </div>
                                                </div>

                                                <div id='admininfowrap' style='min-width:100%;max-width:100%;margin-bottom:5%;display:none;'>

                                                        <div id='nivelwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                                                <label>Nível de acesso</label>

								<div class='inputouterwrap'>
									<div class='inputwrap'>
										<div class='preinput normal'></div>
										<div id='niveis' class='wrappedinput lista'>
											<p id='n_1' class='niveis opcoes'>
												Leitura
											</p>
											<p id='n_2' class='niveis opcoes'>
												Registro
											</p>
											<p id='n_3' class='niveis opcoes'>
												Modificação
											</p>
										</div>
										<div class='posinput'></div>
									</div>
								</div>
                                                        </div> <!-- nivelwrap -->

                                                        <div id='cpfwrap' style='min-width:100%;max-width:100%;display:inline-block;margin:3px auto;'>
                                                                <label>CPF</label>
								<div class='inputouterwrap'>
									<div class='inputwrap'>
										<div class='preinput normal'></div>
										<input class='wrappedinput' type='text' placeholder='Número do CPF' onkeyup='maskIt(this,event,"###.###.###-##")' name='cpf' id='cpf'></input>
										<div class='posinput'></div>
									</div>
								</div>
                                                        </div> <!-- cpfwrap -->

                                                        <div id='telefonewrap' style='min-width:100%;max-width:100%;display:inline-block;margin:3px auto;'>
                                                                <label>Telefone</label>
								<div class='inputouterwrap'>
									<div class='inputwrap'>
										<div class='preinput normal'></div>
										<input class='wrappedinput' type='text' placeholder='Telefone' onkeyup='maskIt(this,event,"(##) #####-####")' name='telefone' id='telefone'></input>
										<div class='posinput'></div>
									</div>
								</div>
                                                        </div> <!-- telefonewrap -->

                                                        <div id='emailwrap' style='min-width:100%;max-width:100%;display:inline-block;margin:3px auto;'>
                                                                <label>Email</label>
								<div class='inputouterwrap'>
									<div class='inputwrap'>
										<div class='preinput normal'></div>
										<input class='wrappedinput' type='email' placeholder='Email' name='email' id='email'></input>
										<div class='posinput'></div>
									</div>
								</div>
                                                        </div> <!-- emailwrap -->

                                                </div> <!-- admininfowrap -->

                                                <script>
                                                        $('#admins').on('change', function () {
								admin = $('#admins').val();
								if (admin!=0) {
									$('#adicionarwrap').css('display','none');
									$('#admininfowrap').css('display','inline-block');
									$('#admininfowrap').find(':input').prop('disabled', false);
									$('#admininfowrap').find(':input').css('background-color', 'var(--branco)');
									$('#admininfowrap').find(':input').css('color', 'var(--preto)');
									$('#admininfowrap').find(':input').css('cursor', 'auto');
									$('.opcoes').removeClass('selecionada');
									$.ajax({
										type: 'POST',
										dataType: 'html',
										async: true,
										url: '<?php echo $dominio ?>/painel/administradores/includes/adminnivel.inc.php',
										data: {
											adminemail: admin
										},
										success: function(adminivel) {
											admininfo = JSON.parse(adminivel);
											$('.opcoes[id=n_'+admininfo['nivel']+']').addClass('selecionada');

											$('#cpf').val(admininfo['cpf']);
											$('#telefone').val(admininfo['telefone']);
											$('#email').val(admininfo['email']);
										} /* success */
									}); /* ajax */
								} else {
									$('#adicionarwrap').css('display','inline-block');
									$('#admininfowrap').css('display','none');
									$('#admininfowrap').find(':input').prop('disabled', 'disabled');
									$('#admininfowrap').find(':input').css('background-color', 'var(--branco)');
									$('#admininfowrap').find(':input').css('color', 'var(--preto)');
									$('#admininfowrap').find(':input').css('cursor', 'auto');
								} /* admin != 0 */
							}); /* change select admin */

							velemento = 0;
							$('.opcoes').on('click', function() {
								velemento = $(this).attr('id').split('_')[1];
								listadeopcoes = $(this).parent().attr('id');
								if ($(this).hasClass('selecionada')) {
									$('#'+listadeopcoes).children('.opcoes').removeClass('selecionada');
									velemento = 0;
									return;
								}
								$('#'+listadeopcoes).children('.opcoes').removeClass('selecionada');
								$(this).addClass('selecionada');
							});

							$('.posinput').on('click', function() {
								elemento = $(this).siblings('.wrappedinput').attr('id');
								if ($('#'+elemento).hasClass('lista') || $('#'+elemento).hasClass('swap')) {
									velemento = velemento;
								} else {
									velemento = $('#'+elemento).val();
								}
								$.ajax({
									type: 'POST',
									url: '<?php echo $dominio ?>/painel/administradores/includes/admin'+elemento+'mod.inc.php',
									data: {
										admin: admininfo['uid'],
										modificacao: velemento
									},
									beforeSend: function() {
										$('#'+elemento).siblings('.preinput').removeClass('modificado');
										$('#'+elemento).siblings('.preinput').addClass('normal');
									},
									success: function(modconfig) {
										$('#'+elemento).siblings('.preinput').html('');
										if (modconfig.includes('sucesso')) {
											if ($('#'+elemento).siblings('.preinput').hasClass('bgrosa')) {
												$('#'+elemento).siblings('.preinput').removeClass('bgrosa');
											}
											$('#'+elemento).css('border', '0');
											$('#'+elemento).closest('.inputouterwrap').css('background-color', 'var(--verdedois)');
											$('#'+elemento).closest('.inputouterwrap').find('*').prop('disabled', 'disabled');
											$('#'+elemento).closest('.inputouterwrap *').css('cursor','not-allowed');
											$('#'+elemento).closest('.inputouterwrap *').css('pointer-events','none');
											$('#'+elemento).siblings('.preinput').removeClass('normal');
											$('#'+elemento).siblings('.preinput').addClass('modificado');
											mostraFooter();

										} else {
											$('#'+elemento).siblings('.preinput').addClass('bgrosa');
											$('#bannerfooter').css('display','none');
										}
									}
								});
							});

							$('#nivelenviar').on('click', function () {
								$.ajax({
									type: 'POST',
									dataType: 'html',
									async: true,
									url: '<?php echo $dominio ?>/painel/administradores/includes/adminnivelmod.inc.php',
									data: {
										novonivel: valnivel,
										admin: admininfo['uid']
									},
									success: function(modnivel) {
										if (modnivel.includes('sucesso')) {
											$('#nivel').prop('disabled', 'disabled');
											$('#nivel').css('cursor', 'not-allowed');
											$('#nivel').css('background-color', 'var(--verde)');
											$('#nivel').css('color', 'var(--preto)');
											$('#nivel').css('border', '1px solid var(--preto)');
											$('#nivelinner').css('min-width', '100%');
											$('#nivelinner').css('max-width', '100%');
											$('#nivelenviar').css('display','none');
										} else {
											$('#nivel').css('border', '1px solid var(--rosa)');
											$('#nivel').css('background-color', 'var(--branco)');
											$('#nivel').css('color', 'var(--preto)');
										}
									}
								}); /* ajax */
							}); /* nivelenviar */

                                                </script>
                                        </div>
                                </div>

	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
