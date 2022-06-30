<?php
        carregaJS();
        tituloPagina('nova manutenção');
        EnviandoImg();

        $listaveiculos = new ConsultaDatabase($uid);
        $listaveiculos = $listaveiculos->ListaVeiculos();
?>

<div class='content' id='content'>

        <p id='retorno' class='retorno'>
        </p> <!-- retorno -->

        <div id='id03'>
                <div class='container'>
                                        <?php
                                                $slideindex = 1;
                                                $slidevid = [];
                                                if ($listaveiculos[0]['vid']==0) {
                                                        VamosComecar();
                                                } else {
                                                        echo "
                                                        <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>
                                                                <label>Veículo</label>
                                                                <div style='min-width:100%;max-width:100%;display:inline-block;padding:5% 0px;padding-top:0;'>
                                                                <div id='slidesveiculo' class='slideshow-wrapper'>
                                                                <div class='slideshow-container' style='position:relative;'>
                                                        ";

                                                        // slide 1
                                                        echo '<!-- slides veiculo -->';
                                                        foreach ($listaveiculos as $veiculos) {
                                                                if ($veiculos['ativo']=='S') {
                                                                        $vid = $veiculos['vid'];
                                                                        $slidevid[$vid] = $slideindex;
                                                                        $slideindex++;

                                                                        $veiculo = new ConsultaDatabase($uid);
                                                                        $veiculo = $veiculo->Veiculo($veiculos['vid']);
                                                                        ($veiculo['limpeza']=='S') ? $limpeza = 'Limpo' : $limpeza = 'Lavar';
                                                                        $categoria = new ConsultaDatabase($uid);
                                                                        $categoria = $categoria->VeiculoCategoria($veiculo['categoria']);
                                                                        $potencia = new Conforto($uid);
                                                                        $potencia = $potencia->Potencia($vid);

                                                                        $disponibilidade_veiculo = new Conforto($uid);
                                                                        $disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($vid);

                                                                        if (key($disponibilidade_veiculo['disponibilidade'])>=0) {
                                                                                // atualiza o array de disponibilidade tirando as datas de antes de hoje
                                                                                while ($disponibilidade_veiculo['disponibilidade'][key($disponibilidade_veiculo['disponibilidade'])]<$agora->format('Y-m-d')) {
                                                                                        unset($disponibilidade_veiculo['disponibilidade'][key($disponibilidade_veiculo['disponibilidade'])]);
                                                                                } // while
                                                                        }

                                                                        $disponibilidade = $disponibilidade_veiculo['status'];

                                                                        echo "
                                                                                <!-- slide -->
                                                                                <div id='v_".$veiculo['vid']."' data-kilometragem='".$veiculo['km']."' class='carouselslides fade slideVeiculo'>
                                                                                        <div class='innerSlideVeiculo'>

                                                                                                <!-- <div class='slideVImg'>
                                                                                                        <img class='slideResImg' src='".$dominio."/img/veiculoslideplaceholder.png'></img>
                                                                                                </div> -->

                                                                                                <div class='slideVDivTxt'>
                                                                                                        <p class='pSlideTitulo'>
                                                                                                                <b>".$veiculo['modelo']." ".$potencia."</b>
                                                                                                                <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$disponibilidade."</b></span>
                                                                                                                <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$limpeza."</b></span>
                                                                                                        </p>
                                                                                                        <p class='pSlideDesc'>
                                                                                                                ".$categoria."
                                                                                                                <br>
                                                                                                                ".$veiculo['placa']."
                                                                                                                <br>
                                                                                                                ".Kilometragem($veiculo['km'])."
                                                                                                        </p>
                                                                                                        <p id='detalhes_v_".$veiculo['vid']."' class='detalhesveiculo botaopretobranco sombraabaixo hoverbranco' style='margin-top:1.8%;'>
                                                                                                                ver detalhes
                                                                                                        </p>
                                                                                                </div>

                                                                                        </div>
                                                                                </div>
                                                                                <!-- slide -->
                                                                        ";
                                                                } // ativo
                                                        } // foreach veiculo
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

                                                                $(".controleslide").on("click", function() {
                                                                        valveiculo=$(".slideVeiculo").not(":hidden").attr("id").split("_")[1];
                                                                        $("#kilometragematual").val($("#v_"+valveiculo+"").data("kilometragem"));
                                                                });
                                                        </script>
                                        </div>
                                        ';
                                        InputGeral('Estabelecimento', 'estabelecimento', 'estabelecimento', 'text', '100');
                                        echo "
                                                <div id='motivowrap' style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                                        <label>Motivo</label>
                                                        <div id='motivoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                                <div id='motivos'>
                                                                        <p id='m_1' class='motivos opcoes'>
                                                                                Oficina
                                                                        </p>
                                                                        <p id='m_2' class='motivos opcoes'>
                                                                                Lavando
                                                                        </p>
                                                                        <p id='m_5' class='motivos opcoes'>
                                                                                Revisão
                                                                        </p>
                                                                        <p id='m_6' class='motivos opcoes'>
                                                                                Pintura
                                                                        </p>
                                                                </div>
                                                                <script>
                                                                        valmotivo = 0;
                                                                        $('.motivos').on('click', function() {
                                                                                valmotivo = $(this).attr('id').split('_')[1];
                                                                                if ($(this).hasClass('selecionada')) {
                                                                                        $('.motivos').removeClass('selecionada');
                                                                                        valmotivo = 0;
                                                                                        return;
                                                                                }
                                                                                $('.motivos').removeClass('selecionada');
                                                                                $(this).addClass('selecionada');
                                                                        });
                                                                </script>
                                                        </div>
                                                </div> <!-- motivowrap -->

                                                ";

                                                InputDatas();

                                                echo "

                                        </div>

                                        <div style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;'>
                                        ";
                                                MontaBotao('adicionar','enviarmanutencao');
                                        echo "
                                        </div>
                                        ";
                                        } // veiculos > 0
                                ?>

                </div> <!--container -->
        </div><!--id03-->
</div> <!-- content -->

<script>

        $('#inicio').keydown(function() {
                return false;
        });
        $('#devolucao').keydown(function() {
                return false;
        });

        $('#inicio').val('<?php echo $agora->format('d/m/Y') ?>');
        $('#inicio').on('click',function () {
                calendarioPop(1,'fundamental',valveiculo);
                // if (valveiculo=='') {
                //         $('#retorno').html('Escolha um veículo');
                //         window.scrollTo(0,0);
                // } else {
                //         $('#retorno').empty();
                //         valveiculo = $('#veiculo').val();
                //         calendarioPop(1,'fundamental',valveiculo);
                // }
        });

        $('#calendarioinicio').on('click',function () {
                calendarioPop(1,'fundamental',valveiculo);
                // if (valveiculo=='') {
                //         $('#retorno').html('Escolha um veículo');
                //         window.scrollTo(0,0);
                // } else {
                //         $('#retorno').empty();
                //         calendarioPop(1,'fundamental',valveiculo);
                // }
        });

        $('#devolucao').val('<?php echo $agora->format('d/m/Y') ?>');
        $('#devolucao').on('click',function () {
                calendarioPop(2,'fundamental',valveiculo);
                // if (valveiculo=='') {
                //         $('#retorno').html('Escolha um veículo');
                //         window.scrollTo(0,0);
                // } else {
                //         $('#retorno').empty();
                //         valveiculo = $('#veiculo').val();
                //         calendarioPop(2,'fundamental',valveiculo);
                // }
        });

        $('#calendariodevolucao').on('click',function () {
                calendarioPop(2,'fundamental',valveiculo);
                // if (valveiculo=='') {
                //         $('#retorno').html('Escolha um veículo');
                //         window.scrollTo(0,0);
                // } else {
                //         $('#retorno').empty();
                //         calendarioPop(2,'fundamental',valveiculo);
                // }
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

        <?php
                if ((isset($_GET['m'])) && (is_numeric($_GET['m'])) ) {
                        echo "
                                $('#motivo').val('".$_GET['m']."');
                        ";
                } // seta o motivo (vindo do vinfo)
        ?>

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

        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviarmanutencao');
                retorno = $('#retorno');
                formulario = $('#id03');

                function EnviarManutencao() {
                        valestabelecimento = $('#estabelecimento').val();
                        valinicio = $('#inicio').val();
                        valdevolucao = $('#devolucao').val();

                        valpwd = $('#pwd').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/manutencoes/novo/includes/manutencao.inc.php',
                                data: {
                                        submitmanutencao: 1,
                                        veiculo: valveiculo,
                                        estabelecimento: valestabelecimento,
                                        motivo: valmotivo,
                                        inicio: valinicio,
                                        devolucao: valdevolucao,
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
                        EnviarManutencao();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarManutencao();
                        }
                });
        }); /* document ready */
</script>
