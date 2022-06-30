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
                                <span style="font-size:15px;">BIKE 46 LOCADORA DE VEÍCULOS</span>
                                <br/><br/>
                                <span style="font-size:12px;">Av. Brasil, 3861 - Manoel Honório</span>
                                <br/><br/>
                                <span style="font-size:13px;"><b>Horário de funcionamento:</b></span>
                                <br/><br/>
                                <span style="font-size:13px;"><b>08h às 11h e 13h às 17h30</b></span>
                                <br/><br/>
                                <span style="font-size:8px">CNPJ: 20.481.199/0001-24</span>
                        </p>
                </th>
                <th colspan="2">
                        <p style="text-align:center;line-height:13px;top:0;margin:0;">
                                <span style="font-size:9px;">CONTRATO DE LOCAÇÃO</span>
                                <br/><br/><br/><br/>
                                <span style="font-family:monospace;">Nº '.$contrato_numero.'</span>
                        </p>
                </th>
        </tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = '
<table>
        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>CONTRATO DE LOCAÇÃO DE AUTOMÓVEL DE PRAZO DETERMINADO</b></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>IDENTIFICAÇÃO DAS PARTES CONTRATANTES</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>LOCADOR</b>: BIKE 46 COM. VAR. LTDA, com endereço na AV. BRASIL, 3861, Juiz de Fora,
                        MG, CEP: 36035-030, inscrito no CNPJ sob o nº 20.481.199/0001.24.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>LOCATÁRIO</b>: '.$locatario['nome'].', residente em '.$locatario['rua'].', '.$locatario['numero'].', '.$locatario['bairro'].' - '.$locatario['cidade'].' - '.$locatario['estado'].', inscrito no CPF sob o nº '.$locatario['documento'].', CNH nº '.$locatario['cnh'].'.
                        As partes acima identificadas têm, entre si, justo e acertado o presente Contrato de Locação de
                        Automóvel de Prazo Determinado, que se regerá pelas cláusulas seguintes e pelas condições
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
                        <b>Cláusula 1ª</b>. O presente contrato tem como OBJETO a locação do veículo
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
                        <b>Cláusula 2ª</b>. O automóvel, objeto deste contrato, será utilizado exclusivamente pelo
                        LOCATÁRIO para serviço PARTICULAR, não sendo permitido o seu uso por terceiros, sob
                        pena de rescisão contratual e o pagamento da multa prevista na Cláusula 5.1. É de total
                        responsabilidade do LOCATÁRIO qualquer dano material ocorrido no veículo, assim como quaisquer infrações
                        de trânsito.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>2.1</b>. O LOCADOR não constituirá nenhum vínculo empregatício caso o LOCATÁRIO se utilize
                        do veículo para fins lucrativos no transporte de terceiros ou de cargas, prevalecendo sempre o
                        objeto deste contrato somente a locação do veículo mencionado pactuada de comum acordo
                        entre as partes. O veículo objeto deste contrato será utilizado exclusivamente para fins
                        lícitos e particulares, permitidos pelos diplomas legais vigentes. Caso ocorra a violação desta
                        cláusula, a responsabilidade civil ou criminal será inteiramente do LOCATÁRIO.
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
                <th colspan="8" style="text-align:center;"><b>DA DEVOLUÇÃO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cláusula 3ª</b>. O LOCATÁRIO deverá devolver o veículo ao locador nas mesmas condições
                        em que estava quando o recebeu, ou seja, em perfeitas condições de uso, respondendo pelos
                        danos ou prejuízos causados.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>3.1</b>. O LOCATÁRIO vistoriou o veículo dado em locação por este instrumento, quando
                        constatou seu perfeito estado de conservação, funcionamento e sem avarias, de acordo com o
                        CHECK LIST em anexo.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>3.2</b>. REPAROS E CONSERVAÇÃO – O LOCATÁRIO recebe o bem em perfeitas condições de
                        limpeza, conservação e funcionamento, obrigando-se a mantê-lo como tal. Para reposição de
                        peças o LOCATÁRIO deve utilizar peças originais e especificadas pela montadora do
                        veículo.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>3.3</b>. VISTORIA – O LOCADOR poderá vistoriar o bem a qualquer tempo, por si ou por preposto,
                        a fim de verificar o cumprimento das obrigações do contrato.
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
                        <b>Cláusula 4ª</b>. A presente locação terá o lapso temporal de validade de '.($prazohoras/24).' dias,
                        iniciando no dia '.$inicio->format('d/m/Y').' às '.$inicio->format('h').'h e terminando no dia '.$devolucao->format('d/m/Y').'  às '.$inicio->format('h').'h,
                        podendo ser prorrogado e tornar-se indeterminado ante prévia comunicação.
                </th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:34px;">
                <th colspan="8" style="text-align:center;"><b>DA RESCISÃO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cláusula 5ª</b>. Caso não ocorra a restituição do veículo ao LOCADOR pelo LOCATÁRIO dentro do período estipulado, este deverá pagar,
                        pelo período em que o detiver em seu poder, o valor que o LOCADOR arbitrar, e responderá por quaisquer danos
                        que o veículo venha a sofrer durante todo o período, ainda que decorrente de caso fortuito ou força maior.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>5.1</b>. O descumprimento de alguma das cláusulas por parte dos contratantes ensejará a
                        rescisão deste instrumento de contrato, e o devido pagamento de multa no valor de R$500,00 (quinhentos reais) pela parte
                        inadimplente.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>5.2</b>. Caso haja atraso no pagamento o LOCADOR poderá reaver a posse do veículo sem
                        qualquer ônus ou consequência por isso.
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
                <th colspan="8" style="text-align:center;"><b>DEVERES DO LOCATÁRIO</b></th>
        </tr>
        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cláusula 6ª</b>. O LOCATÁRIO assume e isenta o LOCADOR a proteção contra danos pessoais,
                        bem como danos que por ventura venha a sofrer ele próprio, pessoa de sua família,
                        pedestres e passageiros que estiverem ocupando o veículo, morte ou lesão corporal de
                        natureza grave, assumirá a demanda oriunda desses eventos na esfera civil e penal em
                        consequência de incêndio, ou dos carros envolvidos em acidente, incidentes no período em
                        que exercer a locação, mesmo que o débito venha a ser constatado, apurado, denunciado ou
                        cobrado posteriormente. Os danos materiais de terceiros em veículos que ultrapassem o limite
                        da proteção contratada pelo LOCADOR, bem como danos morais, danos pessoais derivados
                        do evento danoso, serão de responsabilidade única do LOCATÁRIO.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>6.1</b>. As proteções não constituem seguro, mas um acordo oferecido pelo LOCADOR ao
                        LOCATÁRIO, por meio do qual, o LOCADOR, mediante o pagamento de uma taxa mensal que
                        varia de acordo com a proteção escolhida e o grupo do veículo, através de contrato de adesão
                        feito com “Associação dos proprietários de veículos automotores”.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>6.2</b>. Além do aluguel acima estipulado, o LOCATÁRIO pagará todos os impostos ou quaisquer
                        outras despesas que recaiam ou venham a recair sobre o veículo locado, inclusive franquia,
                        caso venha a utilizar. Os pagamentos dos acessórios serão feitos nos respectivos vencimentos.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>Cláusula 7ª</b>. O LOCATÁRIO assume todos os encargos legais como pagamento de multas,
                        pontos na CNH e penalidades decorrentes de infrações às leis e regulamentos de trânsito,
                        durante o período que estiver de posse do veículo, mesmo que o débito venha a ser
                        constatado, apurado ou cobrado posteriormente.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.1</b>. O LOCATÁRIO assume as despesas de combustível.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.2</b>. O LOCATÁRIO fica responsável pelo pagamento no valor de '.Dinheiro($aluguel['valor_caucao']).' ('.ltrim(extenso($aluguel['valor_caucao']),' ').') em forma de caução para resguardo de acionamento da franquia ou caso haja
                        alguma eventualidade como acidente, roubo/furto ou multas e autuações.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>


        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.3</b>. O LOCATÁRIO obriga-se a fornecer estacionamento para o veículo em condições de
                        segurança, preservação e proteção dos efeitos da natureza ou danos causados por terceiros.
                </th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:center;"></th>
        </tr>

        <tr style="line-height:21px;">
                <th colspan="8" style="text-align:justify;">
                        <b>7.4</b>. O LOCATÁRIO obriga-se a comunicar imediatamente ao LOCADOR ocorrência de
                        acidente, furto, roubou incêndio, além de responsabilizar-se pelas consequências do ocorrido, e
                        a providenciar Boletim de Ocorrência policial ou Laudo Pericial, quando se fizer necessário, no
                        prazo imediato após o evento.
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
                        <b>Cláusula 8ª</b>. Será alugado pelo valor de '.Dinheiro($aluguel['diaria']).' ('.ltrim(extenso($aluguel['diaria']),' ').') por diária.
                        O pagamento será realizado através de deposito bancário (previamente comunicado) ou diretamente ao LOCADOR, no local e prazo acordado entre as partes.
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
                        Para dirimir quaisquer controvérsias oriundas do CONTRATO, as partes elegem o foro da
                        comarca de Juiz de Fora – MG, como o único competente para a solução de questões oriundas
                        do presente acordo, que amigavelmente as partes não puderem resolver, obrigando-se as
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
