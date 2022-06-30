<?php

	$uid = $_SESSION['l_id'];

	if (isset($_SESSION['l_id'])) {
		//registra logout
		$stmt = $conn->prepare("INSERT INTO logout (user_id, quando, session_id) VALUES(?, ?, ?);");
		$stmt->bind_param("iss",$uid,$data,$sid);
		$stmt->execute();
		$stmt->close();

		unset($_SESSION['l_id']);
		session_unset();
		session_regenerate_id();
		session_destroy();
	} else { echo ''; }

?>
