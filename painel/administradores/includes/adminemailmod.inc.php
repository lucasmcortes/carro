<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['admin'])) {
		$admin = $_POST['admin'];
		$email = $_POST['modificacao'];

		if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email, $email, PREG_UNMATCHED_AS_NULL)) {
			$email = $email[0];

			$admininfo = new ConsultaDatabase($uid);
			$admininfo = $admininfo->AdminInfo($admin);

			if ($email!=$admininfo['email']) {
				$modemail = new UpdateRow();
				$modemail = $modemail->UpdateUserEmail($email,$admin);
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
