<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$caracterizado = $_POST['modificacao'];

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ( ($veiculo['caracterizado']!=$caracterizado) && ($caracterizado!=0) ) {

			$vcaracterizadomod = new setRow();
			$vcaracterizadomod = $vcaracterizadomod->Vobs($uid,$vid,$veiculo['portas'],$veiculo['completo'],$caracterizado,$veiculo['revisao'],$veiculo['observacao'],$data);

			if ($vcaracterizadomod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // vcaracterizadomod true
		} else {
			$mod = 0;
		} // caracterizado diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
