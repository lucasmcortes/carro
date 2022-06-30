<?php

include_once __DIR__.'/setup.inc.php';

if (isset($_POST['veiculo'])) {
	$vid = $_POST['veiculo'];

	$card = new Cards($uid);
	$card = $card->CardVeiculo($vid);

} else {
	echo ':((';
}// $_post

?>
