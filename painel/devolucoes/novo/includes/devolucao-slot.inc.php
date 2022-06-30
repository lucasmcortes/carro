<?php
        tituloPagina('devolução');
        EnviandoImg();

        $listaveiculos = new ConsultaDatabase($uid);
        $listaveiculos = $listaveiculos->ListaVeiculos();
?>

<div class='content' id='content'>

        <p id='retorno' class='retorno'>
        </p> <!-- retorno -->

        <?php ?>

        <div id='id03'>

                                <?php
                                if ($listaveiculos[0]['vid']==0) {
                                        NenhumRegistro();
                                } else {
                                                                $alugados = [];
                                                                $slideindex = 1;
                                                                $slidevid = [];

                                                                // slide 1
                                                                echo '<!-- slides veiculo -->';
                                                                foreach ($listaveiculos as $veiculos) {
                                                                        $veiculo = new ConsultaDatabase($uid);
                                                                        $veiculo = $veiculo->Veiculo($veiculos['vid']);
                                                                        $vid = $veiculos['vid'];

                                                                        $potencia = new Conforto($uid);
                                                                	$potencia = $potencia->Potencia($vid);

                                                                        ($veiculo['limpeza']=='S') ? $limpeza = 'Limpo' : $limpeza = 'Lavar';

                                                                        $categoria = new ConsultaDatabase($uid);
                                                                        $categoria = $categoria->VeiculoCategoria($veiculo['categoria']);// vê os dias que tá alugado depois de ver da manutenção pra sobrescrever no array as datas iguais

                                                                        $agendamento_pro_veiculo[$vid]['reserva'] = [];
                                                                        $agendamento_pro_veiculo[$vid]['aluguel'] = [];
                                                                        $agendamento_pro_veiculo[$vid]['manutencao'] = [];

                                                                        $alugueisveiculo = new ConsultaDatabase($uid);
                                                                        $alugueisveiculo = $alugueisveiculo->ListaAlugueisVeiculo($vid);
                                                                        foreach ($alugueisveiculo as $aluguel) {
                                                                                if ($aluguel['aid']!=0) {
                                                                                        $consultaaluguel = new ConsultaDatabase($uid);
                                                                                        $consultaaluguel = $consultaaluguel->AluguelInfo($aluguel['aid']);

                                                                                        $reserva = new ConsultaDatabase($uid);
                                                                                        $reserva = $reserva->Reserva($aluguel['aid']);
                                                                                        if ($reserva['reid']!=0) {
                                                                                                $ativacao = new ConsultaDatabase($uid);
                                                                                                $ativacao = $ativacao->Ativacao($reserva['reid']);
                                                                                                if ($ativacao['ativa']=='S') {
                                                                                                        $devolucao = new ConsultaDatabase($uid);
                                                                                                        $devolucao = $devolucao->Devolucao($reserva['aid']);
                                                                                                        if ($devolucao['deid']==0) {
                                                                                                                $agendamento_pro_veiculo[$vid]['reserva'][] = array(
                                                                                                                        'aid'=>$reserva['aid'],
                                                                                                                        'inicio'=>new DateTime($reserva['inicio']),
                                                                                                                        'devolucao'=>new DateTime($reserva['devolucao'])
                                                                                                                );
                                                                                                        } // sem devolver ainda
                                                                                                } // reserva ativa
                                                                                        } else {
                                                                                                $devolucao = new ConsultaDatabase($uid);
                                                                                                $devolucao = $devolucao->Devolucao($aluguel['aid']);
                                                                                                if ($devolucao['deid']==0) {
                                                                                                        $agendamento_pro_veiculo[$vid]['aluguel'][] = array(
                                                                                                                'aid'=>$consultaaluguel['aid'],
                                                                                                                'inicio'=>new DateTime($consultaaluguel['inicio']),
                                                                                                                'devolucao'=>new DateTime($consultaaluguel['devolucao'])
                                                                                                        );
                                                                                                } // sem devolver ainda
                                                                                        } // é reserva
                                                                                } // se tem aluguel
                                                                        } // foreach listaalugueisveiculo

                                                                        $manutencoes = new ConsultaDatabase($uid);
                                                                        $manutencoes = $manutencoes->VeiculoManutencoes($vid);
                                                                        if ($manutencoes[0]['mid']!=0) {
                                                                                foreach ($manutencoes as $manutencao) {
                                                                                        $retorno = new ConsultaDatabase($uid);
                                                                                        $retorno = $retorno->Retorno($manutencao['mid']);
                                                                                        if ($retorno['rid']==0) {
                                                                                                $motivo = new ConsultaDatabase($uid);
                                                                                                $motivo = $motivo->VeiculoMotivo($manutencao['motivo']);
                                                                                                $agendamento_pro_veiculo[$vid]['manutencao'][] = array(
                                                                                                        'mid'=>$manutencao['mid'],
                                                                                                        'motivo'=>$motivo,
                                                                                                        'data'=>new DateTime($manutencao['data'])
                                                                                                );
                                                                                        } // ainda está em manutenção
                                                                                } // foreach manutencao
                                                                        } // mid > 0

                                                                        krsort($agendamento_pro_veiculo[$vid]['reserva']);
                                                                        foreach ($agendamento_pro_veiculo[$vid]['reserva'] as $reservaVeiculo) {
                                                                                if ($reservaVeiculo['inicio']->format('Y-m-d H:i')<=$agora->format("Y-m-d H:i")) {
                                                                                        // reserva acontecendo
                                                                                        $disponibilidade = 'Alugado';
                                                                                        $alugados[$veiculo['modelo']] = array(
                                                                                                'aid'=>$reservaVeiculo['aid'],
                                                                                                'vid'=>$veiculo['vid'],
                                                                                                'categoria'=>$categoria,
                                                                                                'modelo'=>$veiculo['modelo'],
                                                                                                'placa'=>$veiculo['placa'],
                                                                                                'limpeza'=>$limpeza,
                                                                                                'kilometragem'=>$veiculo['km'],
                                                                                                'disponibilidade'=>$disponibilidade
                                                                                        );
                                                                                } else {
                                                                                        // reserva pra depois
                                                                                        $disponibilidade = 'Reservado';
                                                                                } // inicio antes de agora
                                                                        } // foreach agendamento

                                                                        foreach ($agendamento_pro_veiculo[$vid]['aluguel'] as $aluguelVeiculo) {
                                                                                if ($aluguelVeiculo['inicio']->format('Y-m-d H:i')<=$agora->format("Y-m-d H:i")) {
                                                                                        // aluguel acontecendo
                                                                                        $disponibilidade = 'Alugado';
                                                                                        $alugados[$veiculo['modelo']] = array(
                                                                                                'aid'=>$aluguelVeiculo['aid'],
                                                                                                'vid'=>$veiculo['vid'],
                                                                                                'categoria'=>$categoria,
                                                                                                'modelo'=>$veiculo['modelo'],
                                                                                                'placa'=>$veiculo['placa'],
                                                                                                'limpeza'=>$limpeza,
                                                                                                'kilometragem'=>$veiculo['km'],
                                                                                                'disponibilidade'=>$disponibilidade
                                                                                        );
                                                                                } // inicio antes de agora
                                                                        } // foreach agendamento

                                                                        foreach ($agendamento_pro_veiculo[$vid]['manutencao'] as $manutencaoVeiculo) {
                                                                                // manutenção acontecendo
                                                                                $motivo = new ConsultaDatabase($uid);
                                                                                $motivo = $motivo->VeiculoMotivo($manutencao['motivo']);
                                                                                $disponibilidade = $motivo;
                                                                        } // foreach agendamento
                                                                } // foreach veiculo

                                                                if (count($alugados)>0) {
                                                                        echo "
                                                                        <div class='container'>
                                                                                <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>
                                                                        <div id='veiculoswrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                                                                <label>Veículo</label>
                                                                                <div style='min-width:100%;max-width:100%;display:inline-block;padding:5% 0px;padding-top:0;'>

                                                                                <div id='slidesveiculo' class='slideshow-wrapper'>
                                                                                <div class='slideshow-container' style='position:relative;'>
                                                                        ";
                                                                        foreach($alugados as $alugado) {
                                                                                $slidevid[$alugado['vid']] = $slideindex;
                                                                                $slideindex++;

                                                                                echo "
                                                                                        <!-- slide -->
                                                                                        <div id='v_".$alugado['vid']."' data-kilometragem='".$alugado['kilometragem']."' class='carouselslides fade slideVeiculo'>
                                                                                                <div class='innerSlideVeiculo'>

                                                                                                        <!-- <div class='slideVImg'>
                                                                                                                <img class='slideResImg' src='".$dominio."/img/veiculoslideplaceholder.png'></img>
                                                                                                        </div> -->

                                                                                                        <div class='slideVDivTxt'>
                                                                                                                <p class='pSlideTitulo'>
                                                                                                                        <b>".$alugado['modelo']." ".$potencia."</b>
                                                                                                                        <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$alugado['disponibilidade']."</b></span>
                                                                                                                        <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$alugado['limpeza']."</b></span>
                                                                                                                </p>
                                                                                                                <p class='pSlideDesc'>
                                                                                                                        ".$alugado['categoria']."
                                                                                                                        <br>
                                                                                                                        ".$alugado['placa']."
                                                                                                                        <br>
                                                                                                                        ".Kilometragem($alugado['kilometragem'])."
                                                                                                                </p>
                                                                                                                <p id='detalhes_v_".$alugado['vid']."' class='detalhesveiculo botaopretobranco sombraabaixo hoverbranco' style='margin-top:1.8%;'>
                                                                                                                        ver detalhes
                                                                                                                </p>
                                                                                                        </div>

                                                                                                </div>
                                                                                        </div>
                                                                                        <!-- slide -->
                                                                                ";
                                                                        } // foreach alugados
                                                                        echo '
                                                                                <div style="min-width:100%;max-width:100%;margin:0 auto;">
                                                                                        <a class="prev controleslide" onclick="plusSlides(-1)"></a>
                                                                                        <a class="next controleslide" onclick="plusSlides(1)"></a>
                                                                                </div>
                                                                                </div> <!-- container -->
                                                                        ';

                                                                        // echo '
                                                                        //         <div id="bolinhascontainer">
                                                                        //                 <div>
                                                                        // ';
                                                                        //                 for ($sv=1;$sv<=$slidestotal;$sv++) {
                                                                        //                         echo '
                                                                        //                                 <span class="bolinhaselector" onclick="currentSlide('.$sv.')"></span>
                                                                        //                         ';
                                                                        //                 }
                                                                        // echo '
                                                                        //                 </div>
                                                                        //         </div>
                                                                        // ';

                                                                        echo '
                                                                                </div> <!-- wrapper -->
                                                                        <!-- slides veiculo -->
                                                                        <script>
                                                                                var slideIndex = '.$slideindex.';
                                                                                showSlides(slideIndex);

                                                                                function plusSlides(n) {
                                                                                        showSlides(slideIndex += n);
                                                                                }

                                                                                function currentSlide(n) {
                                                                                        showSlides(slideIndex = n);
                                                                                }

                                                                                function showSlides(n) {
                                                                                        var i;
                                                                                        var slides = document.getElementsByClassName("carouselslides");
                                                                                        var bolinhaselectors = document.getElementsByClassName("bolinhaselector");
                                                                                        if (n > slides.length) {slideIndex = 1}
                                                                                        if (n < 1) {slideIndex = slides.length}
                                                                                        for (i = 0; i < slides.length; i++) {
                                                                                                slides[i].style.display = "none";
                                                                                        }

                                                                                        slides[slideIndex-1].style.display = "block";
                                                                                }
                                                                        ';

                                                                        if ( (isset($_GET['v'])) && (is_numeric($_GET['v'])) && (array_key_exists($_GET['v'],$slidevid)) ) {
                                                                                $slideindex = $slidevid[$_GET['v']];
                                                                                echo 'valveiculo='.$_GET['v'].';';
                                                                        } else {
                                                                                $slideindex = 1;
                                                                                echo 'valveiculo='.array_keys($slidevid)[0].';';
                                                                        }

                                                                        echo '
                                                                                currentSlide('.$slideindex.');
                                                                                $("#km").val($("#v_"+valveiculo+"").data("kilometragem"));

                                                                                $(".controleslide").on("click", function() {
                                                                                        valveiculo=$(".slideVeiculo").not(":hidden").attr("id").split("_")[1];
                                                                                        $("#km").val($("#v_"+valveiculo+"").data("kilometragem"));
                                                                                });
                                                                        </script>
                                                        </div>
                                                </div> <!-- veiculowrap -->
                                                ';

                                                echo "

                                                <div id='kilometragemwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                                        <label>Kilometragem</label>
                        				<input id='km' type='number' placeholder='Kilometragem'></input>
                                		</div> <!-- kilometragemwrap -->

                                                <div id='limpezawrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                			<label>Limpeza</label>
                                                        <div id='limpezainner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                        			<span id='limpezainfo' class='info' aria-label='Lavar' style='float:left;'>
                                                ";
                                                SwitchBox('limpezaswitch','Limpo','Lavar');
                                                echo "
                                        			</span>
                                                        </div>
                                                </div>
                                                <div id='limpezatipowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                                        <label>Tipo de limpeza</label>
                                                        <div id='limpezatipoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                                <div id='limpezatipos'>
                                                                        <p id='l_1' class='limpezatipos opcoes'>
                                                                                <b>Limpeza executiva</b>
                                                                                <br>
                                                                                <span>+".Dinheiro($configuracoes['preco_le'])."</span>
                                                                        </p>
                                                                        <p id='l_2' class='limpezatipos opcoes'>
                                                                                <b>Limpeza completa</b>
                                                                                <br>
                                                                                <span>+".Dinheiro($configuracoes['preco_lc'])."</span>
                                                                        </p>
                                                                        <p id='l_3' class='limpezatipos opcoes'>
                                                                                <b>Limpeza completa com motor</b>
                                                                                <br>
                                                                                <span>+".Dinheiro($configuracoes['preco_lm'])."</span>
                                                                        </p>
                                                                </div>
                                                                <script>
                                                                        vallimpezatipo = 0;
                                                                        $('.limpezatipos').on('click', function() {
                                                                                vallimpezatipo = $(this).attr('id').split('_')[1];
                                                                                if ($(this).hasClass('selecionada')) {
                                                                                        $('.limpezatipos').removeClass('selecionada');
                                                                                        $('#portaswrap').css('display','none');
                                                                                        $('#potenciawrap').css('display','none');
                                                                                        vallimpezatipo = 0;
                                                                                        return;
                                                                                }
                                                                                $('.limpezatipos').removeClass('selecionada');
                                                                                $(this).addClass('selecionada');
                                                                        });
                                                                </script>
                                                        </div>
                                		</div> <!-- limpezatipowrap -->
                                                <script>
                                                        vallimpeza = 'N';
                                                        $('#limpezaswitch').prop('checked', false);
                                                        $('#limpezaswitch').on('change',function() {
                                                                if (this.checked) {
                                                                        vallimpeza = 'S';
                                                                        $('#limpezatipowrap').css('display','none');
                                                                        vallimpezatipo = 0
                                                                } else {
                                                                        vallimpeza = 'N';
                                                                        $('#limpezatipowrap').css('display','inline-block');
                                                                }
                                                                $('#limpezainfo').attr('aria-label', (vallimpeza=='S') ? 'Limpo' : 'Lavar');
                                                        });
                                                </script>

                                                <div id='valoradicionalwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                                        <label>Valor adicional</label>
                        				<input id='valoradicional' type='number' placeholder='Valor adicional'></input>
                                		</div> <!-- valoradicionalwrap -->

                                                <div id='descricaovaloradicionalwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                                        <label>Descrição do valor adicional</label>
                                                        <textarea id='descricaodovaloradicional' rows='2' placeholder='Descrição do valor adicional'></textarea>
                                		</div> <!-- descricaovaloradicionalwrap -->

                                        </div>

                                        <div style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;'>
                                        ";
                                        MontaBotao('devolver','enviardevolucao');
                                        echo "
                                        </div>

                                </div> <!--container -->
                                ";
                        } else {
                                NenhumaDevolucao();
                        } //alugados > 0

                } // veiculos > 0
                ?>


        </div><!--id03-->

        <div id='adicionarpagamentowrap' style='min-width:100%;max-width:100%;display:none;'>
                <p id='adicionarpagamento' class='painelbutton'>
                        adicionar pagamento
                </p>
        </div>

</div> <!-- content -->

<script>
        $('#vinfo').on('click', function() {
                if (valveiculo=='') {
                        $('#retorno').html('Escolha um veículo');
                        window.scrollTo(0,0);
                } else {
                        $('#retorno').empty();
                        valveiculo = $('#veiculo').val();
                        veiculoFundamental(valveiculo);
                }
        });

        $('.detalhesveiculo').on('click', function() {
                if (valveiculo=='') {
                        $('#retorno').html('Escolha um veículo');
                        window.scrollTo(0,0);
                } else {
                        $('#retorno').empty();
                        valveiculo = $(this).attr('id').split('_')[2];
                        veiculoFundamental(valveiculo);
                }
        });

        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviardevolucao');
                retorno = $('#retorno');
                formulario = $('#id03');

                function EnviarDevolucao() {
                        valvaloradicional = $('#valoradicional').val();
                        valdescricaovaloradicional = $('#descricaodovaloradicional').val();
                        valkm = $('#km').val();
                        valpwd = $('#pwd').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/devolucoes/novo/includes/devolucao.inc.php',
                                data: {
                                        submitdevolucao: 1,
                                        veiculo: valveiculo,
                                        limpeza: vallimpeza,
                                        limpezatipo: vallimpezatipo,
                                        valoradicional: valvaloradicional,
                                        descricao: valdescricaovaloradicional,
                                        kilometragem: valkm,
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

                                        if (possivel.includes('confirmar')==true) {
                                                formulario.css('display', 'none');
                                        }

                                        retorno.html(possivel);
                                }
                        });
                }

                enviarform.click(function() {
                        EnviarDevolucao();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarDevolucao();
                        }
                });
        }); /* document ready */

        $("#km").val($("#v_"+valveiculo+"").data("kilometragem"));
</script>
