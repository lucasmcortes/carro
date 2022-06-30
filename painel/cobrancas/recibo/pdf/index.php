<?php

	include_once __DIR__.'/../../../../includes/setup.inc.php';

	if (isset($_SESSION['l_id'])) {
		$criador_pdf = 'Ophanim';
		$autor_pdf = 'Ophanim' ;
		$titulo_pdf = 'Ophanim' ;
		$assunto_pdf = 'Ophanim';
		$palavraschave_pdf = 'Ophanim';

		require_once __DIR__.'/recibo.pdf.php';

		$filename ='recibo_'.date('YmdHis').'.pdf';
		$filelocation = __DIR__ . '/../_recibos/';

		$fileNL = $filelocation."/".$filename;

		if (!file_exists($filelocation)) {
			mkdir($filelocation, 0755, true);
		}

		// exibe pdf
		$pdf->Output($fileNL, 'FI'); // salva e exibe
	} else if (!isset($_SESSION['l_id'])) {
	        redirectToLogin();
	} // uid

?>
