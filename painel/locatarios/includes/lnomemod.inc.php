<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
		$lid = $_POST['locatario'];

		$locatario = new ConsultaDatabase($uid);
		$locatario = $locatario->LocatarioInfo($lid);

		$nome = $_POST['modificacao']?:$locatario['nome'];

		if ($nome!=$locatario['nome']) {
			$modnome = new UpdateRow();
			$modnome = $modnome->UpdateLocatarioNome($nome,$lid);
			if ($modnome===true) {
				$nome = 'sucesso';
			} else {
				$nome = 0;
			} // modnome true
		} else {
			$nome = 0;
		} // != nome
} else {
	$nome = 0;
}// $_post

echo $nome;
