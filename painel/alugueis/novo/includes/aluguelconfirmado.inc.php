<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['aluguelconfirmado'])) {
	$passe = 0;
	$alugando = '';

	$lid = $_POST['lid'];
	$vid = $_POST['vid'];
	$diaria = $_POST['diaria'];
	$kilometragem = $_POST['kilometragem'];
	$inicio = $_POST['inicio'];
	$devolucao = $_POST['devolucao'];
	$kilometragematual = $_POST['kilometragematual'];
	$paginicial = $_POST['paginicial'];
	$forma = $_POST['forma'];
	$caucao = $_POST['caucao'];
	$formacaucao = $_POST['formacaucao'];

	$comeco = new DateTime($inicio);
	$conclusao = new DateTime($devolucao);

	$addaluguel = new setRow();
	$addaluguel = $addaluguel->Aluguel($uid,$lid,$vid,$diaria,$kilometragem,$inicio,$devolucao,$data);
	if ($addaluguel===true) {
		$aluguel = new ConsultaDatabase($uid);
		$aluguel = $aluguel->AluguelAdicionado($vid);
		$aluguelinfo = new ConsultaDatabase($uid);
		$aluguelinfo = $aluguelinfo->AluguelInfo($aluguel['aid']);

		$guid = mb_strtoupper(Guid());
		do {
			$guid = mb_strtoupper(Guid());
		} while ($guid==$aluguelinfo['guid']);
		$addguid = new setRow();
		$addguid = $addguid->AluguelGuid($aluguelinfo['aid'],$guid,$aluguelinfo['data']);
		if ($addguid===true) {
			$addkilometragem = new setRow();
			$addkilometragem = $addkilometragem->Kilometragem($uid,$vid,$kilometragematual,$data);
			if ($addkilometragem===true) {
				if ( ($comeco->format('Y-m-d H'))>($agora->format('Y-m-d H')) ) {
					// é uma reserva
					$passe=1;
					$reserva=1;
					$addreserva = new setRow();
					$addreserva = $addreserva->Reserva($uid,$aluguel['aid'],0,$inicio,$devolucao,$data);
					if ($addreserva===true) {
						$reserva = new ConsultaDatabase($uid);
						$reserva = $reserva->Reserva($aluguel['aid']);

						$addativacao = new setRow();
						$addativacao = $addativacao->Ativacao($uid,$reserva['reid'],'S',$data);
						if ($addativacao===true) {
							$passe=0;
						} else {
							RespostaRetorno('ativacao');
							return;
						} // addativacao true
					} else {
						RespostaRetorno('reserva');
						return;
					} // addreserva true
				} // é uma reserva (inicio > hoje)

				if ($passe==0) {
					$pagamentoinicial = new setRow();
					$pagamentoinicial = $pagamentoinicial->PagamentoInicial($uid,$aluguel['aid'],$paginicial,$forma,$data);
					if ($pagamentoinicial===true) {
						$addcaucao = new setRow();
						$addcaucao = $addcaucao->AluguelCaucao($uid,$aluguel['aid'],$caucao,$formacaucao,$data);
						if ($addcaucao===true) {
							// manda cartinha, confirma
							RespostaRetorno('sucessoaluguel');
							return;
						} else {
							RespostaRetorno('regcaucao');
							return;
						} // addcaucao true
					} else {
						RespostaRetorno('regpaginicial');
						return;
					} // pagamentoinicial true
				} // passe = 0
			} else {
				RespostaRetorno('regkm');
				return;
			} // addkilometragem true
		} else {
			RespostaRetorno('regguid');
			return;
		} // addguid true
	} else {
		RespostaRetorno('regaluguel');
		return;
	} // addaluguel true
} else {
	$alugando = ':((';
} // isset post submit

echo $alugando;

?>
