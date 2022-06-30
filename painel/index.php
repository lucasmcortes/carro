<?php
	require_once __DIR__.'/../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);
		if ($adminivel['nivel']==0) {
			redirectToLogin('entrar/logout');
		} // nivel 0
		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->CadastroCategoria($adminivel['nivel']);

	} else {
		redirectToLogin();
	} // isset uid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>

				<?php
					tituloPagina('painel');
					
					include_once __DIR__.'/../botoespainellinear.inc.php';

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";

						$listaveiculos = new ConsultaDatabase($uid);
						$listaveiculos = $listaveiculos->ListaVeiculos();
						if ($listaveiculos[0]['vid']!=0) {
							foreach ($listaveiculos as $veiculo) {
								if ($veiculo['ativo']=='S') {
									echo "
										<div id='card_v_".$veiculo['vid']."' class='cardslot sombraabaixo'></div>
										<script>
											$(document).ready(function() {
												atualizaCard(".$veiculo['vid'].");
											});
										</script>
									";
								} // ativo
							} // foreach listaveiculos
						} else {
							VamosComecar();
						}// vid > 0

						//////////// COBRANÇAS
						$recebiveis = new Cards($uid);
						$recebiveis = $recebiveis->RecebiveisPainel();
						if ($recebiveis['quantidade']>0) {
							tituloPagina('recebíveis');

							echo $recebiveis['recentes'];

							echo "<div style='min-width:100%;max-width:100%;display:inline-block;margin-bottom:1.8%;border:1px solid var(--cinza);border-left:0;border-right:0;border-top:0;'>";
								BotaoPainel('ver mais','maiscobrancas','cobrancas/aberto');
							echo "</div>";
						} // recebiveis > 0

					echo "</div>";

				?>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../rodape.php';
?>
