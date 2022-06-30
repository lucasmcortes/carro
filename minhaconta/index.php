<?php
	include_once __DIR__.'/../includes/setup.inc.php';

	if (!isset($_SESSION['l_id'])) {
	        redirectToLogin('');
	} else {
		require_once __DIR__.'/../cabecalho.php';

		$contaveiculos = new ConsultaDatabase($uid);
		$contaveiculos = $contaveiculos->ListaVeiculos();
		(count($contaveiculos)==1) ? $veiculoscadastrados = 0 : $veiculoscadastrados = count($contaveiculos);

		$contadevolucoes = new ConsultaDatabase($uid);
		$contadevolucoes = $contadevolucoes->ListaDevolucoes();
		(count($contadevolucoes)==1) ? $devolucoescadastradas = 0 : $devolucoescadastradas = count($contadevolucoes);

		$cartao = new ConsultaDatabase($uid);
		$cartao = $cartao->UserCartao($uid);

		$licenca = new ConsultaDatabase($uid);
		$licenca = $licenca->LicencaUsuario($uid);
		if ($licenca['data']!=0) {
			$datalicenca = new DateTime($licenca['data']);
		} // licenca data
		$validadelicenca = new Conforto($uid);
		$validadelicenca = $validadelicenca->RenovacaoLicenca($uid);

	} // isset $uid
?>

	<div class="conteudo">
		<?php tituloPagina('minha conta'); ?>

		<div class='contaouterwrap'>
			<div class='containnerwrap'>

				<div class='contalinha'>
					<div><p><?php echo $_SESSION['l_nome']; ?></p></div>
					<div><p class='right'><?php echo $_SESSION['l_email']; ?></p></div>
				</div>

				<div class='contalinha'>
					<div><p>Cliente desde:</p></div>
					<div><p class='right'><?php echo $_SESSION['l_cadastroformat']; ?></p></div>
				</div>

				<div class='contalinha'>
					<div><p>Licença:</p></div>
					<?php
						if ($licenca['data']!=0) {
							if ($licenca['status']=='OK') {
								if ($licenca['modalidade']=='Anual') {
									echo "<div style='display:flex;flex-direction:column;'>";
										echo "<div>Válida até ".$validadelicenca."</div>";
										echo "<p class='cancelarbutton'>cancelar</p>";
									echo "</div>";
								} else if ($licenca['modalidade']=='Vitalicia') {
									echo "
										<div style='display:flex;justify-content:space-evenly;'>
											<p style='margin:0;'>
												Vitalícia desde ".$datalicenca->format('d/m/y')."
											</p>
											<span class='vitalicia'>✓</span>
										</div>
									";
								} // modalidade
							} else {
								// licença status != ok
								$boleto = new Conforto($uid);
								$boleto = $boleto->BoletoHabil($uid);
							} // status
						} else {
							// sem licença['data']
							$boleto = new Conforto($uid);
							$boleto = $boleto->BoletoHabil($uid);
						} // data
					?>
				</div>

				<?php
					if ($cartao['cardid']==0) {
						// echo "
						// 	<div class='contalinha'>
						// 		<div><p>Dados do cartão:</p></div>
						// ";
						// echo "<div>";MontaBotao('adicionar','cartaostatusbtn');echo"</div>";
						// echo "
						// 	</div>
						// ";
					} else {
						echo "
							<div class='contalinha'>
								<div><p>Dados do cartão:</p></div>
						";
						echo "<div>";MontaBotao('ver cartão','cartaostatusbtn');echo"</div>";
						echo "
							</div>
						";
					}
				?>

				<div class='contalinha'>
					<div><p>Veículos cadastrados:</p></div>
					<div><p class='right'><?php echo $veiculoscadastrados ?></p></div>
				</div>

				<div class='contalinha'>
					<div><p>Aluguéis concluídos:</p></div>
					<div><p class='right'><?php echo $devolucoescadastradas ?></p></div>
				</div>

				<div class='contalinha'>
					<div><?php MontaBotao('configurações','configbtn'); ?></div>
					<div><?php MontaBotao('relatório geral','relgerbtn'); ?></div>
				</div>

				<div class='contalinha'>
					<div><?php MontaBotaoSecundario('encerrar sessão','descbtn'); ?></div>
				</div>

			</div>
		</div>

	</div> <!-- conteudo -->

	<script>
		<?php
			if ($cartao['cardid']==0) {
				echo "
					$('#cartaostatusbtn').on('click',function() {
						loadFundamental('".$dominio."/minhaconta/cartao/adicionar');
					});
				";
			} else {
				echo "
					$('#cartaostatusbtn').on('click',function() {
						loadFundamental('".$dominio."/minhaconta/cartao/ver');
					});
				";
			} // se tem cartao
		?>
		$('#comprarplano').on('click',function() {
			loadFundamental('<?php echo $dominio ?>/minhaconta/plano');
		});
		$('.cancelarbutton').on('click',function() {
			loadFundamental('<?php echo $dominio ?>/minhaconta/plano/cancelarplanopopup.inc.php');
		});
		$('#configbtn').on('click',function() {
			window.location.href='<?php echo $dominio ?>/painel/configuracoes';
		});
		$('#relgerbtn').on('click',function() {
			window.location.href='<?php echo $dominio ?>/painel/veiculos/relatorio/geral';
		});
		$('#descbtn').on('click',function() {
			window.location.href='<?php echo $dominio ?>/entrar/logout';
		});
	</script>


<?php
	require_once __DIR__.'/../rodape.php';
?>
