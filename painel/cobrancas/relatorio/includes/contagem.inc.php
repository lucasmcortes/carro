<?php
	require_once __DIR__.'/../../../../includes/setup.inc.php';

	if (isset($_SESSION['l_id'])) {
		$mesinicial = $_REQUEST['mes'];
		$anoinicial = $_REQUEST['ano'];

		$inicio_relatorio_string = $anoinicial.'-'.$mesinicial.'-01 00:00:00.000000';
		$inicio_relatorio = new DateTime($inicio_relatorio_string);
		$conclusao_relatorio = $inicio_relatorio->modify('+1 month');
		$conclusao_relatorio = new DateTime($conclusao_relatorio->format('Y-m-d H:i:s.u'));
		$conclusao_relatorio_string = $conclusao_relatorio->format('Y-m-d H:i:s.u');
		$inicio_relatorio->modify('-1 month');

		$listacobrancas = new ConsultaDatabase($uid);
		$listacobrancas = $listacobrancas->ListaCobrancasEpoca($inicio_relatorio_string,$conclusao_relatorio_string);

		(count($listacobrancas)==1) ? $contagem = 0 : $contagem = count($listacobrancas);
		echo $contagem;

	} else {
		redirectToLogin();
	} // isset uid
?>
