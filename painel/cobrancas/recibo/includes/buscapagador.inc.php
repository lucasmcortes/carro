<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

$resultado = [];
$nomes = [];
$enderecos = [];

if (isset($_POST['pagador'])) {

	$termo = '%'.$_POST['pagador'].'%';
	$pagadores = new ConsultaDatabase($uid);
	$pagadores = $pagadores->BuscaPagador($termo);
	foreach($pagadores as $locatario) {
		$resultado[] = array(
			'nome'=>$locatario['nome'],
			'endereco'=>$locatario['rua'].', '.$locatario['numero'].', '.$locatario['bairro'].' - '.$locatario['cidade'].' - '.$locatario['estado']
		);
	} // foreach
} // isset

header('Content-Type: application/json;');
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
