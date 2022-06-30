<?php
        carregaJS();
        tituloPagina('novo veículo');
        EnviandoImg();
?>

<div class='content' id='content'>

        <p id='retorno' class='retorno'>
        </p> <!-- retorno -->

        <div id='id03'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>
                        <!-- img_doc_outer_wrap -->
                        <div id='img_doc_outer_wrap' style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                <div class='labelwrap'>
                                        <label>Foto da documentação</label>
                                        <span class='info infomaior' aria-label='Opcional. Poderá ser incluída após o cadastro na sessão de edição dos dados do veículo.'>i</span>
                                </div>
                                <div id='img_doc_wrap' class='uploadwrap'>
                                        <label id='label_img_doc' for='img_doc' class='upload'>
                                                <img class='uploadicon' src='<?php echo $dominio ?>/img/addimg.png'></img>
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

                                                ajax.open('POST','<?php echo $dominio ?>/painel/veiculos/novo/includes/addimgdoc.inc.php');
                                                ajax.send(formdata);
                                        }

                                        function progressHandler(event) {
                                                percent = (event.loaded / event.total) * 100;
                                                $('#progressBar_doc').width(Math.round(percent) + '%');
                                                document.getElementById('statusUpload_doc').innerHTML = Math.round(percent) + '%';
                                        }

                                        function completeHandler(event) {
                                                document.getElementById('img_doc_wrap').innerHTML = event.target.responseText;
                                                $('#remove_img_doc').on('click',function() {
                                                        $('#img_doc_outer_wrap').html(img_doc_outer_wrap);
                                                        $.ajax({
                                                                url: '<?php echo $dominio ?>/painel/veiculos/novo/includes/unsetdoc.inc.php'
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
                        <!-- img_doc_outer_wrap -->

                        <!-- img_foto_outer_wrap -->
                        <div id='img_foto_outer_wrap' class='uploadouterwrap'>
                                <div class='labelwrap'>
                                        <label>Foto do veículo</label>
                                        <span class='info infomaior' aria-label='Opcional. Poderá ser incluída após o cadastro na sessão de edição dos dados do veículo.'>i</span>
                                </div>
                                <div id='img_foto_wrap' class='uploadwrap'>
                                        <label id='label_img_foto' for='img_foto' class='upload'>
                                                <img class='uploadicon' src='<?php echo $dominio ?>/img/addimg.png'></img>
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

                                                ajax.open('POST','<?php echo $dominio ?>/painel/veiculos/novo/includes/addimgfoto.inc.php');
                                                ajax.send(formdata);
                                        }

                                        function progressHandlerFoto(event) {
                                                percent = (event.loaded / event.total) * 100;
                                                $('#progressBar_foto').width(Math.round(percent) + '%');
                                                document.getElementById('statusUpload_foto').innerHTML = Math.round(percent) + '%';
                                        }

                                        function completeHandlerFoto(event) {
                                                document.getElementById('img_foto_wrap').innerHTML = event.target.responseText;
                                                $('#remove_img_foto').on('click',function() {
                                                        $('#img_foto_outer_wrap').html(img_foto_outer_wrap);
                                                        $.ajax({
                                                                url: '<?php echo $dominio ?>/painel/veiculos/novo/includes/unsetfoto.inc.php'
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
                        <!-- img_foto_outer_wrap -->

                        <?php
                                InputGeral('Modelo', 'modelo', 'modelo', 'text', '100');
                                InputGeral('Marca', 'marca', 'marca', 'text', '100');
                                InputGeral('Ano', 'ano', 'ano', 'number', '100');
                        ?>

                        <div id='completowrap' style='min-width:100%;max-width:100%;margin:3px auto;margin-bottom:7px;display:inline-block;'>
        			<label>Completo</label>
                                <div id='completoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                			<span id='completoinfo' class='info' aria-label='Não' style='float:left;'>
                                                <?php SwitchBox('completoswitch','Sim','Não'); ?>
                			</span>
                                </div>
                        </div>
                        <script>
                                valcompleto = 'N';
                                $('#completoswitch').prop('checked', false);
                                $('#completoswitch').on('change',function() {
                                        if (this.checked) {
                                                valcompleto = 'S';
                                        } else {
                                                valcompleto = 'N';
                                        }
                                        $('#completoinfo').attr('aria-label', (valcompleto=='S') ? 'Sim' : 'Não');
                                });
                        </script>

                        <?php InputGeral('Cor', 'cor', 'cor', 'text', '100'); ?>

                        <div id='categoriawrap' style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                <label>Categoria</label>
                                <div id='categoriainner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                        <div id='categorias'>
                                                <p id='cat_1' class='categorias opcoes'>
                                                        Carro
                                                </p>
                                                <p id='cat_2' class='categorias opcoes'>
                                                        Utilitário
                                                </p>
                                                <p id='cat_3' class='categorias opcoes'>
                                                        Moto
                                                </p>
                                        </div>
                                        <script>
                                                valcategoria = 1;
                                                valportas = 1;
                                                $('.categorias').on('click', function() {
                                                        valportas = 1;
                                                        valcategoria = $(this).attr('id').split('_')[1];
                                                        if ($(this).hasClass('selecionada')) {
                                                                $('.categorias').removeClass('selecionada');
                                                                $('#portaswrap').css('display','none');
                                                                $('#potenciawrap').css('display','none');
                                                                valportas = 1;
                                                                return;
                                                        }
                                                        $('.categorias').removeClass('selecionada');
                                                        $(this).addClass('selecionada');

                                                        if ( (valcategoria!='3') && (valcategoria!='') ) {
                                                                $('#portaswrap').css('display','inline-block');
                                                                $('#potenciawrap').css('display','inline-block');
                                                                valportas = 2;
                                                        } else {
                                                                $('#portaswrap').css('display','none');
                                                                $('#potenciawrap').css('display','none');
                                                                valportas = 1;
                                                        }
                                                });
                                        </script>
                                </div>
                        </div> <!-- categoriawrap -->

                        <div id='portaswrap' style='min-width:100%;max-width:100%;margin:3px auto;margin-bottom:7px;display:none;'>
        			<label>Portas</label>
                                <div id='portasinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                			<span id='portasinfo' class='info' aria-label='Duas' style='float:left;'>
                                                <?php SwitchBox('portasswitch','4','2'); ?>
                			</span>
                                </div>
                        </div>
                        <script>
                                $('#portasswitch').prop('checked', false);
                                $('#portasswitch').on('change',function() {
                                        if (this.checked) {
                                                valportas = 4;
                                        } else {
                                                valportas = 2;
                                        }
                                        $('#portasinfo').attr('aria-label', (valportas==4) ? 'Quatro' : 'Duas');
                                });
                        </script>

                        <?php
                                echo "
                                        <div id='potenciawrap' style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:none;'>
                                                <label>Potência do motor</label>
                                                <select id='potencia' style='max-width:100%;min-width:100%;'>
                                                        <option value=''>-- ESCOLHA --</option>
                                                        <option value='10'>1.0</option>
                                                        <option value='11'>1.1</option>
                                                        <option value='12'>1.2</option>
                                                        <option value='13'>1.3</option>
                                                        <option value='14'>1.4</option>
                                                        <option value='15'>1.5</option>
                                                        <option value='16'>1.6</option>
                                                        <option value='17'>1.7</option>
                                                        <option value='18'>1.8</option>
                                                        <option value='19'>1.9</option>
                                                        <option value='20'>2.0</option>
                                                        <option value='21'>2.1</option>
                                                        <option value='22'>2.2</option>
                                                        <option value='23'>2.3</option>
                                                        <option value='24'>2.4</option>
                                                        <option value='25'>2.5</option>
                                                </select>
                                        </div>
                                ";
                                echo "
                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <label>Placa</label>
                                                <input type='text' placeholder='Placa' maxlength='8' name='placa' id='placa' style='max-width:100%;min-width:100%;'>
                                        </div>
                                ";
                                echo "
                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <label>Chassi</label>
                                                <input type='text' placeholder='Chassi' maxlength='17' name='chassi' id='chassi' style='max-width:100%;min-width:100%;'>
                                        </div>
                                ";
                                echo "
                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <label>Renavam</label>
                                                <input type='text' placeholder='Renavam' maxlength='11' name='renavam' id='renavam' style='max-width:100%;min-width:100%;'>
                                        </div>
                                ";
                                InputGeral('Kilometragem', 'km', 'km', 'number', '100');
                                echo "
                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>

                                                <div class='labelwrap'>
                                                        <label>Múltiplo de KM para notificar revisão</label>
                                                        <span class='info infomaior' aria-label='Opcional. Poderá ser editado após o cadastro na sessão de edição dos dados do veículo.'>i</span>
                                                </div>
                                                <input type='text' placeholder='Padrão: 10.000' maxlength='10' name='revisao' id='revisao' style='max-width:100%;min-width:100%;'>
                                        </div>

                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <label>Observações</label>
                                                <textarea id='observacao' placeholder='Observações' rows='5' style='max-width:100%;min-width:100%;'></textarea>
                                        </div>
                                ";
                                //InputGeral('Senha', 'pwd', 'pwd', 'password', '100');
                        ?>
                        </div>

                        <div style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;'>
                                <?php MontaBotao('adicionar veiculo','enviarveiculo'); ?>
                        </div>

                </div> <!--container -->
        </div><!--id03-->
</div> <!-- content -->

<script>
        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviarveiculo');
                retorno = $('#retorno');
                formulario = $('#id03');

                function EnviarVeiculo() {
                        valmodelo = $('#modelo').val();
                        valmarca = $('#marca').val();
                        valano = $('#ano').val();
                        valcor = $('#cor').val();
                        valkm = $('#km').val() || 0;
                        valrevisao = $('#revisao').val() || 0;
                        valplaca = $('#placa').val();
                        valchassi = $('#chassi').val();
                        valrenavam = $('#renavam').val();
                        valpotencia = $('#potencia').val() || 1;
                        valobservacao = $('#observacao').val();
                        valpwd = $('#pwd').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/veiculos/novo/includes/veiculo.inc.php',
                                data: {
                                        submitveiculo: 1,
                                        marca: valmarca,
                                        modelo: valmodelo,
                                        ano: valano,
                                        completo: valcompleto,
                                        cor: valcor,
                                        placa: valplaca,
                                        chassi: valchassi,
                                        renavam: valrenavam,
                                        km: valkm,
                                        revisao: valrevisao,
                                        categoria: valcategoria,
                                        portas: valportas,
                                        potencia: valpotencia,
                                        observacao: valobservacao,
                                        pwd: valpwd
                                },
                                beforeSend: function(possivel) {
                                        window.scrollTo(0,0);
                                        enviandoimg.css('display', 'block');
                                        formulario.css('opacity', '0');
                                        retorno.css('opacity', '0');
                                },
                                success: function(possivel) {
                                        window.scrollTo(0,0);
        				bordaRosa();
                                        enviandoimg.css('display', 'none');
                                        formulario.css('opacity', '1');
                                        retorno.css('opacity', '1');

                                        retorno.html(possivel);

                                        if ( (possivel.includes('sucesso') == true) || (possivel.includes('atualizados') == true) ) {
                                                formulario.css('display', 'none');
                                                retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
                                        }
                                }
                        });
                }

                enviarform.click(function() {
                        console.log('oi');
                        EnviarVeiculo();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarVeiculo();
                        }
                });
        }); /* document ready */
</script>
