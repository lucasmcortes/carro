<?php

        $termoshacker = file(''.$dominio.'/includes/hlist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // declara função pra fazer strpos no array dos termos hacker
        if (!function_exists('strpos_array')) {
        function strpos_array($haystack, $needles) {
                if ( is_array($needles) ) {
                        foreach ($needles as $str) {
                                if ( is_array($str) ) {
                                        $pos = strpos_array($haystack, $str);
                                } else {
                                        $pos = strpos($haystack, $str);
                                }
                                if ($pos !== FALSE) {
                                        return $pos;
                                }
                        } // foreach
                } else {
                        return strpos($haystack, $needles);
                } // if
        } // function strpos_array
        }

        // vê se tem o termos hacker no link que a pessoa acessou coloca o ip dela na blacklist e bloqueia os acessos
        if (strpos_array($visita_link, $termoshacker) == true) {
                // hacker detectado, vê se já tem o ip $blacklisted
                $blacklisted = file(''.$dominio.'/includes/blist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                // add se não tá blacklisted ainda
                if (in_array($_SERVER['REMOTE_ADDR'], $blacklisted)) {
                        // já tem
                } else {
                        // altera como aparece o link na tabela pra indicar quando adicionou aquele ip na blacklist
                        $blacklisted_link = "blacklisted ($visita_link)";
                        $visita_link = $blacklisted_link;

                        $blacklist = fopen(__DIR__ . '/blist.txt', 'a');
                        $blacklist_address = "\n" . $_SERVER['REMOTE_ADDR'];
                        fwrite($blacklist, $blacklist_address);
                        fclose($blacklist);
                } // verificação
        } // if

?>
