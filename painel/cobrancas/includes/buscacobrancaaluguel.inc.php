<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['aid'])) {
	$aid = $_POST['aid'];
	$coid = 0;

	$cobranca = new ConsultaDatabase($uid);
	$cobranca = $cobranca->CobrancaAluguel($aid);
	if ($cobranca['coid']!=0) {
		$coid = $cobranca['coid'];
	} else {
		$coid = 0;
	}
} else {
	$coid = 0;
}// $_post

echo $coid;

?>
