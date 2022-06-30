<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_POST['modificacao'])) {
	$km = str_replace(',','.',$_POST['modificacao'])?:$configuracoes['rev_car_apos'];
	$pwd = $_POST['senha']?:0;

	$encontraadmin = new ConsultaDatabase($uid);
	$encontraadmin = $encontraadmin->AdminInfo($uid);
	if ($encontraadmin!=0) {
		if ($encontraadmin['nivel']==3) {
			// $authadmin = new ConsultaDatabase($uid);
			// $authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);
			$authadmin = 1;
			if ($authadmin==0) {
				$mod = 0;
			} else {
				$addconfig = new setRow();
				$addconfig = $addconfig->KMRevisaoAposLimiar($uid,$km,$data);
				if ($addconfig===true) {
					$mod = 'sucesso';
				} else {
					$mod = 0;
				} // addconfig true
			} // authadmin
		} else {
			$mod = 0;
		} // nivel
	} else {
		$mod = 0;
	} // encontrado
} else {
	$mod = 0;
}// $_post

echo $mod;
