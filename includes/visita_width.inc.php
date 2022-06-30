<?php
	include_once __DIR__.'/setup.inc.php';

	if (isset($_POST['visWidth'])) {
		if (isset($visita_link)) {
			//$vwidth = mysqli_real_escape_string($conn, $_POST['visWidth']); // largura da tela

			// $sql_visita = "
			// 	SELECT * FROM visitas
			// 	WHERE visita_rmt_addr='".$_SERVER['REMOTE_ADDR']."'
			// 	ORDER BY visita_id
			// 	DESC
			// 	LIMIT 1
			// ";
			// $result_visita = mysqli_query($conn, $sql_visita);
			// $resultCheck_visita = mysqli_num_rows($result_visita);
			// if ($resultCheck_visita < 1) {
			// 	//
			// } else {
			// 	if ($row_visita = mysqli_fetch_assoc($result_visita)) {
			// 		$result_visita->data_seek(0);
			// 		while($row_visita = mysqli_fetch_array($result_visita)) {
			// 			$visita_width_id = $row_visita['visita_id'];
			// 			$vwidth_query = "UPDATE visitas SET visita_width='".$vwidth."' WHERE visita_id='".$visita_width_id."' ";
			// 			mysqli_query($conn, $vwidth_query);
			// 		} // while
			// 	} // if
			// } // else
		} else {
			//
		} // sem cleared
	} else {
		//
	} // sem $_post
?>
