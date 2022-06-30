<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido
if (isset($_POST['modificacao'])) {
		$vid = $_POST['veiculo'];
		$observacao = $_POST['modificacao'];

		$veiculo = new ConsultaDatabase($uid);
		$veiculo = $veiculo->Veiculo($vid);

		if ($veiculo['observacao']!=$observacao) {
			$vobservacaomod = new setRow();
			$vobservacaomod = $vobservacaomod->Vobs($uid,$vid,$veiculo['portas'],$veiculo['completo'],$veiculo['caracterizado'],$veiculo['revisao'],$observacao,$data);

			if ($vobservacaomod===true) {
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
