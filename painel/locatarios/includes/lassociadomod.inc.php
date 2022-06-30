<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];
	$associado = $_POST['associado'];

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

	if ($associado!=$locatario['associado']) {
		$modassociado = new setRow();
		$modassociado = $modassociado->Associado($uid,$lid,$associado,$data);
		if ($modassociado===true) {
			$associado = 'sucesso';
		} else {
			$associado = 0;
		} // modemail true
	} else {
		$associado = 0;
	} // != email
} else {
	$associado = 0;
}// $_post

echo $associado;
