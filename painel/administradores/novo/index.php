<?php

	include_once __DIR__.'/../../../includes/setup.inc.php';

	if (!isset($_SESSION['l_id'])) {
	        redirectToLogin();
	} else {
		$permissao = new Conforto($uid);
		$permissao = $permissao->Permissao('modificacao');
		if ($permissao!==true) {
			redirectToLogin();
		} // permitido

		require_once __DIR__.'/../../../cabecalho.php';
		echo '<div class="conteudo">';
			require_once __DIR__.'/includes/admin-slot.inc.php';
		echo '</div> <!-- conteudo -->';
		require_once __DIR__.'/../../../rodape.php';
	} // isset $uid

?>
