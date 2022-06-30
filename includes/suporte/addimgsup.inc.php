<?php

include __DIR__.'/../setup.inc.php';

unset($_SESSION['img_sup_info_session']);
$uploaded_file_name = $_POST['uploaded_file_name'];

$fileName = $_FILES['img_sup']['name'];
$fileTmpLoc = $_FILES['img_sup']['tmp_name'];
$fileType = $_FILES['img_sup']['type'];
$fileSize = $_FILES['img_sup']['size'];
$fileErrorMsg = $_FILES['img_sup']['error'];
if (!$fileTmpLoc) {
        echo 'Erro';
        exit();
}

// NOMES DO ARQUIVO QUE VEM UPLOADED
// o diretório alvo é o próprio diretório onde fica esse aquivo aqui
$target_dir = __DIR__.'/img/';

// pega o tipo do arquivo pra colocar a extensão certa
$extensao_img = '.'.str_replace(array('image/','application/'), '', $fileType);

// o endereço do do arquivo completo, com o diretório, tmp_name e a extensão
$nome_completo_arquivo = microtime(true).'-'.$uploaded_file_name;
$arquivo_url_completo = $target_dir.$nome_completo_arquivo;

// coloca as informações do arquivo nesse array
$img_sup_info = array (
    'img_sup_nome_completo' => $nome_completo_arquivo,
    'img_sup_extensao' => $extensao_img,
    'img_sup_mime' => $fileType,
    'img_sup_path' => $target_dir,
    'img_sup_url_completo' => $arquivo_url_completo
);
// e na session
$_SESSION['img_sup_info_session'] = $img_sup_info;

if (move_uploaded_file($fileTmpLoc, $arquivo_url_completo)) {

        clearstatcache();
        if (is_file($_SESSION['img_sup_info_session']['img_sup_path'].$_SESSION['img_sup_info_session']['img_sup_nome_completo'])) {
                $imagem_location_pra_rename = $_SESSION['img_sup_info_session']['img_sup_url_completo'];
                if ( ($_SESSION['img_sup_info_session']['img_sup_extensao']!='.pdf') && ($_SESSION['img_sup_info_session']['img_sup_extensao']!='.png') ) {
                        fit_image_file_to_width($_SESSION['img_sup_info_session']['img_sup_url_completo'], 1080, $_SESSION['img_sup_info_session']['img_sup_mime']);
                }
                $imagem_location_rename = __DIR__.'/img/'.$_SESSION['img_sup_info_session']['img_sup_nome_completo'];
                copy($imagem_location_pra_rename,$imagem_location_rename);

                clearstatcache();

        } // file_exists

        $resultimgimg_sup = "
        <!-- sup_img_result_wrap -->
        <div id='sup_img_result_wrap' style='min-width:100%;max-width:100%;text-align:center;position:relative;'>
                <div style='position:absolute;right:0;top:3%;'>
                        <img id='remove_img_sup' alt='remover' title='remover' style='width:26px;height:auto;margin:0 auto;cursor:pointer;' src='".$dominio."/img/red-x.svg'></img>
                </div>
                <p style='min-width:100%;max-width:100%;display:inline-block;'>
                Arquivo recebido
        </p>
        <div style='min-width:100%;max-width:100%;display:inline-block;'>
        ";

        if ($extensao_img=='.pdf') {
                $resultimgimg_sup .= "
                        <iframe id='supimg' src='".$dominio."/includes/suporte/img/".$nome_completo_arquivo."' style='width:100%;auto;'></iframe>
                ";
        } else {
                $resultimgimg_sup .= "
                        <img id='supimg' style='max-width:222px;width:100%;height:auto;' src='".$dominio."/includes/suporte/img/".$nome_completo_arquivo."'></img>
                ";
        }

        $resultimgimg_sup .= "
                </div>
        </div> <!-- sup_img_result_wrap -->
        ";
} else {
       $resultimgimg_sup = "
       <!-- sup_img_result_wrap -->
       <div id='sup_img_result_wrap' style='min-width:100%;max-width:100%;text-align:center;'>
               <p style='min-width:100%;max-width:100%;display:inline-block;'>
                        Erro no upload.
               </p>
               <p id='remove_img_sup' style='min-width:100%;max-width:100%;display:inline-block;cursor:pointer;'>
                        Clique aqui para tentar novamente >
               </p>
       </div> <!-- sup_img_result_wrap -->
       ";
}

$_SESSION['suporte']['img'] = $dominio."/includes/suporte/img/".$nome_completo_arquivo;

unset($_SESSION['img_sup_info_session']);

echo $resultimgimg_sup;

?>
