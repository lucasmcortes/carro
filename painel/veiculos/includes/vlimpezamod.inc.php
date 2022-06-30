<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido
if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$limpeza = $_POST['modificacao'];

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ( ($veiculo['limpeza']!=$limpeza) && ($limpeza!=0) ) {
			$vlimpezamod = new setRow();
			$vlimpezamod = $vlimpezamod->Limpeza($uid,$vid,$limpeza,$data);

			if ($vlimpezamod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // vlimpezamod true
		} else {
			$mod = 0;
		} // limpeza diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
