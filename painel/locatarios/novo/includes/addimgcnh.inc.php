<?php

include __DIR__.'/../../../../includes/setup.inc.php';

unset($_SESSION['img_cnh_info_session']);
$uploaded_file_name = $_POST['uploaded_file_name'];

$fileName = $_FILES['img_cnh']['name'];
$fileTmpLoc = $_FILES['img_cnh']['tmp_name'];
$fileType = $_FILES['img_cnh']['type'];
$fileSize = $_FILES['img_cnh']['size'];
$fileErrorMsg = $_FILES['img_cnh']['error'];
if (!$fileTmpLoc) {
        echo 'Erro';
        exit();
}

// NOMES DO ARQUIVO QUE VEM UPLOADED
// o diretório alvo é o próprio diretório onde fica esse aquivo aqui
$target_dir = __DIR__.'/../temp/';

// pega o tipo do arquivo pra colocar a extensão certa
$extensao_img = '.'.str_replace(array('image/','application/'), '', $fileType);

// url completo do arquivo na pasta temporária
// o endereço do do arquivo completo, com o diretório, tmp_name e a extensão
$nome_completo_arquivo = microtime(true).'-'.$uploaded_file_name;
$arquivo_url_completo = $target_dir.$nome_completo_arquivo;

// coloca as informações do arquivo nesse array
$img_cnh_info = array (
    'img_cnh_nome_completo' => $nome_completo_arquivo,
    'img_cnh_extensao' => $extensao_img,
    'img_cnh_mime' => $fileType,
    'img_cnh_path' => $target_dir,
    'img_cnh_url_completo' => $arquivo_url_completo
);
// e na session
$_SESSION['img_cnh_info_session'] = $img_cnh_info;

if (move_uploaded_file($fileTmpLoc, $arquivo_url_completo)) {
        $resultimgimg_cnh = "
        <!-- cnh_img_result_wrap -->
        <div id='cnh_img_result_wrap' style='min-width:100%;max-width:100%;text-align:center;position:relative;'>
                <div style='position:absolute;right:0;top:3%;'>
                        <img id='remove_img_cnh' alt='remover' title='remover' style='width:26px;height:auto;margin:0 auto;cursor:pointer;' src='".$dominio."/img/red-x.svg'></img>
                </div>
                <p style='min-width:100%;max-width:100%;display:inline-block;'>
                Arquivo recebido
        </p>
        <div style='min-width:100%;max-width:100%;display:inline-block;'>
        ";

        if ($extensao_img=='.pdf') {
                $resultimgimg_cnh .= "
                        <iframe id='docimg' src='".$dominio."/painel/locatarios/novo/temp/".$nome_completo_arquivo."' style='width:100%;auto;'></iframe>
                ";
        } else {
                $resultimgimg_cnh .= "
                        <img id='docimg' style='max-width:222px;width:100%;height:auto;' src='".$dominio."/painel/locatarios/novo/temp/".$nome_completo_arquivo."'></img>
                ";
        }

        $resultimgimg_cnh .= "
                </div>
        </div> <!-- cnh_img_result_wrap -->
        ";
} else {
       $resultimgimg_cnh = "
       <!-- cnh_img_result_wrap -->
       <div id='cnh_img_result_wrap' style='min-width:100%;max-width:100%;text-align:center;'>
               <p style='min-width:100%;max-width:100%;display:inline-block;'>
                        Erro no upload.
               </p>
               <p id='remove_img_cnh' style='min-width:100%;max-width:100%;display:inline-block;cursor:pointer;'>
                        Clique aqui para tentar novamente >
               </p>
       </div> <!-- cnh_img_result_wrap -->
       ";
}

echo $resultimgimg_cnh;

?>
