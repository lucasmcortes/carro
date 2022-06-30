<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
	$vid = $_POST['veiculo'];
	$revisao = $_POST['modificacao'];

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);

	if ($veiculo['revisao']!=$revisao) {
		$vrevisaomod = new setRow();
		$vrevisaomod = $vrevisaomod->Vobs($uid,$vid,$veiculo['portas'],$veiculo['completo'],$veiculo['caracterizado'],$revisao,$veiculo['observacao'],$data);

		if ($vrevisaomod===true) {
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
