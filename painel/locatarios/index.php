<?php
	require_once __DIR__.'/../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);
		if ($adminivel['nivel']!=3) {
			redirectToLogin();
		} // nivel != 3

		$permissao = new Conforto($uid);
	        $permissao = $permissao->Permissao('registro');
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
					tituloPagina('locatários');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('addlocatario','adicionar locatário','addlocatarioicon');
					Icone('addaluguel','adicionar aluguel','addaluguelicon');
					echo "</div>";
					echo "
						<script>
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#addlocatario').on('click',function () {
								window.location.href='".$dominio."/painel/locatarios/novo';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
						<?php
							$locatarios = [];
							$listalocatarios = new ConsultaDatabase($uid);
							$listalocatarios = $listalocatarios->ListaLocatarios();
							if ($listalocatarios[0]['lid']!=0) {
								foreach ($listalocatarios as $locatario) {
									$locatarios[] = $locatario;
								}
							} // lid > 0

							$filtro = new Conforto($uid);
							$filtro = $filtro->Exibicao($locatarios);
							echo $filtro['botoes'];

							echo "<div style='min-width:90%;max-width:90%;display:inline-block;'>";
							require_once __DIR__.'/includes/achalocatario.inc.php';
							echo "</div>";

							if ($filtro['i']>0) {
								echo "
									<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
									<!-- container -->
							                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
								";

											$paginas = new Conforto($uid);
											$paginas = $paginas->Paginacao($filtro['itens']);
											foreach ($paginas['itens'] as $locatarios) {
												$locatario = new ConsultaDatabase($uid);
												$locatario = $locatario->LocatarioInfo($locatarios['lid']);

												$telefone = new Conforto($uid);
												$telefone = $telefone->FormatoTelefone($locatario['telefone'],'br');

												($locatario['associado']=='S') ? $associado = 'Desde '.strftime('%d de %B de %Y', strtotime($locatario['data_associado'])) : $associado = 'Não';
												echo "
													<div id='locatarioswrap_".$locatario['lid']."' class='relatoriowrap'>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Nome:</b></p>
																<p class='infoslotrelatorio'>".$locatario['nome']."</p>
																<p class='headerslotrelatorio'><b>Telefone:</b></p>
																<p class='infoslotrelatorio'>".$telefone."</p>
															</div>
														</div>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>CPF:</b></p>
																<p class='infoslotrelatorio'>".$locatario['documento']."</p>
																<p class='headerslotrelatorio'><b>CNH:</b></p>
																<p class='infoslotrelatorio'>".$locatario['cnh']."</p>
															</div>
														</div>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Email:</b></p>
																<p class='infoslotrelatorio'>".$locatario['email']."</p>
																<p class='headerslotrelatorio'><b>Associado:</b></p>
																<p class='infoslotrelatorio'>".$associado."</p>
															</div>
														</div>
													</div>
												";
											} // foreach veiculos
								echo "
									</div>
									<!-- container -->
									".$paginas['botoes']."
								";
							} else {
								NenhumRegistro();
							}// i > 0
						?>

						<script>
							$('.relatoriowrap').on('click', function() {
								lid = $(this).attr('id').split('_')[1];
								locatarioFundamental(lid);
							});
						</script>
					</div>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
