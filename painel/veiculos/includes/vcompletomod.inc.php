<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido
if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$completo = $_POST['modificacao'];

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ( ($veiculo['completo']!=$completo) && ($completo!=0) ) {
			$vcompletomod = new setRow();
			$vcompletomod = $vcompletomod->Vobs($uid,$vid,$veiculo['portas'],$completo,$veiculo['caracterizado'],$veiculo['revisao'],$veiculo['observacao'],$data);
			if ($vcompletomod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // vcaracterizadomod true
		} else {
			$mod = 0;
		} // completo diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
