<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];
	$resultado = '';

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($aluguel['lid']);
	$reserva = new ConsultaDatabase($uid);
	$reserva = $reserva->Reserva($aid);
	$ativa = new ConsultaDatabase($uid);
	$ativa = $ativa->ReservaAtiva($reserva['aid']);
	if ($ativa['atid']!=0) {
		if ($ativa['ativa']=='S') {
			$cartinha = new Cartinha();
			$cartinha = $cartinha->enviarCartinha('lembrete-reserva',$locatario['email']);
			$addlembrete = new setRow();
			$addlembrete = $addlembrete->Lembrete($uid,$aid,$data);

			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						".$cartinha['resposta']."
					</p>
				</div>
			";

			unset($_SESSION['lembrete']);
		} // ativa
	} // atid
} else {
	$resultado = 0;
}// $_post

$resultado .= '<script>atualizaCard('.$aluguel['vid'].');</script>';

echo $resultado;

?>
