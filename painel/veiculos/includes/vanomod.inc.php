<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$ano = $_POST['modificacao'];

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ($veiculo['ano']!=$ano) {

			$vanomod = new UpdateRow();
			$vanomod = $vanomod->UpdateVeiculoAno($ano,$vid);

			if ($vanomod===true) {
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
