<?php

include __DIR__.'/../../../../includes/setup.inc.php';
BotaoFecharVestimenta();

echo "
        <!-- img_foto_outer_wrap -->
        <div id='img_foto_outer_wrap' style='max-width:100%;min-width:100%;padding:3%;padding-bottom:0;margin:8% auto;margin-bottom:0;display:inline-block;'>
                <label>Foto do veículo:</label>
                <div id='img_foto_wrap' class='uploadwrap'>
                        <label id='label_img_foto' for='img_foto' class='upload'>
                                <img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
                                <p class='uploadcaption'>
                                        adicionar imagem
                                </p>
                        </label>
                        <input type='file' name='img_foto' id='img_foto' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
                        <div style='min-width:100%;max-width:100%;display:inline-block;'>
                                <div id='progressBarWrap_foto' class='uploadprogressbar'>
                                        <div id='progressBar_foto' class='uploadprogressbarinner'></div>
                                        <p id='statusUpload_foto' class='uploadstatusupload'></p>
                                </div>
                        </div>
                </div>


                <script>
                        img_foto_outer_wrap = $('#img_foto_outer_wrap').html();
                        function uploadFoto(elemento) {
                                file = document.getElementById(elemento).files[0];

                                formdata = new FormData();
                                formdata.append('img_foto', file);
                                formdata.append('uploaded_file_name', file.name);

                                ajax = new XMLHttpRequest();
                                ajax.upload.addEventListener('progress', progressHandlerFoto, false);
                                ajax.addEventListener('load', completeHandlerFoto, false);
                                ajax.addEventListener('error', errorHandlerFoto, false);
                                ajax.addEventListener('abort', abortHandlerFoto, false);

                                ajax.open('POST','".$dominio."/painel/veiculos/novo/includes/addimgfoto.inc.php');
                                ajax.send(formdata);
                        }

                        function progressHandlerFoto(event) {
                                percent = (event.loaded / event.total) * 100;
                                $('#progressBar_foto').width(Math.round(percent) + '%');
                                document.getElementById('statusUpload_foto').innerHTML = Math.round(percent) + '%';
                        }

                        function completeHandlerFoto(event) {
                                document.getElementById('img_foto_wrap').innerHTML = event.target.responseText;

                                $.ajax({
                                        url: '".$dominio."/painel/veiculos/novo/includes/salvaimgfoto.inc.php'
                                });

                                $('#remove_img_foto').on('click',function() {
                                        $('#img_foto_outer_wrap').html(img_foto_outer_wrap);
                                        $.ajax({
                                                url: '".$dominio."/painel/veiculos/novo/includes/unsetfoto.inc.php'
                                        });
                                });
                        }

                        function errorHandlerFoto(event) {
                                document.getElementById('img_foto_wrap').innerHTML = 'Upload falhou';
                        }

                        function abortHandlerFoto(event) {
                                document.getElementById('img_foto_wrap').innerHTML = 'Upload cancelado';
                        }

                        $('#img_foto').change(function() {
                                elemento = $(this).attr('id');
                                uploadFoto(elemento);
                        });
                </script>
        </div>

        <div style='min-width:94%;max-width:94%;display:inline-block;margin:3% auto;'>
        ";
                MontaBotao('voltar','verfoto');
        echo "
        </div>

        <script>
                abreVestimenta();
                $('#fecharvestimenta').on('click', function() {
                        verFoto('".$_SESSION['vid']."');
                });

                $('#verfoto').on('click', function () {
                        $('#fecharvestimenta').trigger('click');
                });
        </script>
        <!-- img_foto_outer_wrap -->
";
?>
