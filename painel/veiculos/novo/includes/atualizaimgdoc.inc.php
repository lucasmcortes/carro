<?php

include __DIR__.'/../../../../includes/setup.inc.php';
BotaoFecharVestimenta();

echo "
        <!-- img_doc_outer_wrap -->
        <div id='img_doc_outer_wrap' style='max-width:100%;min-width:100%;padding:3%;padding-bottom:0;margin:8% auto;margin-bottom:0;display:inline-block;'>

                <label>Foto da documentação:</label>
                <div id='img_doc_wrap' class='uploadwrap'>
                        <label id='label_img_doc' for='img_doc' class='upload'>
                                <img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
                                <p class='uploadcaption'>
                                        adicionar imagem
                                </p>
                        </label>
                        <input type='file' name='img_doc' id='img_doc' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
                        <div style='min-width:100%;max-width:100%;display:inline-block;'>
                                <div id='progressBarWrap_doc' class='uploadprogressbar'>
                                        <div id='progressBar_doc' class='uploadprogressbarinner'></div>
                                        <p id='statusUpload_doc' class='uploadstatusupload'></p>
                                </div>
                        </div>
                </div>

                <script>
                        img_doc_outer_wrap = $('#img_doc_outer_wrap').html();
                        function uploadFile(elemento) {
                                file = document.getElementById(elemento).files[0];

                                formdata = new FormData();
                                formdata.append('img_doc', file);
                                formdata.append('uploaded_file_name', file.name);

                                ajax = new XMLHttpRequest();
                                ajax.upload.addEventListener('progress', progressHandler, false);
                                ajax.addEventListener('load', completeHandler, false);
                                ajax.addEventListener('error', errorHandler, false);
                                ajax.addEventListener('abort', abortHandler, false);

                                ajax.open('POST','".$dominio."/painel/veiculos/novo/includes/addimgdoc.inc.php');
                                ajax.send(formdata);
                        }

                        function progressHandler(event) {
                                percent = (event.loaded / event.total) * 100;
                                $('#progressBar_doc').width(Math.round(percent) + '%');
                                document.getElementById('statusUpload_doc').innerHTML = Math.round(percent) + '%';
                        }

                        function completeHandler(event) {
                                document.getElementById('img_doc_wrap').innerHTML = event.target.responseText;

                                $.ajax({
                                        url: '".$dominio."/painel/veiculos/novo/includes/salvaimgdoc.inc.php'
                                });

                                $('#remove_img_doc').on('click',function() {
                                        $('#img_doc_outer_wrap').html(img_doc_outer_wrap);
                                        $.ajax({
                                                url: '".$dominio."/painel/veiculos/novo/includes/unsetdoc.inc.php'
                                        });
                                });
                        }

                        function errorHandler(event) {
                                document.getElementById('img_doc_wrap').innerHTML = 'Upload falhou';
                        }

                        function abortHandler(event) {
                                document.getElementById('img_doc_wrap').innerHTML = 'Upload cancelado';
                        }

                        $('#img_doc').change(function() {
                                elemento = $(this).attr('id');
                                uploadFile(elemento);
                        });
                </script>
        </div>

        <div style='min-width:94%;max-width:94%;display:inline-block;margin:3% auto;'>
        ";
                MontaBotao('voltar','verdoc');
        echo "
        </div>

        <script>
                abreVestimenta();
                $('#fecharvestimenta').on('click', function() {
                        verDoc('".$_SESSION['vid']."');
                });

                $('#verdoc').on('click', function () {
                        $('#fecharvestimenta').trigger('click');
                });
        </script>
        <!-- img_doc_outer_wrap -->
";
?>
