<?php

include __DIR__.'/../../includes/setup.inc.php';
BotaoFecharVestimenta();

echo "
        <!-- img_logo_outer_wrap -->
        <div id='img_logo_outer_wrap' style='max-width:100%;min-width:100%;padding:3%;padding-bottom:0;margin:8% auto;margin-bottom:0;display:inline-block;'>

                <label>Logo da empresa:</label>
                <div id='img_logo_wrap' class='uploadwrap'>
                        <label id='label_img_logo' for='img_logo' class='upload'>
                                <img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
                                <p class='uploadcaption'>
                                        adicionar imagem
                                </p>
                        </label>
                        <input type='file' name='img_logo' id='img_logo' class='plimgupload'  accept='image/jpeg,image/gif,image/png' style='display:none;'>
                        <div style='min-width:100%;max-width:100%;display:inline-block;'>
                                <div id='progressBarWrap_logo' class='uploadprogressbar'>
                                        <div id='progressBar_logo' class='uploadprogressbarinner'></div>
                                        <p id='statusUpload_logo' class='uploadstatusupload'></p>
                                </div>
                        </div>
                </div>

                <script>
                        img_logo_outer_wrap = $('#img_logo_outer_wrap').html();
                        function uploadFile(elemento) {
                                file = document.getElementById(elemento).files[0];

                                formdata = new FormData();
                                formdata.append('img_logo', file);
                                formdata.append('uploaded_file_name', file.name);

                                ajax = new XMLHttpRequest();
                                ajax.upload.addEventListener('progress', progressHandler, false);
                                ajax.addEventListener('load', completeHandler, false);
                                ajax.addEventListener('error', errorHandler, false);
                                ajax.addEventListener('abort', abortHandler, false);

                                ajax.open('POST','".$dominio."/painel/configuracoes/cfigaddimglogo.inc.php');
                                ajax.send(formdata);
                        }

                        function progressHandler(event) {
                                percent = (event.loaded / event.total) * 100;
                                $('#progressBar_logo').width(Math.round(percent) + '%');
                                document.getElementById('statusUpload_logo').innerHTML = Math.round(percent) + '%';
                        }

                        function completeHandler(event) {
                                document.getElementById('img_logo_wrap').innerHTML = event.target.responseText;

                                $.ajax({
                                        url: '".$dominio."/painel/configuracoes/cfigsalvaimglogo.inc.php',
                                        success: function(salvalogo) {
                                                $('#logoimg').attr('src',salvadoc+'?".rand(1, 999)."');
                                        }
                                });

                                $('#remove_img_logo').on('click',function() {
                                        $('#img_logo_outer_wrap').html(img_logo_outer_wrap);
                                        $.ajax({
                                                url: '".$dominio."/painel/configuracoes/cfigunsetlogo.inc.php'
                                        });
                                });
                        }

                        function errorHandler(event) {
                                document.getElementById('img_logo_wrap').innerHTML = 'Upload falhou';
                        }

                        function abortHandler(event) {
                                document.getElementById('img_logo_wrap').innerHTML = 'Upload cancelado';
                        }

                        $('#img_logo').change(function() {
                                elemento = $(this).attr('id');
                                uploadFile(elemento);
                        });
                </script>
        </div>

        <div style='min-width:94%;max-width:94%;display:inline-block;margin:3% auto;'>
        ";
                MontaBotao('fechar','verlogo');
        echo "
        </div>

        <script>
                abreVestimenta();
                $('#verlogo').on('click', function () {
                        window.location.href='".$dominio."/painel/configuracoes/';
                });
        </script>
        <!-- img_logo_outer_wrap -->
";
?>
