<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$kilometragem = str_replace(' ','',$_POST['modificacao']);

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ($veiculo['km']!=$kilometragem) {

			$vkilometragemmod = new setRow();
			$vkilometragemmod = $vkilometragemmod->Kilometragem($uid,$vid,$kilometragem,$data);

			if ($vkilometragemmod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // true
		} else {
			$mod = 0;
		} // diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
