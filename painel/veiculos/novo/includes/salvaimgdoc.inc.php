<?php

include __DIR__.'/../../../../includes/setup.inc.php';

clearstatcache();
if (is_file($_SESSION['img_doc_info_session']['img_doc_path'].$_SESSION['img_doc_info_session']['img_doc_nome_completo'])) {
        $imagem_location_pra_rename = $_SESSION['img_doc_info_session']['img_doc_url_completo'];
        if ( ($_SESSION['img_doc_info_session']['img_doc_extensao']!='.pdf') && ($_SESSION['img_doc_info_session']['img_doc_extensao']!='.png') ) {
                fit_image_file_to_width($_SESSION['img_doc_info_session']['img_doc_url_completo'], 1080, $_SESSION['img_doc_info_session']['img_doc_mime']);
        }
        $imagem_location_rename = __DIR__.'/../../doc/'.$_SESSION['placa'].$_SESSION['img_doc_info_session']['img_doc_extensao'];
        copy($imagem_location_pra_rename,$imagem_location_rename);

        clearstatcache();
        if (is_file($imagem_location_rename)) {
                // deleta imagens temporárias de doc
                //unlinkTemp(__DIR__.'/../temp/');
                // volta o nome da imagem
                echo str_replace(__DIR__.'/../../doc/','',$imagem_location_rename).'?'.rand(0,999);
        } // moveu a imagem true

} // file_exists

unset($_SESSION['img_doc_info_session']);
?>
