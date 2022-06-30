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
$pdf->AddPage();

// Set some content to print
$tbl = '
<table>
<tr style="border:1px solid #000000;">
        <th colspan="2">
                <br/><br/><img src="'.$dominio.'/painel/configuracoes/logo/'.$logo_empresa.'" width="90"/>
        </th>
        <th colspan="10">
                <p style="text-align:center;line-height:13px;top:0;margin:0;">
                        <span style="font-size:15px;">'.$razao_social_pdf.'</span>
                        <br/>
                        <span style="font-size:12px;">'.$endereco_pdf.'</span>
                        <br/>
                        <span style="font-size:8px">CNPJ: '.$cnpj_pdf.'</span>
                </p>
        </th>
        <th colspan="2">
                <p style="text-align:center;line-height:13px;top:0;margin:0;">
                        <span style="font-size:9px;">CONTRATO DE LOCAÇÃO</span>
                        <br/><br/>
                        <span style="font-family:monospace;">Nº '.$contrato_numero.'</span>
                </p>
        </th>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$html .= '
	<p style="min-width:100%;max-width:100%;font-size:11px;text-align:justify;">
		Eu, <b>'.$locatario['nome'].'</b>, CPF <b>'.$locatario['documento'].'</b>, CNH <b>'.$locatario['cnh'].'</b>, declaro para os devidos fins de direito que na presente data peguei a título de <b>VEÍCULO RESERVA</b> o automóvel <b>'.$veiculo['modelo'].'</b>, placa <b>'.$veiculo['placa'].'</b> às <b>'.$inicio->format('H').'h'.$inicio->format('i').'</b> e o entregarei num prazo de <b>'.$prazohoras.'h</b>.
                Sei que sou responsável para arcar com a participação obrigatória de <b>'.Dinheiro($aluguel['valor_caucao']).'</b> em caso de acidente, furto ou roubo do mesmo; também sou responsável por danos a terceiros, multas e quaisquer infrações que ocorrerem durante o período em que o veículo se encontrar em meu poder.
                Será cobrada uma taxa de <b><u>'.Dinheiro($configuracoes['preco_le']).' para '.$limpezaexecutiva['tipo'].'</u></b>, <b><u>'.Dinheiro($configuracoes['preco_lc']).' para '.$limpezacompleta['tipo'].'</u></b> e <b><u>'.Dinheiro($configuracoes['preco_lm']).' para '.$limpezacompletacommotor['tipo'].'</b></u>.
                Também declaro ciência de que sou responsável pelo pagamento do valor de <b>'.Dinheiro($excedente).' por cada diária</b> que exceda ao prazo estipulado. '.$especificacao_km.'
                <br/>• Veículo entregue <b>'.$limpezaatual.'</b>
                <br/>
        </p>
';
$pdf->writeHTML($html, true, false, true, false, '');

$tbl = '
<table border="1" style="font-size:11px;line-height:19px;">
<tr style="text-align:center;">
<th colspan="4"><b>Controle de entrega</b></th>
<th colspan="4"><b>Controle de devolução</b></th>
</tr>
<tr>
<th colspan="2">&nbsp;Data: '.$inicio->format('d/m/Y').'</th>
<th colspan="2">&nbsp;Hora: '.$inicio->format('H').'h</th>
<th colspan="2">&nbsp;Data:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th colspan="2">&nbsp;Hora:</th>
</tr>
<tr>
<th colspan="2">&nbsp;Km: '.Kilometragem($veiculo['km']).'</th>
<th colspan="2">&nbsp;Combustível:</th>
<th colspan="2">&nbsp;Km:</th>
<th colspan="2">&nbsp;Combustível:</th>
</tr>
<tr>
<th colspan="4">&nbsp;Entregue por: '.$locador['nome'].'</th>
<th colspan="4">&nbsp;Recebido por:</th>
</tr>
<tr>

<table border="1" style="font-size:11px;line-height:19px;">
<tr style="text-align:center;">
<th colspan="32"><b>Acessórios</b></th>
</tr>
<tr>
<th colspan="12" style="background-color:whitesmoke;"></th>
<th colspan="2">&nbsp;Ent.</th>
<th colspan="2">&nbsp;Dev.</th>

<th colspan="12" style="background-color:whitesmoke;"></th>
<th colspan="2">&nbsp;Ent.</th>
<th colspan="2">&nbsp;Dev.</th>
</tr>
<tr>
<th colspan="12">&nbsp;Documentos</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Macaco</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Som</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Extintor</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Ar condicionado</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Tapetes</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Calotas</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Triângulo de sinalização</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Estepe novo</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Nível de óleo do motor</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Estepe usado</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Bateria</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Antena</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Chave de roda</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
</table>
</tr>
<tr>
<table>
<tr>
        <br/>
        <th colspan="12">
                <img src="'.$dominio.'/img/checklistcarro.png" width="300"/>
        </th>
        <th colspan="12">
                <img src="'.$dominio.'/img/checklistmoto.png" width="300"/>
        </th>
</tr>
</table>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = '
<table>
<tr>
        <td>
                <p><b>Placa do veículo associado</b>: '.$placa_escolhida.'</p>
        </td>
        <td>
                <p><b>Telefone</b>: '.$telefone.'</p>
        </td>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = '
<table>
<tr style="line-height:23px;">
        <td><p><b>Observações</b>: _________________________________________________________________________________________________________________________________________________________________________________________________________</p></td>
</tr>
<tr style="line-height:34px;">
        <td><p><b>Assinatura (igual na habilitação)</b>: __________________________________________</p></td>
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
