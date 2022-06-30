<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['admin'])) {
		$admin = $_POST['admin'];
		$nivel = $_POST['modificacao'];
		if ($nivel!=56) {
			$admininfo = new ConsultaDatabase($uid);
			$admininfo = $admininfo->AdminInfo($admin);

			if ($nivel!=$admininfo['nivel']) {
				$modnivel = new setRow();
				$modnivel = $modnivel->AdminNivel($admin,$nivel,$data);
				if ($modnivel===true) {
					$nivel = 'sucesso';
				} else {
					$nivel = 0;
				} // modnivel true
			} else {
				$nivel = 0;
			} // != nivel
		} else {
			$nivel = 0;
		} // != 56
} else {
	$nivel = 0;
}// $_post

echo $nivel;
