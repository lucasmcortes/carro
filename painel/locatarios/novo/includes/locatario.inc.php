<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitlocatario'])) {
	$cadastrando = '';

	$nome = mb_strtoupper($_POST['nome']);
	if (empty($nome)) {
		RespostaRetorno('nomelocatario');
		return;
	} // nome

	$associado = $_POST['associado'];
	if (empty($associado)) {
		RespostaRetorno('statusassociado');
		return;
	} // associado

	if (empty($_POST['placa'])) {
		if ($associado=='S') {
			RespostaRetorno('placaveiculo');
			return;
		} else {
			$placa = 0;
		} // exige placa pra associado
	} else {
		$placa = new Conforto($uid);
		$placa = $placa->FormatoPlaca($_POST['placa']);
		// if (preg_match('/([A-Z0-9]{3}\-[A-Z0-9]{4})?/', $placa, $placa, PREG_UNMATCHED_AS_NULL)) {
		// 	$placa = $placa[0];
		// } else {
		// 	if ($associado=='S') {
		// 		RespostaRetorno('placaveiculo');
		// 		return;
		// 	} // exige placa pra associado
		// } // regex placa
	} // placa

	$cpf = $_POST['cpf'];
	if (empty($cpf)) {
		RespostaRetorno('cpf');
		return;
	} else {
		if (preg_match('/^(\d{3}\.\d{3}\.\d{3}\-\d{2})?+$/', $cpf, $cpf, PREG_UNMATCHED_AS_NULL)) {
			$cpf = $cpf[0];
		} else {
			RespostaRetorno('cpf');
			return;
		} // regex cpf
	} // cpf

	$telefone = $_POST['telefone'];
	if (empty($telefone)) {
		RespostaRetorno('telefone');
		return;
	} else {
		if (preg_match('/(\(\d{2}\)[ ]{1}\d{5}\-\d{4})/', $telefone, $telefone, PREG_UNMATCHED_AS_NULL)) {
			$telefone = $telefone[0];
		} else {
			RespostaRetorno('telefone');
			return;
		} // regex telefone
	} // telefone

	$cnh = $_POST['cnh'];
	if (empty($cnh)) {
		RespostaRetorno('cnh');
		return;
	} // cnh
	// if (preg_match('/^(\d{9}\-\d{2})+$/', $cnh, $cnh, PREG_UNMATCHED_AS_NULL)) {
	// 	$cnh = $cnh[0];
	// 	$nome_img_cnh = str_replace('-','',$cnh);
	// } else {
	// 	$cnh = '';
	// } // regex cnh

	$validade = $_POST['validade'];
	if (empty($validade)) {
		RespostaRetorno('cnhvalidade');
		return;
	} else {
		if (preg_match('/^(\d{2}\/\d{2}\/\d{4})+$/', $validade, $validade, PREG_UNMATCHED_AS_NULL)) {
			$validade = $validade[0];
		} else {
			RespostaRetorno('cnhvalidade');
			return;
		} // regex validade
	} // validade

	$email = $_POST['email'];
	if (empty($email)) {
		RespostaRetorno('email');
		return;
	} else {
		if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email, $email, PREG_UNMATCHED_AS_NULL)) {
			$_SESSION['cadastro_email'] = $email = $email[0];
		} else {
			RespostaRetorno('email');
			return;
		} // regex email
	} // email

	$nascimento = $_POST['nascimento'];
	if (preg_match('/^(\d{2}\/\d{2}\/\d{4})+$/', $nascimento, $nascimento, PREG_UNMATCHED_AS_NULL)) {
		$_SESSION['cadastro_nascimento'] = $nascimento = $nascimento[0];
	} else {
		$nascimento = '';
	} // regex data de nascimento

	$cep = $_POST['cep'];
	if (preg_match('/^(\d{2}\.\d{3}\-\d{3})+$/', $cep, $cep, PREG_UNMATCHED_AS_NULL)) {
		$cep = $cep[0];
	} else {
		$cep = '';
	} // regex cep

	$rua = $_POST['rua']?:0;
	$numero = $_POST['numero']?:0;
	$bairro = $_POST['bairro']?:0;
	$cidade = $_POST['cidade']?:0;
	$estado = $_POST['estado']?:0;
	$complemento = $_POST['complemento']?:0;

	// $pwd = $_POST['pwd'];
	// if (empty($pwd)) {
	// 	RespostaRetorno('senha');
	// 	return;
	// } // pwd

	$remote_addr = $_SERVER['REMOTE_ADDR'];

	if (empty($nome) || empty($associado) || empty($cpf) || empty($telefone) || empty($email) || empty($cnh) || empty($validade) ) {
		RespostaRetorno('vazio');
		return;

	} else {
		// if (!isset($_SESSION['img_cnh_info_session'])) {
		// 	RespostaRetorno('cnhimagem');
		// 	return;
		// } // se tem imagem da cnh

		if (empty($placa)) {
			if ($associado=='S') {
				RespostaRetorno('placaassociado');
				return;
			} // se é associado
		} // isset placa

		$encontraadmin = new ConsultaDatabase($uid);
		$encontraadmin = $encontraadmin->AdminInfo($uid);
		if ($encontraadmin!=0) {
			if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
				// $authadmin = new ConsultaDatabase($uid);
				// $authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);
				$authadmin = 1;

				if ($authadmin==0) {
					RespostaRetorno('authadmin');
					return;
				} else {
					$valida_documento = new ValidaCPFCNPJ($cpf);
					if ($valida_documento->valida() ) {
						$consultalocatario = new ConsultaDatabase($uid);
						$consultalocatario = $consultalocatario->Locatario($cpf);
						if ($consultalocatario['lid']==0) {
							$addlocatario = new setRow();
							$addlocatario = $addlocatario->Locatario($uid,$nome,$cpf,$telefone,$email,$nascimento,$data);
							if ($addlocatario===true) {
								$consultalocatario = new ConsultaDatabase($uid);
								$consultalocatario = $consultalocatario->Locatario($cpf);
								if ($consultalocatario['lid']!=0) {
									$addendereco = new setRow();
									$addendereco = $addendereco->Endereco($uid,$consultalocatario['lid'],$cep,$rua,$numero,$bairro,$cidade,$estado,$complemento,1,$data);
									if ($addendereco===true) {
										$addhabilitacao = new setRow();
										$addhabilitacao = $addhabilitacao->Habilitacao($uid,$consultalocatario['lid'],$validade,$cnh,$data);
										if ($addhabilitacao===true) {

											//// IMAGEM CNH LOCATÁRIO
											clearstatcache();
											if (isset($_SESSION['img_cnh_info_session'])) {
												if (is_file($_SESSION['img_cnh_info_session']['img_cnh_path'].$_SESSION['img_cnh_info_session']['img_cnh_nome_completo'])) {
													$imagem_location_pra_rename = $_SESSION['img_cnh_info_session']['img_cnh_url_completo'];
													fit_image_file_to_width($_SESSION['img_cnh_info_session']['img_cnh_url_completo'], 1080, $_SESSION['img_cnh_info_session']['img_cnh_mime']);
													$imagem_location_rename = __DIR__.'/../../cnh/'.$cnh.$_SESSION['img_cnh_info_session']['img_cnh_extensao'];
													copy($imagem_location_pra_rename,$imagem_location_rename);
													unlink($imagem_location_pra_rename);
													unset($_SESSION['img_cnh_info_session']);

													clearstatcache();
													if (is_file($imagem_location_rename)) {
														// deleta imagens temporárias de cnh
														//unlinkTemp(__DIR__.'/../temp/');
													       RespostaRetorno('sucessolocatario');
													       return;
													} else {
													       RespostaRetorno('regcnhimagem');
													       return;
													} // moveu a imagem true
												} else {
												       RespostaRetorno('regcnhimagem');
												       return;
												} // file_exists
											} // $_SESSION['img_cnh_info_session']

										} else {
										       RespostaRetorno('reghabilitacao');
										       return;
										} // addhabilitacao true
									} else {
									       RespostaRetorno('regendereco');
									       return;
									} // addendereco true
								} else {
								       RespostaRetorno('reglocatario');
								       return;
								} // consultalocatario
							} else {
							       RespostaRetorno('reglocatario');
							       return;
							} // addlocatario true
						} else {
						       RespostaRetorno('reglocatarioexistente');
						       return;
						} // novo locatario
					} else {
					       RespostaRetorno('cpfinvalido');
					       return;
					} // cpf válido
				} // authadmin true
			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel ok
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // encontraadmin = 0
	} // campos preenchidos
} else {
	$cadastrando = ':((';
} // isset post submit

echo $cadastrando;

?>
