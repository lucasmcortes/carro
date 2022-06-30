<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
		$lid = $_POST['locatario'];
		$email = $_POST['modificacao'];

		if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email, $email, PREG_UNMATCHED_AS_NULL)) {
			$email = $email[0];

			$locatario = new ConsultaDatabase($uid);
			$locatario = $locatario->LocatarioInfo($lid);

			if ($email!=$locatario['email']) {
				$modemail = new UpdateRow();
				$modemail = $modemail->UpdateLocatarioEmail($email,$lid);
				if ($modemail===true) {
					$email = 'sucesso';
				} else {
					$email = 0;
				} // modemail true
			} else {
				$email = 0;
			} // != email
		} else {
			$email = 0;
		} // regex email
} else {
	$email = 0;
}// $_post

echo $email;
