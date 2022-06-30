<?php

include __DIR__.'/../../includes/setup.inc.php';

clearstatcache();
if (is_file($_SESSION['img_logo_info_session']['img_logo_path'].$_SESSION['img_logo_info_session']['img_logo_nome_completo'])) {
        $imagem_location_pra_rename = $_SESSION['img_logo_info_session']['img_logo_url_completo'];
        if ( ($_SESSION['img_logo_info_session']['img_logo_extensao']!='.pdf') && ($_SESSION['img_logo_info_session']['img_logo_extensao']!='.png') ) {
                fit_image_file_to_width($_SESSION['img_logo_info_session']['img_logo_url_completo'], 1080, $_SESSION['img_logo_info_session']['img_logo_mime']);
        }
        $imagem_location_rename = __DIR__.'/../../logo/'.$_SESSION['logo'].$_SESSION['img_logo_info_session']['img_logo_extensao'];
        copy($imagem_location_pra_rename,$imagem_location_rename);

        clearstatcache();
        if (is_file($imagem_location_rename)) {
                // volta o nome da imagem
                echo $imagem_location_rename;
        } // moveu a imagem true

} // file_exists

unset($_SESSION['img_logo_info_session']);

?>
