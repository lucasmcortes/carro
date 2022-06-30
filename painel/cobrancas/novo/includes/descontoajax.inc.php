<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['desconto'])) {

	$valor = $_POST['valor'];
	$coid = $_POST['coid'];
	$cobranca = new ConsultaDatabase($uid);
	$cobranca = $cobranca->Cobranca($coid);
	$somaparciais = new Conforto($uid);
	$somaparciais = $somaparciais->SomaParciais($coid);
	$pagamentosaluguel = new Conforto($uid);
	$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($cobranca['aid']);

	$valor = $valor-$somaparciais-$pagamentosaluguel;

	$desconto = str_replace(array('%'),'',$_POST['desconto']);
	if (empty($desconto)) {
		$desconto = 0;
		$resultado = $valor;
	} else {
		$resultado = $valor - ($valor * ($desconto/100));
	} // desconto

	($resultado<0) ? $resultado = 0 : $resultado = $resultado;

} else {
	$resultado = 0;
} // isset post submit

if ($resultado==$valor) {
	echo '';
} else {
	echo 'Valor com desconto: '.Dinheiro($resultado);
}

?>
