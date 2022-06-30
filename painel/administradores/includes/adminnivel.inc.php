<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['adminemail'])) {
		$email = $_POST['adminemail'];

		$listaadmin = new ConsultaDatabase($uid);
		$listaadmin = $listaadmin->EncontraAdmin($email);

		$nivel = $listaadmin;
} else {
	$nivel = 0;
}// $_post

header('Content-Type: application/json;');
echo json_encode($nivel, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
