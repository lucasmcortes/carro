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
                        <br/><br/><br/><br/><img src="'.$dominio.'/img/logo.png" width="90"/>
                </th>
                <th colspan="10">
                        <p style="text-align:center;line-height:13px;top:0;margin:0;">
                                <span style="font-size:15px;">BIKE 46 LOCADORA DE VE??CULOS</span>
                                <br/><br/>
                                <span style="font-size:12px;">Av. Brasil, 3861 - Manoel Hon??rio</span>
                                <br/><br/>
                                <span style="font-size:13px;"><b>Hor??rio de funcionamento:</b></span>
                                <br/><br/>
                                <span style="font-size:13px;"><b>08h ??s 11h e 13h ??s 17h30</b></span>
                                <br/><br/>
                                <span style="font-size:8px">CNPJ: 20.481.199/0001-24</span>
                        </p>
                </th>
                <th colspan="2">
                        <p style="text-align:center;line-height:13px;top:0;margin:0;">
                                <span style="font-size:9px;">CONTRATO DE LOCA????O</span>
                                <br/><br/><br/><br/>
                                <span style="font-family:monospace;">N?? '.$contrato_numero.'</span>
                        </p>
                </th>
        </tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = '
<table>
        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>CONTRATO DE LOCA????O DE AUTOM??VEL DE PRAZO DETERMINADO</b></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>IDENTIFICA????O DAS PARTES CONTRATANTES</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>LOCADOR</b>: BIKE 46 COM. VAR. LTDA, com endere??o na AV. BRASIL, 3861, Juiz de Fora,
                        MG, CEP: 36035-030, inscrito no CNPJ sob o n?? 20.481.199/0001.24.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>LOCAT??RIO</b>: '.$locatario['nome'].', residente em '.$locatario['rua'].', '.$locatario['numero'].', '.$locatario['bairro'].' - '.$locatario['cidade'].' - '.$locatario['estado'].', inscrito no CPF sob o n?? '.$locatario['documento'].', CNH n?? '.$locatario['cnh'].'.
                        As partes acima identificadas t??m, entre si, justo e acertado o presente Contrato de Loca????o de
                        Autom??vel de Prazo Determinado, que se reger?? pelas cl??usulas seguintes e pelas condi????es
                        descritas no presente contrato.
                </th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DO OBJETO DO CONTRATO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 1??</b>. O presente contrato tem como OBJETO a loca????o do ve??culo
                        '.$veiculo['modelo'].', ano '.$veiculo['ano'].', chassi '.$veiculo['chassi'].', cor predominantemente '.mb_strtolower($veiculo['cor']).', placa '.$veiculo['placa'].'
                </th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DO USO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 2??</b>. O autom??vel, objeto deste contrato, ser?? utilizado exclusivamente pelo
                        LOCAT??RIO para servi??o PARTICULAR, n??o sendo permitido o seu uso por terceiros, sob
                        pena de rescis??o contratual e o pagamento da multa prevista na Cl??usula 5.1. ?? de total
                        responsabilidade do LOCAT??RIO qualquer dano material ocorrido no ve??culo, assim como quaisquer infra????es
                        de tr??nsito.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>2.1</b>. O LOCADOR n??o constituir?? nenhum v??nculo empregat??cio caso o LOCAT??RIO se utilize
                        do ve??culo para fins lucrativos no transporte de terceiros ou de cargas, prevalecendo sempre o
                        objeto deste contrato somente a loca????o do ve??culo mencionado pactuada de comum acordo
                        entre as partes. O ve??culo objeto deste contrato ser?? utilizado exclusivamente para fins
                        l??citos e particulares, permitidos pelos diplomas legais vigentes. Caso ocorra a viola????o desta
                        cl??usula, a responsabilidade civil ou criminal ser?? inteiramente do LOCAT??RIO.
                </th>
        </tr>

</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->AddPage();
$tbl = '
<table>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DA DEVOLU????O</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 3??</b>. O LOCAT??RIO dever?? devolver o ve??culo ao locador nas mesmas condi????es
                        em que estava quando o recebeu, ou seja, em perfeitas condi????es de uso, respondendo pelos
                        danos ou preju??zos causados.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>3.1</b>. O LOCAT??RIO vistoriou o ve??culo dado em loca????o por este instrumento, quando
                        constatou seu perfeito estado de conserva????o, funcionamento e sem avarias, de acordo com o
                        CHECK LIST em anexo.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>3.2</b>. REPAROS E CONSERVA????O ??? O LOCAT??RIO recebe o bem em perfeitas condi????es de
                        limpeza, conserva????o e funcionamento, obrigando-se a mant??-lo como tal. Para reposi????o de
                        pe??as o LOCAT??RIO deve utilizar pe??as originais e especificadas pela montadora do
                        ve??culo.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>3.3</b>. VISTORIA ??? O LOCADOR poder?? vistoriar o bem a qualquer tempo, por si ou por preposto,
                        a fim de verificar o cumprimento das obriga????es do contrato.
                </th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DO PRAZO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 4??</b>. A presente loca????o ter?? o lapso temporal de validade de '.($prazohoras/24).' dias,
                        iniciando no dia '.$inicio->format('d/m/Y').' ??s '.$inicio->format('h').'h e terminando no dia '.$devolucao->format('d/m/Y').'  ??s '.$inicio->format('h').'h,
                        podendo ser prorrogado e tornar-se indeterminado ante pr??via comunica????o.
                </th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DA RESCIS??O</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 5??</b>. Caso n??o ocorra a restitui????o do ve??culo ao LOCADOR pelo LOCAT??RIO dentro do per??odo estipulado, este dever?? pagar,
                        pelo per??odo em que o detiver em seu poder, o valor que o LOCADOR arbitrar, e responder?? por quaisquer danos
                        que o ve??culo venha a sofrer durante todo o per??odo, ainda que decorrente de caso fortuito ou for??a maior.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>5.1</b>. O descumprimento de alguma das cl??usulas por parte dos contratantes ensejar?? a
                        rescis??o deste instrumento de contrato, e o devido pagamento de multa no valor de R$500,00 (quinhentos reais) pela parte
                        inadimplente.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>5.2</b>. Caso haja atraso no pagamento o LOCADOR poder?? reaver a posse do ve??culo sem
                        qualquer ??nus ou consequ??ncia por isso.
                </th>
        </tr>

</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->AddPage();
$tbl = '
<table>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DEVERES DO LOCAT??RIO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 6??</b>. O LOCAT??RIO assume e isenta o LOCADOR a prote????o contra danos pessoais,
                        bem como danos que por ventura venha a sofrer ele pr??prio, pessoa de sua fam??lia,
                        pedestres e passageiros que estiverem ocupando o ve??culo, morte ou les??o corporal de
                        natureza grave, assumir?? a demanda oriunda desses eventos na esfera civil e penal em
                        consequ??ncia de inc??ndio, ou dos carros envolvidos em acidente, incidentes no per??odo em
                        que exercer a loca????o, mesmo que o d??bito venha a ser constatado, apurado, denunciado ou
                        cobrado posteriormente. Os danos materiais de terceiros em ve??culos que ultrapassem o limite
                        da prote????o contratada pelo LOCADOR, bem como danos morais, danos pessoais derivados
                        do evento danoso, ser??o de responsabilidade ??nica do LOCAT??RIO.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>6.1</b>. As prote????es n??o constituem seguro, mas um acordo oferecido pelo LOCADOR ao
                        LOCAT??RIO, por meio do qual, o LOCADOR, mediante o pagamento de uma taxa mensal que
                        varia de acordo com a prote????o escolhida e o grupo do ve??culo, atrav??s de contrato de ades??o
                        feito com ???Associa????o dos propriet??rios de ve??culos automotores???.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>6.2</b>. Al??m do aluguel acima estipulado, o LOCAT??RIO pagar?? todos os impostos ou quaisquer
                        outras despesas que recaiam ou venham a recair sobre o ve??culo locado, inclusive franquia,
                        caso venha a utilizar. Os pagamentos dos acess??rios ser??o feitos nos respectivos vencimentos.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 7??</b>. O LOCAT??RIO assume todos os encargos legais como pagamento de multas,
                        pontos na CNH e penalidades decorrentes de infra????es ??s leis e regulamentos de tr??nsito,
                        durante o per??odo que estiver de posse do ve??culo, mesmo que o d??bito venha a ser
                        constatado, apurado ou cobrado posteriormente.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.1</b>. O LOCAT??RIO assume as despesas de combust??vel.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.2</b>. O LOCAT??RIO fica respons??vel pelo pagamento no valor de '.Dinheiro($aluguel['valor_caucao']).' ('.ltrim(extenso($aluguel['valor_caucao']),' ').') em forma de cau????o para resguardo de acionamento da franquia ou caso haja
                        alguma eventualidade como acidente, roubo/furto ou multas e autua????es.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.3</b>. O LOCAT??RIO obriga-se a fornecer estacionamento para o ve??culo em condi????es de
                        seguran??a, preserva????o e prote????o dos efeitos da natureza ou danos causados por terceiros.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.4</b>. O LOCAT??RIO obriga-se a comunicar imediatamente ao LOCADOR ocorr??ncia de
                        acidente, furto, roubou inc??ndio, al??m de responsabilizar-se pelas consequ??ncias do ocorrido, e
                        a providenciar Boletim de Ocorr??ncia policial ou Laudo Pericial, quando se fizer necess??rio, no
                        prazo imediato ap??s o evento.
                </th>
        </tr>

</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->AddPage();
$tbl = '
<table>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DO VALOR DO ALUGUEL</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cl??usula 8??</b>. Ser?? alugado pelo valor de '.Dinheiro($aluguel['diaria']).' ('.ltrim(extenso($aluguel['diaria']),' ').') por di??ria.
                        O pagamento ser?? realizado atrav??s de deposito banc??rio (previamente comunicado) ou diretamente ao LOCADOR, no local e prazo acordado entre as partes.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DO FORO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        Para dirimir quaisquer controv??rsias oriundas do CONTRATO, as partes elegem o foro da
                        comarca de Juiz de Fora ??? MG, como o ??nico competente para a solu????o de quest??es oriundas
                        do presente acordo, que amigavelmente as partes n??o puderem resolver, obrigando-se as
                        partes por si, herdeiros e sucessores ao fiel cumprimento do mesmo.
                </th>
        </tr>

        <tr style="line-height:90px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        Por estarem assim justos e contratados, firmam o presente instrumento, em duas vias de igual
                        teor, juntamente com 2 (duas) testemunhas.
                </th>
        </tr>

        <tr style="line-height:55px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;">Juiz de Fora, '.$agora->format('d').' de '.$mesAtualExtenso.' de '.$agora->format('Y').'</th>
        </tr>
        <tr style="line-height:55px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;">______________________________________________________________</th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;">'.$locatario['nome'].'</th>
        </tr>
        <tr style="line-height:55px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;">______________________________________________________________</th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;">BIKE 46 COM VAR LTDA</th>
        </tr>
        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>
        <tr style="line-height:72px;">
                <th colspan="8" style="text-align:center;">TESTEMUNHAS:</th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="4" style="text-align:left;">_______________________________</th>
                <th colspan="4" style="text-align:left;">_______________________________</th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="4" style="text-align:left;">NOME:</th>
                <th colspan="4" style="text-align:left;">NOME:</th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="4" style="text-align:left;">CPF:</th>
                <th colspan="4" style="text-align:left;">CPF:</th>
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
