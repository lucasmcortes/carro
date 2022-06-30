<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitveiculo'])) {
	$submit = '';
	$veiculo = '';

	$modelo = $_POST['modelo'];
	if (empty($modelo)) {
		RespostaRetorno('modelo');
		return;
	} // modelo

	$marca = $_POST['marca'];
	if (empty($marca)) {
		RespostaRetorno('marca');
		return;
	} // marca

	$ano = $_POST['ano'];
	if (empty($ano)) {
		RespostaRetorno('ano');
		return;
	} // ano

	$completo = $_POST['completo'];
	if (empty($completo)) {
		RespostaRetorno('completo');
		return;
	} // completo

	$cor = $_POST['cor'];
	if (empty($cor)) {
		RespostaRetorno('cor');
		return;
	} // cor

	$revisao = $_POST['revisao'];

	$categoria = $_POST['categoria'];
	if (empty($categoria)) {
		RespostaRetorno('categoria');
		return;
	} // categoria

	$portas = $_POST['portas'];
	$potencia = $_POST['potencia'];
	if ( ($categoria==1) || ($categoria==2) ) {
		if ( (empty($portas)) || ($portas==1) ) {
			RespostaRetorno('portas');
			return;
		} // escolher portas
		if ( (empty($potencia)) || ($potencia==1) ) {
			RespostaRetorno('potencia');
			return;
		} // escolher potencia
	} // se é carro ou utilitario

	if (empty($_POST['placa'])) {
		RespostaRetorno('placaveiculo');
		return;
	} else {
		$placa = new Conforto($uid);
		$placa = $placa->FormatoPlaca($_POST['placa']);
	} // placa

	$chassi = $_POST['chassi'];
	if (empty($chassi)) {
		RespostaRetorno('chassi');
		return;
	} // chassi

	$renavam = $_POST['renavam'];
	if (empty($renavam)) {
		RespostaRetorno('renavam');
		return;
	} // renavam

	$km = str_replace(array(',','.'),'',$_POST['km'])??0;

	$observacao = $_POST['observacao'];

	if (empty($modelo) || empty($marca) || empty($ano) || empty($completo) || empty($cor) || empty($placa) || empty($chassi) || empty($renavam) || empty($categoria) || empty($portas) ) {
		RespostaRetorno('vazio');
		return;
	} else {
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
					$consultaveiculo = new ConsultaDatabase($uid);
					$consultaveiculo = $consultaveiculo->EncontraVeiculo($placa);
					if ($consultaveiculo['vid']==0) {
						$addveiculo = new setRow();
						$addveiculo = $addveiculo->Veiculo($uid,$categoria,$marca,$modelo,$potencia,$placa,$chassi,$renavam,$ano,$cor,'S',$data);
				 		if ($addveiculo===true) {
							$vidveiculo = new ConsultaDatabase($uid);
							$vidveiculo = $vidveiculo->EncontraVeiculo($placa);
						} else {
							RespostaRetorno('regveiculo');
							return;
						} // addveiculo true
					} else {
						RespostaRetorno('veiculoexistente');
						return;
						$vidveiculo = new ConsultaDatabase($uid);
						$vidveiculo = $vidveiculo->EncontraVeiculo($placa);
					} // consultaveiculo true

					if ($vidveiculo['vid']==0) {
						RespostaRetorno('regveiculo');
						return;
					} else {
						$addvobs = new setRow();
						$addvobs = $addvobs->Vobs($uid,$vidveiculo['vid'],$portas,$completo,$revisao,$observacao,$data);
						if ($addvobs===true) {
							$addkm = new setRow();
							$addkm = $addkm->Kilometragem($uid,$vidveiculo['vid'],$km,$data);
							if ($addkm===true) {
								$addlimpeza = new setRow();
								$addlimpeza = $addlimpeza->Limpeza($uid,$vidveiculo['vid'],'S',$data);
								if ($addlimpeza===true) {
									//// IMAGEM DA DOCUMENTACAO
									clearstatcache();
									if (isset($_SESSION['img_doc_info_session'])) {
										if (is_file($_SESSION['img_doc_info_session']['img_doc_path'].$_SESSION['img_doc_info_session']['img_doc_nome_completo'])) {
											$imagem_location_pra_rename = $_SESSION['img_doc_info_session']['img_doc_url_completo'];
											fit_image_file_to_width($_SESSION['img_doc_info_session']['img_doc_url_completo'], 1080, $_SESSION['img_doc_info_session']['img_doc_mime']);
											$imagem_location_rename = __DIR__.'/../../doc/'.$placa.$_SESSION['img_doc_info_session']['img_doc_extensao'];
											copy($imagem_location_pra_rename,$imagem_location_rename);
											unlink($imagem_location_pra_rename);

											clearstatcache();
											if (is_file($imagem_location_rename)) {
												// deleta imagens temporárias de doc
												unset($_SESSION['img_doc_info_session']);
											} else {
												//RespostaRetorno('regimagemdoc');
												//return;
											} // moveu a imagem true
										} else {
											//RespostaRetorno('regimagemdoc');
											//return;
										} // file_exists
									} // $_SESSION['img_doc_info_session']


									//// IMAGEM DO VEÍCULO
									clearstatcache();
									if (isset($_SESSION['img_foto_info_session'])) {
										if (is_file($_SESSION['img_foto_info_session']['img_foto_path'].$_SESSION['img_foto_info_session']['img_foto_nome_completo'])) {
											$imagem_location_pra_rename = $_SESSION['img_foto_info_session']['img_foto_url_completo'];
											fit_image_file_to_width($_SESSION['img_foto_info_session']['img_foto_url_completo'], 1080, $_SESSION['img_foto_info_session']['img_foto_mime']);
											$imagem_location_rename = __DIR__.'/../../foto/'.$placa.$_SESSION['img_foto_info_session']['img_foto_extensao'];
											copy($imagem_location_pra_rename,$imagem_location_rename);
											unlink($imagem_location_pra_rename);

											clearstatcache();
											if (is_file($imagem_location_rename)) {
												// deleta imagens temporárias de foto
												unset($_SESSION['img_foto_info_session']);
											} else {
												//RespostaRetorno('regimagemfoto');
												//return;
											} // moveu a imagem true
										} else {
											//RespostaRetorno('regimagemfoto');
											//return;
										} // file_exists
									} // $_SESSION['img_foto_info_session']

									//unlinkTemp(__DIR__.'/../temp/');
									RespostaRetorno('sucessoveiculo');
									return;
								} else {
									RespostaRetorno('reglimpeza');
									return;
								} // addlimpeza true
							} else {
								RespostaRetorno('regkm');
								return;
							} // addkm true
						} else {
							RespostaRetorno('regvobs');
							return;
						} // addvobs true
					} // vidveiculo true
				} // authadmin true
			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel ok
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // encontraadmin = 0
	} // campospreenchidos = 0
} else {
	$submit = ':((';
} // isset post submit

echo $submit;

?>
