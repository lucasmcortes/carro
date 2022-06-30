<?php

	include_once __DIR__.'/setup.inc.php';

	$visita_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	// bota na blacklist se veio querendo acessar com palavras da hlist.txt (hackerlist)
	//include_once __DIR__.'/blacklisting.inc.php';

	// se tem o [d] no link, vai pro 404 e adiciona conforme abaixo na tabela visitas
	if (isset($_GET['d'])) {
		$visita_link = "desencontro (" . base64_decode($_GET['d']) . ")";
	}

	if (isset($_SESSION['l_id'])) {
		$visita_user_id = $_SESSION['l_id'];
	} else {
		$visita_user_id = 0;
	}

	if (isset($_SERVER['REMOTE_ADDR'])) {
		$rmt_addr = $_SERVER['REMOTE_ADDR'];
	} else {
		$rmt_addr = '0';
	}

	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
	} else {
		$user_agent = '0';
	}

	if (!function_exists('insereVisita')) {
		function insereVisita($visita_link,$visita_user_id,$rmt_addr,$user_agent) {
			$cond = 0;
			$visita = 0;

			$cond++;
			if ( (strpos($visita_user_id, 'robots') !== false) || ($visita_user_id = 0) ) {
				$visita = 0;
			} else {
				$visita++;
			}

			$cond++;
			if ($rmt_addr != 0) {
				$visita++;
			} else {
				$visita = 0;
			}

			$cond++;
			if ( (strpos($user_agent, 'bot') !== false) || ($user_agent = 0) ) {
				$visita = 0;
			} else {
				$visita++;
			}

			$cond++;
			if ( (strpos($visita_link, 'robots') !== false) || (strpos($visita_link, 'includes') !== false) ) {
				$visita = 0;
			} else {
				$visita++;
			}

			if ($visita == $cond) {
				$visita = 1;
			} else {
				$visita = 0;
			}

			return $visita;
		} // função que filtra e insere a visita na tabela
	}

	if (insereVisita($visita_link,$visita_user_id,$rmt_addr,$user_agent) == 1) {
		// pega o visita_link que foi verificado pela função
		// pra usar no modelosetup.inc.php
		// pra mandar pro entrar-portatil.php pra ir depois de logar
		// e pra mandar pro visita_width.inc.php atualizar a tabela com a largura da tela
		$visita_link_cleared = $visita_link;

		$addvisita = new setRow();
		$addvisita = $addvisita->Visitas($visita_user_id,$visita_link,$rmt_addr,$user_agent,$data);
	}

	// pra usar no botoes_licenciamento_ajax
	if (strpos($visita_link, '?m=') !== false) {
		$_SESSION['desejo'] = $visita_link;
	} else {
		$_SESSION['desejo'] = $dominio.'/musicas/';
	}

?>
