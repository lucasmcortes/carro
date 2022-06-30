<?php
        carregaJS();
        tituloPagina('novo retorno');
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
                                if ($listaveiculos[0]['vid']==0) {
                                        VamosComecar();
                                } else {
                                        $manutencoesacontecendo = [];
                                        $slideindex = 1;
                                        $slidevid = [];

                                        foreach ($listaveiculos as $veiculos) {
                                                $veiculo = new ConsultaDatabase($uid);
                                                $veiculo = $veiculo->Veiculo($veiculos['vid']);
                                                $vid = $veiculos['vid'];

                                                $potencia = new Conforto($uid);
                                                $potencia = $potencia->Potencia($vid);

                                                ($veiculo['limpeza']=='S') ? $limpeza = 'Limpo' : $limpeza = 'Lavar';

                                                $categoria = new ConsultaDatabase($uid);
                                                $categoria = $categoria->VeiculoCategoria($veiculo['categoria']);// vê os dias que tá alugado depois de ver da manutenção pra sobrescrever no array as datas iguais

                                                $manutencoes = new ConsultaDatabase($uid);
                                                $manutencoes = $manutencoes->VeiculoManutencoes($vid);
                                                if ($manutencoes[0]['mid']!=0) {
                                                        foreach ($manutencoes as $manutencao) {
                                                                $motivo = new ConsultaDatabase($uid);
                                                                $motivo = $motivo->VeiculoMotivo($manutencao['motivo']);
                                                                $retorno = new ConsultaDatabase($uid);
                                                                $retorno = $retorno->Retorno($manutencao['mid']);
                                                                if ($retorno['rid']==0) {
                                                                        $reservamanutencao = new ConsultaDatabase($uid);
                                                                        $reservamanutencao = $reservamanutencao->ManutencaoReserva($manutencao['mid']);
                                                                        if ($reservamanutencao['mreid']!=0) {
                                                                                $manutencaoativa = new ConsultaDatabase($uid);
                                                                                $manutencaoativa = $manutencaoativa->ManutencaoAtivacao($reservamanutencao['mreid']);
                                                                                if ($manutencaoativa['ativa']=='S') {
                                                                                        $manutencoesacontecendo[] = array(
                                                                                                'mid'=>$manutencao['mid'],
                                                                                                'vid'=>$veiculo['vid'],
                                                                                                'categoria'=>$categoria,
                                                                                                'modelo'=>$veiculo['modelo'],
                                                                                                'placa'=>$veiculo['placa'],
                                                                                                'limpeza'=>$limpeza,
                                                                                                'kilometragem'=>$veiculo['km'],
                                                                                                'disponibilidade'=>$motivo,
                                                                                                'motivo'=>$motivo,
                                                                                                'data'=>new DateTime($manutencao['data'])
                                                                                        );
                                                                                } // ta ativa a reserva da manutencao
                                                                        } else {
                                                                                $manutencoesacontecendo[] = array(
                                                                                        'mid'=>$manutencao['mid'],
                                                                                        'vid'=>$veiculo['vid'],
                                                                                        'categoria'=>$categoria,
                                                                                        'modelo'=>$veiculo['modelo'],
                                                                                        'placa'=>$veiculo['placa'],
                                                                                        'limpeza'=>$limpeza,
                                                                                        'kilometragem'=>$veiculo['km'],
                                                                                        'disponibilidade'=>$motivo,
                                                                                        'motivo'=>$motivo,
                                                                                        'data'=>new DateTime($manutencao['data'])
                                                                                );
                                                                        } // se é agendamento
                                                                } // ainda está em manutenção
                                                        } // foreach manutencao
                                                } // mid > 0
                                        } // foreach veiculo

                                        if (count($manutencoesacontecendo)>0) {
                                                echo "
                                                <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>

                                                        <div id='veiculoswrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                                                                <label>Veículo</label>
                                                                <div style='min-width:100%;max-width:100%;display:inline-block;padding:5% 0px;padding-top:0;'>
                                                        <div id='slidesveiculo' class='slideshow-wrapper'>
                                                        <div class='slideshow-container' style='position:relative;'>
                                                ";

                                                // slide 1
                                                echo '<!-- slides veiculo -->';
                                                foreach($manutencoesacontecendo as $emmanutencao) {
                                                        $slidevid[$emmanutencao['vid']] = $slideindex;
                                                        $slideindex++;

                                                        echo "
                                                                <!-- slide -->
                                                                <div id='v_".$emmanutencao['vid']."' data-kilometragem='".$emmanutencao['kilometragem']."' class='carouselslides fade slideVeiculo'>
                                                                        <div class='innerSlideVeiculo'>

                                                                                <!-- <div class='slideVImg'>
                                                                                        <img class='slideResImg' src='".$dominio."/img/veiculoslideplaceholder.png'></img>
                                                                                </div> -->

                                                                                <div class='slideVDivTxt'>
                                                                                        <p class='pSlideTitulo'>
                                                                                                <b>".$emmanutencao['modelo']." ".$potencia."</b>
                                                                                                <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$emmanutencao['disponibilidade']."</b></span>
                                                                                                <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$emmanutencao['limpeza']."</b></span>
                                                                                        </p>
                                                                                        <p class='pSlideDesc'>
                                                                                                ".$emmanutencao['categoria']."
                                                                                                <br>
                                                                                                ".$emmanutencao['placa']."
                                                                                                <br>
                                                                                                ".Kilometragem($emmanutencao['kilometragem'])."
                                                                                        </p>
                                                                                        <p id='detalhes_v_".$emmanutencao['vid']."' class='detalhesveiculo botaopretobranco sombraabaixo hoverbranco' style='margin-top:1.8%;'>
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
                        InputGeral('Kilometragem', 'km', 'km', 'number', '100');
                        echo "
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
                        <script>
                                vallimpeza = 'N';
                                $('#limpezaswitch').prop('checked', false);
                                $('#limpezaswitch').on('change',function() {
                                        if (this.checked) {
                                                vallimpeza = 'S';
                                        } else {
                                                vallimpeza = 'N';
                                        }
                                        $('#limpezainfo').attr('aria-label', (vallimpeza=='S') ? 'Limpo' : 'Lavar');
                                });
                        </script>
                        ";
                        InputGeral('Valor', 'valor', 'valor', 'number', '100');
                        echo "
                                <label>Observação</label>
                                <textarea id='observacao' placeholder='Observação' rows='3'></textarea>

                </div>

                <div style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;'>
                ";
                MontaBotao('disponibilizar','enviarretorno');
                echo "
                </div>
                ";
                                } else {
                                        NenhumRetorno();
                                } // count
                        } // veiculos > 0
                ?>

                </div> <!--container -->
        </div><!--id03-->
</div> <!-- content -->

<script>
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
                enviarform = $('#enviarretorno');
                retorno = $('#retorno');
                formulario = $('#id03');

                function EnviarRetorno() {
                        valkm = $('#km').val();
                        valvalor = $('#valor').val();
                        valobservacao = $('#observacao').val();
                        valpwd = $('#pwd').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/retornos/novo/includes/retorno.inc.php',
                                data: {
                                        submitretorno: 1,
                                        veiculo: valveiculo,
                                        kilometragem: valkm,
                                        limpeza: vallimpeza,
                                        valor: valvalor,
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

                                        if (possivel.includes('sucesso') == true) {
                                                formulario.css('display', 'none');
                                                retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
                                        }
                                }
                        });
                }

                enviarform.click(function() {
                        EnviarRetorno();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarRetorno();
                        }
                });
        }); /* document ready */

        $("#km").val($("#v_"+valveiculo+"").data("kilometragem"));
</script>
