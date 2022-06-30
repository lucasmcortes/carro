<?php

        $preco_anual = '990';
        $preco_anual_vista = number_format($preco_anual,2,',','.');
        $validade_plano_anual = date('d/m/Y', strtotime('+1 year'));

        $preco_vital = '3999';
        $preco_vital_vista = number_format($preco_vital,2,',','.');
        $validade_plano_vital = 'eterna';

?>
