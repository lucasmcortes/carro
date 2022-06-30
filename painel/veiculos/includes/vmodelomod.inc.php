<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$modelo = $_POST['modificacao'];

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ($veiculo['modelo']!=$modelo) {

			$vmodelomod = new UpdateRow();
			$vmodelomod = $vmodelomod->UpdateVeiculoModelo($modelo,$vid);

			if ($vmodelomod===true) {
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
