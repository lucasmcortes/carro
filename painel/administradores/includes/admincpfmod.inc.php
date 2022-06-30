<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['admin'])) {
		$admin = $_POST['admin'];
		$cpf = $_POST['modificacao'];

		if (preg_match('/(\d{3}\.\d{3}\.\d{3}\-\d{2})?/', $cpf, $cpf, PREG_UNMATCHED_AS_NULL)) {
			$cpf = $cpf[0];

			$admininfo = new ConsultaDatabase($uid);
			$admininfo = $admininfo->AdminInfo($admin);

			if ($cpf!=$admininfo['cpf']) {
				$valida_documento = new ValidaCPFCNPJ($cpf);
				if ($valida_documento->valida() ) {
					$modcpf = new UpdateRow();
					$modcpf = $modcpf->UpdateUserCPF($cpf,$admin);
					if ($modcpf===true) {
						$cpf = 'sucesso';
					} else {
						$cpf = 0;
					} // modcpf true
				} else {
					$cpf = 0;
				}// valido
			} else {
				$cpf = 0;
			} // != cpf
		} else {
			$cpf = 0;
		} // regex cpf
} else {
	$cpf = 0;
}// $_post

echo $cpf;
