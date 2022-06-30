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
<th colspan="4"><b></b></th>
<th colspan="4"><b>Vencimento: '.$mesVencimentoPromissoria.'</b></th>
</tr>
<tr style="font-size:18px;background-color:whitesmoke;line-height:55px;">
<th colspan="4" style="text-align:left;"><b>Nº 01/01</b></th>
<th colspan="4" style="text-align:right;"><b>'.Dinheiro($aluguel['valor_caucao']).'</b></th>
</tr>
<tr>
<th colspan="4"></th>
<th colspan="4"></th>
</tr>
<tr style="font-size:13px;">
<th colspan="8" style="text-align:left;">
<br/>
Ao(s) '.$aos.' do mês de '.$mesDevolucaoExtenso.' de '.$devolucao->format('Y').' pagarei por essa única via de <b>NOTA PROMISSÓRIA</b> a '.$razao_social_pdf.', CNPJ '.$cnpj_pdf.', ou à sua ordem, a quantia de <b>'.ltrim(extenso($aluguel['valor_caucao']),' ').'</b> em moeda corrente deste país.
</th>
</tr>
<tr style="line-height:43px;">
<th colspan="4" style="text-align:left;"><b>Pagável em '.$cidade_promissoria.'</b></th>
<th colspan="4" style="text-align:right;"><b>Data de emissão: '.$inicio->format('d/m/Y').'</b></th>
</tr>
<tr style="line-height:21px;">
<th colspan="4" style="text-align:left;"><b>Emitente</b>: '.$locatario['nome'].'</th>
<th colspan="4" style="text-align:right;"><b>CPF</b>: '.$locatario['documento'].'</th>
</tr>
<tr style="line-height:13px;">
<th colspan="8" style="text-align:left;"><b>Endereço</b>: '.$locatario['rua'].', '.$locatario['numero'].', '.$locatario['bairro'].' - '.$locatario['cidade'].' - '.$locatario['estado'].'</th>
</tr>
<tr>
<th colspan="4"></th>
<th colspan="4"></th>
</tr>
<tr style="line-height:64px;">
        <th colspan="8"><b>Assinatura do emitente</b>: ____________________________________________________________</th>
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
