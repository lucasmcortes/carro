<?php
	// http to https
	// $domínio, funções
	// session_start, dbh
	// eDepois, data e hora
	// title, descrição, robots
	// visita
	require_once __DIR__.'/includes/setup.inc.php';
	// (sem enviar headers até aqui)
?>
<!doctype html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html"; charset="UTF-8" />
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0"/>

	<script src="<?php echo $dominio ?>/includes/jquery.min.js"></script>
	<script src="<?php echo $dominio ?>/includes/jquery-ui.js"></script>
	<script src='<?php echo $dominio ?>/includes/interact.min.js'></script>

	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	<title><?php echo $title ?></title>
	<meta name="robots" content="<?php echo $robots ?>">

	<link rel="shortcut icon" href="<?php echo $dominio ?>/favicon.ico">
	<link rel="icon" type="image/png" href="<?php echo $dominio ?>/favicon.png" sizes="32x32">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $dominio ?>/apple-touch-icon.png">
	<meta name="msapplication-TileImage" content="<?php echo $dominio ?>/mstile-144x144.png">
	<meta name="msapplication-TileColor" content="#ffffff">

	<link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo $dominio ?>/estilo.css">

	<link rel="canonical" href="https://www.ophanim.com.br">
	<meta name="title" content="<?php echo $title ?>" />
	<meta name="description" content="" />
	<meta name="theme-color" content="<?php echo $cores_root['cremelight']['cor'] ?>"/>

	<meta name="og:url" property="og:url" content="<?php echo $dominio ?>" />
	<meta name="og:title" property="og:title" content="<?php echo $title ?>" />
	<meta name="og:site_name" property="og:site_name" content="Ophanim Desenvolvimento" />
	<meta name="og:type" property="og:type" content="website" />
	<meta name="og:image" property="og:image" content="<?php echo $dominio ?>/img/logo.png" />
	<meta name="og:image:type" property="og:image:type" content="image/png" />
	<meta name="og:image:width" property="og:image:width" content="400" />
	<meta name="og:image:height" property="og:image:height" content="400" />
	<meta name="og:description" property="og:description" content="<?php echo $head_desc ?>" />

</head>

<body>

<div id='loader'>
	<svg class='spinner' viewBox='0 0 50 50'>
		<circle class='path' cx='25' cy='25' r='20' fill='none' stroke-width='3'></circle>
	</svg>
</div>

<?php
	require_once __DIR__.'/includes/fundamental.inc.php';
	require_once __DIR__.'/includes/vestimenta.inc.php';
	require_once __DIR__.'/includes/superior.inc.php';
	require_once __DIR__.'/menutop.php';
	require_once __DIR__.'/includes/cep.inc.php';
?>

<extremos>
	<script>
		$("extremos").css({"display": "none"});
		$("body").css({"position": "fixed", "width": "100%", "overflow-y": "scroll"});
	</script>
