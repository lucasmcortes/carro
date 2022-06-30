<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
		$lid = $_POST['locatario'];
		$cnh = $_POST['modificacao'];

		$locatario = new ConsultaDatabase($uid);
		$locatario = $locatario->LocatarioInfo($lid);

		if ($cnh!=$locatario['cnh']) {
			$modcnh = new UpdateRow();
			$modcnh = $modcnh->UpdateHabilitacao($cnh,$lid);
			if ($modcnh===true) {
				$cnh = 'sucesso';
			} else {
				$cnh = 0;
			} // modcnh true
		} else {
			$cnh = 0;
		} // != cnh
} else {
	$cnh = 0;
}// $_post

echo $cnh;
