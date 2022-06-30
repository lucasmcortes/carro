<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido
if (isset($_POST['modificacao'])) {
		$lid = $_POST['locatario'];
		$observacao = $_POST['modificacao'];

		$locatario = new ConsultaDatabase($uid);
		$locatario = $locatario->LocatarioInfo($lid);

		if ($locatario['observacao']!=$observacao) {
			$lobservacaomod = new setRow();
			$lobservacaomod = $lobservacaomod->Lobs($uid,$lid,$observacao,$data);

			if ($lobservacaomod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // vcaracterizadomod true
		} else {
			$mod = 0;
		} // observacao diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
