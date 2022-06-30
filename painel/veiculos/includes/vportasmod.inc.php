<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$portas = $_POST['modificacao'];

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ( ($veiculo['portas']!=$portas) && ($portas!=0) ) {
			$vportasmod = new setRow();
			$vportasmod = $vportasmod->Vobs($uid,$vid,$portas,$veiculo['completo'],$veiculo['caracterizado'],$veiculo['revisao'],$veiculo['observacao'],$data);

			if ($vportasmod===true) {
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
