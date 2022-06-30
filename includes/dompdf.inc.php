<?php

        use Dompdf\Dompdf;
        use Dompdf\Options;

        // Instância de Options
        $options = new Options();
        $options->setChroot(__DIR__);
        $options->setIsRemoteEnabled(true); // habilita inclusão de arquivos remotos
        $options->setIsHtml5ParserEnabled(true);

        // Instância de Dompdf
        $dompdf = new Dompdf($options);

?>
