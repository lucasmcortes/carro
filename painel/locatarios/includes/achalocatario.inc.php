<div id='locatariowrap' style='min-width:100%;max-width:100%;margin:1.8% auto;'>
        <label>Locatário</label>
        <div id='locatarioinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                <input type='text' id='locatario' placeholder='Locatário'></input>
        </div>
</div> <!-- locatariowrap -->
<div id='locatarioresult' style='min-width:100%;max-width:100%;display:inline-block;'>
        <p id='locatarioresultp' style='min-width:100%;max-width:100%;text-align:center;margin:1.8% auto;'>
        </p>
</div>
<script>
        buscando = 0;
        valplaca = 0;
        typingTimer = '';
        doneTypingInterval = 1200;
        $('#locatario').on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });
        $('#locatario').on('keydown', function () {
                $('#retorno').empty();
                clearTimeout(typingTimer);
        });
        function doneTyping () {
                if ($('#locatario').val()!='') {
                        if (buscando==0) {
                                BuscaLocatario();
                        }

                } else { /* query vazio */
                        $('#retorno').html('Encontre o locatário pelo nome, CPF, CNH, telefone, email ou placa');
                        $('#locatarioresultp').empty();
                        $('#locatarioresultp').css('text-align','center');
                }
        }
        function BuscaLocatario() {
                buscando = 1;
                document.getElementById('locatario').disabled = true;
                $.ajax({
                        type: 'POST',
                        url: '<?php echo $dominio ?>/painel/alugueis/novo/includes/buscalocatario.inc.php',
                        data: { locatario: $('#locatario').val() },
                        beforeSend: function() {
                                $('#locatarioresultp').html('');
                                $('#locatarioresultp').html("<div id='enviando' style='display:inline-block;'><div id='enviandospinner'></div></div>");
                        },
                        success: function(locatario) {
                                buscando = 0;
                                document.getElementById('locatario').disabled = false;
                                $('#locatarioresultp').empty();

                                if (locatario.includes('encontrado')===false) {
                                        $.each(locatario, function(index, lid) {
                                                /* $("#locatarioresultp").append('<span id="lid_' + lid.lid + '" class="opcaolocatario sombraabaixo hoverbranco" style="background-color:var(--preto);color:var(--branco);min-width:100%;max-width:100%;margin:1.2% auto;padding:5% 8%;border-radius:var(--radius);border:1px solid var(--preto);display:inline-block;cursor:pointer;">' + lid.nome + '</span>'); */
                                                $("#locatarioresultp").append(lid.div);
                                        });

                                        $('.opcaolocatario').on('click',function() {
                                                vallocatario = $(this).attr('id').split('_')[1];
                                                $('#locatarioresultp').html($(this).html());
                                                $('#locatariowrap').css('display','none');
                                                $('#locatarioresultp').css('display','inline-block');
                                                $('#locatarioresultp').css('background-color','var(--verde)');
                                                $.ajax({
                                                        type: 'POST',
                                                        url: '<?php echo $dominio ?>/painel/alugueis/novo/includes/infolocatario.inc.php',
                                                        data: { locatario: vallocatario },
                                                        success: function(locatario) {
                                                                $('#retorno').empty();
                                                                $('#locatarioresultp').empty();
                                                                $('#locatarioresultp').css('text-align','left');

                                                                $("#locatarioresult").append('<p id="editar" class="sombraabaixo hoverbranco" style="background-color:var(--preto);color:var(--branco);padding:3% 8%;padding-top:2.6%;border:1px solid var(--preto);border-radius:var(--radius);float:left;cursor:pointer;">editar</p>');
                                                                $("#editar").on("click",function() {
                                                                        locatarioFundamental(vallocatario);
                                                                });
                                                               $("#locatarioresult").append('<p id="trocar" class="sombraabaixo hoverbranco" style="background-color:var(--preto);color:var(--branco);padding:3% 8%;padding-top:2.6%;margin-left:1.8%;border:1px solid var(--preto);border-radius:var(--radius);float:left;cursor:pointer;">buscar novamente</p>');
                                                               $("#trocar").on("click",function() {
                                                                       $('#locatarioresultp').trigger("click");
                                                                       $('#locatario').val('');
                                                                       $('#retorno').html('Encontre o locatário pelo nome, CPF, CNH, telefone, email ou placa');
                                                                       $('#locatarioresultp').empty();
                                                               });

                                                                $("#placa").empty();
                                                                $('#locatarioresultp').html(locatario['resposta']);
                                                                $('#locatarioresultp').css('display','inline-block');
                                                                $('#locatarioresultp').css('background-color','transparent');

                                                                tiraBordaRosa();
                                                                $('#locatarioresultp').html(locatario['resposta']);
                                                                $('#locatariowrap').css('display','none');
                                                                $(this).css('background-color','var(--verde)');
                                                                vallocatario = $('#locatarioresultspan').data('lid');
                                                                $('#aluguelinfo').css('display','inline-block');
                                                                if ($('#locatarioresultspan').data('associado')=='S') {
                                                                        placas = locatario['placas'] == null ? [] : (locatario['placas'] instanceof Array ? locatario['placas'] : [locatario['placas']]);
                                                                        $.each(placas, function(index, placa) {
                                                                                placa_acionamento ='<p id="pid_' + placa.pid + '" class="opcoesplacaaluguel opcoes">' + placa.placa + '<br><span style="font-size:12px;">('+placa.cortesias_disponiveis+' cortesias disponíveis)</span></p>';
                                                                                $("#placas").append(placa_acionamento);
                                                                        });

                                                                        $('.opcoesplacaaluguel').on('click', function() {
                                                                                valplaca = $(this).attr('id').split('_')[1];
                                                                                if ($(this).hasClass('selecionada')) {
                                                                                        valplaca = 0;
                                                                                        $('.opcoesplacaaluguel').removeClass('selecionada');
                                                                                        return;
                                                                                }
                                                                                $('.opcoesplacaaluguel').removeClass('selecionada');
                                                                                $(this).addClass('selecionada');
                                                                        });

                                                                        $('#particularwrap').css('display','inline-block');
                                                                        $('#particular').val('N');
                                                                        $('#acionamentowrap').css('display','inline-block');
                                                                        $('#avisokmlivre').css('display','inline-block');
                                                                        $('#kmmaximawrap').css('display','none');
                                                                        $('#diariawrap').css('display','none');
                                                                } else {
                                                                        valacionamento = 'N';
                                                                        valparticular = 'S';
                                                                        $('#particularwrap').css('display','none');
                                                                        $('#acionamentowrap').css('display','none');
                                                                        $('#diariawrap').css('display','inline-block');
                                                                }

                                                                $('#locatarioresultp').on('click', function () {
                                                                        $('#locatarioresultp').css('background-color','transparent');
                                                                        $('#locatariowrap').css('display','inline-block');
                                                                        $('#editar').remove();
                                                                        $('#trocar').remove();
                                                                        $('#locatario').val('');

                                                                        $('#retorno').html('Encontre o locatário pelo nome, CPF, CNH, telefone, email ou placa');
                                                                        $('#locatarioresultp').empty();
                                                                        $('#locatarioresultp').css('text-align','center');

                                                                        $('#aluguelinfo').css('display','none');
                                                                });
                                                       }
                                                });
                                        });
                                } else {
                                        $('#locatarioresultp').html(locatario);
                                }
                        }
                });
        }
</script>
