<div style='min-width:90%;max-width:90%;margin:0 auto;'>
        <?php
                tituloPagina('cadastro');
                EnviandoImg();
        ?>

        <p id='cadastrado' class='retorno'>Preencha todos os campos para realizar o seu cadastro</p> <!-- result entrar -->

        <div id='id03'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block:'>
                                <div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
                                        <label>Nome</label>
                                        <input type='text' placeholder='Seu nome completo' name='nome' id='nome'>
                                </div>

                                <div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
                                        <label>Data de nascimento</label>
                                        <input onkeyup='maskIt(this,event,"##/##/####")' type='text' placeholder='dd/mm/aaaa' name='nascimento' id='nascimento'>
                                </div>

                                <div style='display:flex;gap:2%;'>
                                        <div style='flex:1;'>
                                                <label>CPF</label>
                                                <input onkeyup='maskIt(this,event,"###.###.###-##")' type='text' placeholder='999.999.999-99' name='cpf' id='cpf'>
                                        </div>
                                        <div style='flex:1;'>
                                                <label>Celular</label>
                                                <input onkeyup='maskIt(this,event,"(##) ###-###-###")' type='text' placeholder='(99) 999-999-999' name='telefone' id='telefone'>
                                        </div>
                                </div>

                                <!-- endereco wrap -->
                                <div id='enderecowrap'>
                                        <div style='display:flex;gap:2%;'>
                                                <div style='flex:3;'>
                                                        <label>CEP</label>
                                                        <input onkeyup='maskIt(this,event,"##.###-###")' max-length='8' type='text' placeholder='99.999-99' name='cep' id='cep'>
                                                </div>
                                                <div style='flex:8;'>
                                                        <label>Rua</label>
                                                        <input type='text' placeholder='Rua' name='rua' id='rua'>
                                                </div>
                                        </div>

                                        <div style='display:flex;gap:2%;'>
                                                <div style='flex:1;'>
                                                        <label>Nº</label>
                                                        <input type='text' placeholder='Número' name='numero' id='numero'>
                                                </div>
                                                <div style='flex:3;'>
                                                        <label>Bairro</label>
                                                        <input type='text' placeholder='Bairro' name='bairro' id='bairro'>
                                                </div>
                                        </div>

                                        <div style='display:flex;gap:2%;'>
                                                <div style='flex:3;'>
                                                        <label>Cidade</label>
                                                        <input type='text' placeholder='Cidade' name='cidade' id='cidade'>
                                                </div>
                                                <div style='flex:1;'>
                                                        <label>UF</label>
                                                        <input type='text' placeholder='Estado' name='estado' id='estado'>
                                                </div>
                                        </div>

                                        <div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
                                                <label>Complemento</label>
                                                <input type='text' placeholder='Complemento' name='complemento' id='complemento'>
                                        </div>
                                </div>
                                <!-- endereco wrap -->

                                <div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
                                        <label>E-mail</label>
                                        <input type='email' placeholder='E-mail que será o seu login' name='email' id='email'>
                                </div>
                                <div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
                                        <label>Senha</label>
                                        <input type='password' placeholder='Escolha uma senha' name='pwd' id='pwd'>
                                </div>

                        </div>

                        <?php
                                MontaBotao('cadastrar','enviarcadastro');
                        ?>
                </div> <!-- container -->
        </div><!--id03-->
</div> <!-- content -->

<script>
        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviarcadastro');
                cadastrando = $('#cadastrado');
                formulario = $('#id03');

                function EnviarCadastro() {
                        valnome = $('#nome').val();
                        valnascimento = $('#nascimento').val();
                        valcpf = $('#cpf').val();
                        valtelefone = $('#telefone').val();
                        valemail = $('#email').val();
                        valpwd = $('#pwd').val();

                        valcep = $('#cep').val();
                        valrua = $('#rua').val();
                        valnumero = $('#numero').val();
                        valbairro = $('#bairro').val();
                        valcidade = $('#cidade').val();
                        valestado = $('#estado').val();
                        valcomplemento = $('#complemento').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/cadastro/includes/cadastro.inc.php',
                                data: {
                                        submitcadastro: 1,
                                        nome: valnome,
                                        nascimento: valnascimento,
                                        nivel: 3,
                                        cpf: valcpf,
                                        telefone: valtelefone,
                                        email: valemail,
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
                                        formulario.css('display', 'none');
                                        cadastrando.css('display', 'none');
                                },
                                success: function(possivel) {
                                        window.scrollTo(0,0);

                                        enviandoimg.css('display', 'none');
                                        formulario.css('display', 'block');
                                        cadastrando.css('display', 'block');

                                        if (possivel.includes('sucesso') == true) {
                                                formulario.css('display', 'none');
                                                gtag('event', 'conversion', {'send_to': 'AW-985745368/nMLcCM3Ky44DENiPhdYD'});
                                        }

                                        cadastrando.empty();
                                        cadastrando.html(possivel);
                                }
                        });
                }

                enviarform.click(function() {
                        EnviarCadastro();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarCadastro();
                        }
                });
        }); /* document ready */
</script>
