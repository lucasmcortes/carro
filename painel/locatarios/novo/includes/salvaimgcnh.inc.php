<?php

include __DIR__.'/../../../../includes/setup.inc.php';

clearstatcache();
if (is_file($_SESSION['img_cnh_info_session']['img_cnh_path'].$_SESSION['img_cnh_info_session']['img_cnh_nome_completo'])) {
        $imagem_location_pra_rename = $_SESSION['img_cnh_info_session']['img_cnh_url_completo'];
        if ( ($_SESSION['img_cnh_info_session']['img_cnh_extensao']!='.pdf') && ($_SESSION['img_cnh_info_session']['img_cnh_extensao']!='.png') ) {
                fit_image_file_to_width($_SESSION['img_cnh_info_session']['img_cnh_url_completo'], 1080, $_SESSION['img_cnh_info_session']['img_cnh_mime']);
        }
        $imagem_location_rename = __DIR__.'/../../cnh/'.$_SESSION['cnh'].$_SESSION['img_cnh_info_session']['img_cnh_extensao'];
        copy($imagem_location_pra_rename,$imagem_location_rename);

        clearstatcache();
        if (is_file($imagem_location_rename)) {
                // deleta imagens temporárias de cnh
                unlinkTemp(__DIR__.'/../temp/');
                // volta o nome da imagem
                echo $imagem_location_rename;
        } // moveu a imagem true

} // file_exists

unset($_SESSION['img_cnh_info_session']);

?>
