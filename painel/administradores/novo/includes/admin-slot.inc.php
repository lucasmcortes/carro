<?php
        carregaJS();
        tituloPagina('adicionar administrador');
        EnviandoImg();
?>

<div class='content' id='content'>

        <p id='cadastrado' class='retorno'>
        </p> <!-- cadastrado -->

        <div id='id03'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>
                        <?php InputGeral('Nome', 'nome', 'nome', 'text', '100'); ?>

                        <div id='nivelwrap' style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                <label>Nível de acesso</label>
                                <div id='nivelinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                        <div id='niveis'>
                                                <p id='n_1' class='niveis opcoes'>
                                                        Leitura
                                                </p>
                                                <p id='n_2' class='niveis opcoes'>
                                                        Registro
                                                </p>
                                                <p id='n_3' class='niveis opcoes'>
                                                        Modificação
                                                </p>
                                        </div>
                                        <script>
                                                valnivel = 0;
                                                $('.niveis').on('click', function() {
                                                        valnivel = $(this).attr('id').split('_')[1];
                                                        if ($(this).hasClass('selecionada')) {
                                                                $('.niveis').removeClass('selecionada');
                                                                valnivel = 0;
                                                                return;
                                                        }
                                                        $('.niveis').removeClass('selecionada');
                                                        $(this).addClass('selecionada');
                                                });
                                        </script>
                                </div>
                        </div> <!-- nivelwrap -->
                        <?php
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
                                InputGeral('Escolha uma senha', 'pwd', 'pwd', 'password', '100');
                        ?>
                        </div>

                        <div style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;'>
                                <?php MontaBotao('cadastrar','enviarcadastro'); ?>
                        </div>

                </div> <!--container -->
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
                        valcpf = $('#cpf').val();
                        valtelefone = $('#telefone').val();
                        valemail = $('#email').val();
                        valpwd = $('#pwd').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/administradores/novo/includes/admin.inc.php',
                                data: {
                                        submitadmin: 1,
                                        nome: valnome,
                                        nivel: valnivel,
                                        cpf: valcpf,
                                        telefone: valtelefone,
                                        email: valemail,
                                        pwd: valpwd
                                },
                                beforeSend: function(possivel) {
                                        window.scrollTo(0,0);
                                        enviandoimg.css('display', 'block');
                                        formulario.css('opacity', '0');
                                        cadastrando.css('opacity', '0');
                                },
                                success: function(possivel) {
                                        window.scrollTo(0,0);
                                        bordaRosa();

                                        enviandoimg.css('display', 'none');
                                        formulario.css('opacity', '1');
                                        cadastrando.css('opacity', '1');

                                        if (possivel.includes('sucesso') == true) {
                                                formulario.css('display', 'none');
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
