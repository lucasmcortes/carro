<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
		$lid = $_POST['locatario'];
		$validade = $_POST['modificacao'];

		$locatario = new ConsultaDatabase($uid);
		$locatario = $locatario->LocatarioInfo($lid);

		if ($validade!=$locatario['validade']) {
			$modvalidade = new UpdateRow();
			$modvalidade = $modvalidade->UpdateValidade($validade,$locatario['cnh']);
			if ($modvalidade===true) {
				$validade = 'sucesso';
			} else {
				$validade = 0;
			} // modvalidade true
		} else {
			$validade = 0;
		} // != validade
} else {
	$validade = 0;
}// $_post

echo $validade;
