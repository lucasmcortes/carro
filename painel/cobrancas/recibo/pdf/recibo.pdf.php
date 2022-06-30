<?php

/**
 * Creates an example PDF/A-1b document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: PDF/A-1b mode
 * @author Nicola Asuni
 * @since 2011-09-28
 */

// Include the main TCPDF library (search for installation path).
//require_once(__DIR__.'/../../../../includes/TCPDF-master/examples/tcpdf_include.php');
//require_once(__DIR__.'/../../../../includes/TCPDF-master/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator($criador_pdf);
$pdf->SetAuthor($autor_pdf);
$pdf->SetTitle($titulo_pdf);
$pdf->SetSubject($assunto_pdf);
$pdf->SetKeywords($palavraschave_pdf);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//$pdf->SetFont('helvetica', '', 12);

// set margins
$pdf->SetMargins(21, 13, 21, 13); // left top right bot
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

// set auto page breaks
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// Add a page
// This method has several options, check the source code documentation for more information.
//$pdf->AddPage();
$pdf->AddPage('L', 'A5');

// Set some content to print

$tbl = '
<table style="font-size:13px;line-height:19px;">
<tr style="text-align:right;line-height:34px;">
<th colspan="8"><b style="text-align:center;font-size:21px;">RECIBO</b></th>
</tr>

<tr style="font-size:18px;background-color:whitesmoke;line-height:55px;">
<th colspan="4" style="text-align:left;"><b>Nº '.$_GET['numero'].'</b></th>
<th colspan="4" style="text-align:right;"><b>'.Dinheiro($_GET['importancia']).'</b></th>
</tr>

<tr>
<br/>
<th colspan="8"><br/><b>Recebemos de: </b>'.mb_strtoupper($_GET['pagador'],'UTF-8').'</th>
</tr>

<tr>
<th colspan="8" style="text-align:left;"><b>Endereço: </b>'.mb_strtoupper($_GET['endereco'],'UTF-8').'</th>
</tr>

<tr>
<th colspan="8" style="text-align:left;">A importância de <b>'.ltrim(extenso($_GET['importancia']),' ').'</b></th>
</tr>

<tr>
<th colspan="8" style="text-align:left;">Referente a '.mb_strtolower($_GET['referente'],'UTF-8').'</th>
</tr>

<tr style="font-size:13px;">
<th colspan="8" style="text-align:left;">
<br/><br/>
• '.$_GET['cidade'].', '.$_GET['datarecibo'].'
</th>
</tr>

<tr>
<th colspan="5" style="text-align:left;"><b>Emitente: </b>'.$_GET['emitente'].'</th>
<th colspan="3" style="text-align:right;"><b>'.$_GET['tipodocumento'].': </b>'.$_GET['documento'].'</th>
</tr>

<tr>
<br/><br/>
<th colspan="8"><b>Assinatura</b>: ______________________________________________________________________</th>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');


// ---------------------------------------------------------

ob_clean();

//============================================================+
// END OF FILE
//============================================================+
?>
