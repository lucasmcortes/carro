<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['parcial'])) {

	$parcial = str_replace(',','.',$_POST['parcial']);
	$coid = $_POST['coid'];

	if (empty($parcial)) {
		$parcial = 0;
		$resultado = $parcial;
	} // parcial

	$cobranca = new ConsultaDatabase($uid);
	$cobranca = $cobranca->Cobranca($coid);
	$somaparciais = new Conforto($uid);
	$somaparciais = $somaparciais->SomaParciais($coid);
	$pagamentosaluguel = new Conforto($uid);
	$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($cobranca['aid']);

	$resultado = $cobranca['valor']-($pagamentosaluguel+$parcial+$somaparciais);

} else {
	$resultado = 0;
} // isset post submit

if ($resultado<0) {
	echo 'Valor ultrapassou o total em '.str_replace('-','',Dinheiro($resultado));
} else {
	echo Dinheiro($resultado).' restantes';
}

?>
