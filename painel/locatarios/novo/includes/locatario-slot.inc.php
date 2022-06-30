<?php
        carregaJS();
        tituloPagina('novo locatário');
        EnviandoImg();

        // reset a imagem da cnh
        unset($_SESSION['img_cnh_info_session']);
?>

<div class='content' id='content'>

        <p id='retorno' class='retorno'>
        </p> <!-- retorno -->

        <div id='id03'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>
                        <?php
                                InputGeral('Nome', 'nome', 'nome', 'text', '100');
                                echo "
                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <label>CPF</label>
                                                <input type='text' placeholder='Número do CPF' onkeyup=\"maskIt(this,event,'###.###.###-##')\" name='cpf' id='cpf' style='max-width:100%;min-width:100%;'>
                                        </div>
                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <label>Telefone</label>
                                                <input type='text' placeholder='Telefone' onkeyup=\"maskIt(this,event,'(##) #####-####')\" name='telefone' id='telefone' style='max-width:100%;min-width:100%;'>
                                        </div>
                                ";
                                InputGeral('E-mail', 'email', 'email', 'text', '100');

                                echo "
                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <div style='max-width:100%;min-width:100%;margin:0 auto;float:left;'>
                                                        <label>Data de nascimento</label>
                                                        <input style='max-width:100%;min-width:100%;' onkeyup='maskIt(this,event,\"##/##/####\")'  max-length='10' type='text' placeholder='DD/MM/AAAA' name='nascimento' id='nascimento'>
                                                </div>
                                        </div>

                                        <div id='placawrap' style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:none;'>
                                                <div style='max-width:100%;min-width:100%;margin:0 auto;float:left;'>
                                                        <label>Placa associada</label>
                                                        <input style='max-width:100%;min-width:100%;' type='text' placeholder='Placa' name='placa' id='placa'>
                                                </div>
                                        </div>
                                        <script>
                                                valassociado = 'N';
                                                $('#associadoswitch').prop('checked', false);
                                                $('#associadoswitch').on('change',function() {
                                                        if (this.checked) {
                                                                valassociado = 'S';
                                                                $('#placawrap').css('display','inline-block');
                                                        } else {
                                                                valassociado = 'N';
                                                                $('#placawrap').css('display','none');
                                                        }
                                                        $('#associadoinfo').attr('aria-label', (valassociado=='S') ? 'Sim' : 'Não');
                                                });
                                        </script>

                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <div style='max-width:49%;min-width:49%;margin:0 auto;float:left;'>
                                                        <label>CNH</label>
                                                        <input style='max-width:100%;min-width:100%;' onkeyup='maskIt(this,event,\"###########\")'  max-length='11' type='text' placeholder='CNH' name='cnh' id='cnh'>
                                                </div>
                                                <div style='max-width:49%;min-width:49%;margin:0 auto;float:right;'>
                                                        <label>Validade</label>
                                                        <input style='max-width:100%;min-width:100%;' onkeyup='maskIt(this,event,\"##/##/####\")' max-length='10' type='text' placeholder='DD/MM/AAAA' name='validade' id='validade'>
                                                </div>
                                        </div>

                                        <!-- img_cnh_outer_wrap -->
                                        <div id='img_cnh_outer_wrap' class='uploadouterwrap'>
                                                <label>Foto da CNH:</label>
                                                <div id='img_cnh_wrap' class='uploadwrap'>
                                                        <label id='label_img_cnh' for='img_cnh' class='upload'>
                                                                <img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
                                                                <p class='uploadcaption'>
                                                                        adicionar imagem
                                                                </p>
                                                        </label>
                                                        <input type='file' name='img_cnh' id='img_cnh' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
                					<div style='min-width:100%;max-width:100%;display:inline-block;'>
                						<div id='progressBarWrap_cnh' class='uploadprogressbar'>
                							<div id='progressBar_cnh' class='uploadprogressbarinner'></div>
                							<p id='statusUpload_cnh' class='uploadstatusupload'></p>
                						</div>
                					</div>
                                                </div>


                                                <script>
                                                        img_cnh_outer_wrap = $('#img_cnh_outer_wrap').html();
                                                        function uploadFile(elemento) {
                                                                file = document.getElementById(elemento).files[0];

                                                                formdata = new FormData();
                                                                formdata.append('img_cnh', file);
                                                                formdata.append('uploaded_file_name', file.name);

                                                                ajax = new XMLHttpRequest();
                        					ajax.upload.addEventListener('progress', progressHandler, false);
                                                                ajax.addEventListener('load', completeHandler, false);
                                                                ajax.addEventListener('error', errorHandler, false);
                                                                ajax.addEventListener('abort', abortHandler, false);

                                                                ajax.open('POST','".$dominio."/painel/locatarios/novo/includes/addimgcnh.inc.php');
                                                                ajax.send(formdata);
                                                        }

                        				function progressHandler(event) {
                        					percent = (event.loaded / event.total) * 100;
                        					$('#progressBar_cnh').width(Math.round(percent) + '%');
                        					document.getElementById('statusUpload_cnh').innerHTML = Math.round(percent) + '%';
                        				}

                                                        function completeHandler(event) {
                                                                document.getElementById('img_cnh_wrap').innerHTML = event.target.responseText;
                                                                $('#remove_img_cnh').on('click',function() {
                                                                        $('#img_cnh_outer_wrap').html(img_cnh_outer_wrap);
                                                                        $.ajax({
                                                                                url: '".$dominio."/painel/locatarios/novo/includes/unsetcnh.inc.php'
                                                                        });
                                                                });
                                                        }

                                                        function errorHandler(event) {
                                                                document.getElementById('img_cnh_wrap').innerHTML = 'Upload falhou';
                                                        }

                                                        function abortHandler(event) {
                                                                document.getElementById('img_cnh_wrap').innerHTML = 'Upload cancelado';
                                                        }

                                                        $('#img_cnh').change(function() {
                                                                elemento = $(this).attr('id');
                                                                uploadFile(elemento);
                                                        });
                                                </script>
                                        </div>
                                        <!-- img_cnh_outer_wrap -->

                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <div style='max-width:39%;min-width:39%;margin:0 auto;float:left;'>
                                                        <label>CEP</label>
                                                        <input style='max-width:100%;min-width:100%;' onkeyup='maskIt(this,event,\"##.###-###\")' max-length='8' type='text' placeholder='CEP' name='cep' id='cep'>
                                                </div>
                                                <div style='max-width:59%;min-width:59%;margin:0 auto;float:right;'>
                                                        <label>Rua</label>
                                                        <input style='max-width:100%;min-width:100%;' type='text' placeholder='Rua' name='rua' id='rua'>
                                                </div>
                                        </div>

                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <div style='max-width:29%;min-width:29%;margin:0 auto;float:left;'>
                                                        <label>Número</label>
                                                        <input style='max-width:100%;min-width:100%;' type='text' placeholder='Número' name='numero' id='numero'>
                                                </div>
                                                <div style='max-width:69%;min-width:69%;margin:0 auto;float:right;'>
                                                        <label>Bairro</label>
                                                        <input style='max-width:100%;min-width:100%;' type='text' placeholder='Bairro' name='bairro' id='bairro'>
                                                </div>
                                        </div>

                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <div style='max-width:79%;min-width:79%;margin:0 auto;float:left;'>
                                                        <label>Cidade</label>
                                                        <input style='max-width:100%;min-width:100%;' type='text' placeholder='Cidade' name='cidade' id='cidade'>
                                                </div>
                                                <div style='max-width:19%;min-width:19%;margin:0 auto;float:right;'>
                                                        <label>Estado</label>
                                                        <input style='max-width:100%;min-width:100%;' type='text' placeholder='Estado' name='estado' id='estado'>
                                                </div>
                                        </div>

                                        <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                <div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
                                                        <label>Complemento</label>
                                                        <input style='max-width:100%;min-width:100%;' type='text' placeholder='Complemento' name='complemento' id='complemento'>
                                                </div>
                                        </div>
                                ";
                                //InputGeral('Senha', 'pwd', 'pwd', 'password', '100');
                        ?>
                        </div>

                        <div style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;'>
                                <?php MontaBotao('cadastrar','enviarlocatario'); ?>
                        </div>

                </div> <!--container -->
        </div><!--id03-->
</div> <!-- content -->

<script>
        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviarlocatario');
                retorno = $('#retorno');
                formulario = $('#id03');

                function EnviarLocatario() {
                        valnome = $('#nome').val();
                        valcpf = $('#cpf').val();
                        valtelefone = $('#telefone').val();
                        valemail = $('#email').val();
                        valnascimento = $('#nascimento').val();

                        valplaca = $('#placa').val() || 0;
                        valcnh = $('#cnh').val();
                        valvalidade = $('#validade').val();

                        valcep = $('#cep').val();
                        valrua = $('#rua').val();
                        valnumero = $('#numero').val();
                        valbairro = $('#bairro').val();
                        valcidade = $('#cidade').val();
                        valestado = $('#estado').val();
                        valcomplemento = $('#complemento').val();

                        valpwd = $('#pwd').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/locatarios/novo/includes/locatario.inc.php',
                                data: {
                                        submitlocatario: 1,
                                        nome: valnome,
                                        cpf: valcpf,
                                        telefone: valtelefone,
                                        email: valemail,
                                        nascimento: valnascimento,
                                        associado: valassociado,
                                        placa: valplaca,
                                        cnh: valcnh,
                                        validade: valvalidade,
                                        cep: valcep,
                                        rua: valrua,
                                        numero: valnumero,
                                        bairro: valbairro,
                                        cidade: valcidade,
                                        estado: valestado,
                                        complemento: valcomplemento,
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

                                        if (possivel.includes('sucesso') == true) {
                                                formulario.css('display', 'none');
                                                retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
                                        }
                                }
                        });
                }

                enviarform.click(function() {
                        EnviarLocatario();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarLocatario();
                        }
                });
        }); /* document ready */
</script>
