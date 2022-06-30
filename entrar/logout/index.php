<?php

	require_once __DIR__.'/../../includes/setup.inc.php';

	if (isset($_SESSION['l_id'])) {

		unset($_SESSION);
		session_unset();
		session_regenerate_id();
		session_destroy();

		redirectToLogin();

	} else {
		redirectToLogin('/');
	}

?>
