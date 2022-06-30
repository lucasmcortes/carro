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

	if ( (isset($_GET['inicio'])) && (isset($_GET['devolucao'])) ) {
		$inicioformatado = explode('/',$_GET['inicio']);
		$inicioformatado = $inicioformatado[2].'-'.$inicioformatado[1].'-'.$inicioformatado[0];
		$devolucaoformatado = explode('/',$_GET['devolucao']);
		$devolucaoformatado = $devolucaoformatado[2].'-'.$devolucaoformatado[1].'-'.$devolucaoformatado[0];
	} else {
		redirectToLogin();
	}// get


?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina('disponibilidade');
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div id ='respostadisponibilidadepordata' style='min-width:81%;max-width:81%;display:inline-block;'>
						<script>
							$.ajax({
								type: 'POST',
								url: '<?php echo $dominio ?>/painel/alugueis/novo/includes/buscadatadisponivel.inc.php',
								data: {
									inicio: '<?php echo $inicioformatado ?>',
									devolucao: '<?php echo $devolucaoformatado ?>'
								},
								success: function(desejo) {
									$('#respostadisponibilidadepordata').html('');
									if (desejo['quantidade_de_veiculos']>0) {
										$('#respostadisponibilidadepordata').append('Veículos disponíveis para o período de <b><?php echo $_GET['inicio'] ?></b> até <b><?php echo $_GET['devolucao'] ?></b>:<br>');
										veiculos = desejo['veiculos'] == null ? [] : (desejo['veiculos'] instanceof Array ? desejo['veiculos'] : [desejo['veiculos']]);
										$.each(veiculos, function(index, veiculo) {
											$('#'+veiculo['vid']+'_wrap').append('<div id=\"card_v_'+veiculo['vid']+'\" class=\"cardslot\"></div>');
											$('#'+veiculo['vid']+'_wrap').append('<div id=\"sel_v_'+veiculo['vid']+'\" class=\"selecao\"><p class=\"selecao\">Escolher '+veiculo['modelo']+'</p></div>');
											atualizaCard(veiculo['vid']);
										});
									} else {
										$('#respostadisponibilidadepordata').html('Todos os veículos estão ocupados para o período de <b><?php echo $_GET['inicio'] ?></b> até <b><?php echo $_GET['devolucao'] ?></b>.<br>');
									}
								}
							});
							$('body').on('click', '.selecao',function() {
								window.location.href='<?php echo $dominio ?>/painel/alugueis/novo/?v='+$(this).attr('id').split('_')[2]+'&inicio=<?php echo $_GET['inicio'] ?>&devolucao=<?php echo $_GET['devolucao'] ?>';
							});
						</script>
					</div> <!-- respostadisponibilidadepordata -->
					<?php
						$veiculos = new ConsultaDatabase($uid);
						$veiculos = $veiculos->ListaVeiculos();
						if ($veiculos[0]['vid']!=0) {
							foreach ($veiculos as $veiculo) {
								echo "<div id='".$veiculo['vid']."_wrap'></div>";
							} // foreach
						} // vid != 0
					?>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
