<?php
	require_once __DIR__.'/../../cabecalho.php';

	if (isset($_SESSION['l_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['l_email']);
		if ($adminivel['nivel']==0) {
			redirectToLogin('entrar/logout');
		} // nivel 0
		
	        $permissao = new Conforto($uid);
	        $permissao = $permissao->Permissao('modificacao');
	        if ($permissao!==true) {
	                redirectToLogin();
	        } // permitido

		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->AdminCategoria($adminivel['nivel']);

	} else {
		redirectToLogin();
	} // isset uid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo' style='margin-bottom:13%;'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
				<?php
					if ($adminivel['nivel']==3) {
						include_once __DIR__.'/configuracoes.inc.php';
					} // admin nivel 3
				?>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
