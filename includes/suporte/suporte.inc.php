<?php

include_once __DIR__.'/../setup.inc.php';

if (isset($_POST['uid'])) {
	if (!empty($_POST['mensagem'])) {
		$usuario = new ConsultaDatabase($uid);
		$usuario = $usuario->AdminInfo($_POST['uid']);

		$_SESSION['suporte']['mensagem'] = $_POST['mensagem'];
		$_SESSION['suporte']['nome'] = $usuario['nome'];
		$_SESSION['suporte']['email'] = $usuario['email'];

		$cartinha = new Cartinha();
		$cartinha = $cartinha->enviarCartinha('suporte','lmattoscortes@gmail.com');

		unset($_SESSION['suporte']);
		unlinkTemp(__DIR__.'/img/');

		$resultado = "
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p class='respostaalteracao'>
					Mensagem enviada com sucesso
				</p>
			</div>
		";
	} else {
		$resultado = '<script>loadFundamental("'.$dominio.'/includes/suporte/suportepopup.inc.php");</script>';
	} // mensagem existe
} // $_post

echo $resultado;
?>
