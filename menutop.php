<?php

        echo "
                <!-- menutopwrap -->
                <div id='menutopwrap'>
                        <div id='menutopinnerwrap'>
                        <div class='inicioanchor' style='display:flex;margin:3%;cursor:pointer;'>
                                <img src='".$dominio."/img/logo.png'></img>
                                <p style='margin:auto;font-size:13px;'>ophanim</p>
                        </div>
                        <div class='opcoestopwrap'>
        ";

        if (!isset($_SESSION['l_id'])) {
                echo "
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/cadastro'>Começar</a>
                        </div>
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/contato'>Contato</a>
                        </div>
                ";
        } else {
                echo "
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/painel'>Painel</a>
                        </div>
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/minhaconta'>Minha Conta</a>
                        </div>
                ";
        }

        echo "
                        </div>
                        <div class='flexflex'></div>
        ";

        if (isset($_SESSION['l_id'])) {
                echo "
                <div class='buttontopwraplogado'>
                        <div id='infotopwrap'>
                                <div id='infotop'>
                                        <p style='display:inline-block;vertical-align:middle;'>Olá, ".NomeCliente($_SESSION['l_nome'])."!</p>
                                </div>
                        </div>
                ";
        } else {
                echo "
                        <div class='buttontopwrap'>
                                <div id='top_areacliente'>
                                        <img id='top_areacliente' src='".$dominio."/img/user.png' style='max-width:21px;'></img>
                                </div>
                ";
        }

        echo "
                </div>
                        <!-- buttonwrap -->

                                <div id='abremenusuperior'>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                </div>
                        </div>
        ";
        if (isset($_SESSION['l_id'])) {
                echo "
                        <div class='flexbreak'></div>
                        ";
        } // logado
        echo "

                        <div id='banneraviso' class='banneraviso'>
                                <button id='fechabanneraviso' class='fechabanneraviso' aria-label='fechar' tabindex='0'>✕</button>
                                <div>
                                        <p id='banneravisomsg'></p>
                                </div>
                        </div>

                </div>
                </div>
                <!-- menutopwrap -->

                <script>

                        $('#top_areacliente').on('click', function () {
                                loadFundamental('".$dominio."/entrar/includes/entrarfundamental.inc.php');
                        });

                        $('#top_logout').on('click', function () {
                                window.location.href='".$dominio."/entrar/logout'
                        });
        ";

        if (!isset($_SESSION['l_id'])) {
                echo "

                        $('.inicioanchor').on('click', function() {
                                window.location.href = '".$dominio."';
                        });

                        window.addEventListener('load', function() {
                                $('#abremenusuperior').on('click', function() {
                                        if ($('#superior').css('left')!='0px') {
                                                $('#fechar').trigger('click');
                                                $('#fecharvestimenta').trigger('click');
                                                $('#fechacarrinho').trigger('click');
                                                $('#fechabanneraviso').trigger('click');
                                                loadSuperior('".$dominio."/menusuperior.php');
                                                $(this).addClass('open');
                                        } else if ($('#superior').css('left')=='0px') {
                                                $('#fecharsuperior').trigger('click');
                                                $(this).removeClass('open');
                                        }
                                });
                        });
                ";
        } else {
                echo "

                        $('.inicioanchor').on('click', function() {
                                window.location.href = '".$dominio."/painel';
                        });

                        window.addEventListener('load', function() {
                                $('#abremenusuperior').on('click', function() {
                                        if ($('#superior').css('left')!='0px') {
                                                $('#fechar').trigger('click');
                                                $('#fecharvestimenta').trigger('click');
                                                $('#fechacarrinho').trigger('click');
                                                $('#fechabanneraviso').trigger('click');
                                                loadSuperior('".$dominio."/menusuperiorsistema.php');
                                                $(this).addClass('open');
                                        } else if ($('#superior').css('left')=='0px') {
                                                $('#fecharsuperior').trigger('click');
                                                $(this).removeClass('open');
                                        }
                                });
                        });
                ";

        } // menusuperior

        echo "
                        $(document).ready(function(cms) {
                                $(document).click(function(cms) {
                                        if ($(cms.target).closest('.fundamental').attr('id')==='fundamental') return;
                                        if ($(cms.target).closest('.iconesuporte').attr('id')==='msgsuporteicon') return;
                                        if ($('#superior').css('left')=='0px') {
                                                if (cms.pageX>141) {
                                                        $('#fechar').trigger('click');
                                                        $('#fecharvestimenta').trigger('click');
                                                        $('#fecharsuperior').trigger('click');
                                                        $('#fechabanneraviso').trigger('click');
                                                        $('#abremenusuperior').removeClass('open');
                                                }
                                        }
                                });
                        });

                        $('.mostracarrinho').on('click', function() {
                                abreCarrinho();
                        });

                        $('.opcoestop').on('click',function() {
                                window.location.href = $(this).find('a').attr('href');
                        });

                </script>
        ";

        if (isset($_SESSION['l_id'])) {
                echo '
                        <div id="msgsuporteicon" class="iconesuporte">
                                <img class="iconemenu" style="max-width:26px;display:inline-block;" src="'.$dominio.'/img/msgsuporteicon.png"></img>
                                <p class="suporte">suporte</p>
                        </div>
                        <script>
                                $("#msgsuporteicon").on("click", function() {
                                        loadFundamental("'.$dominio.'/includes/suporte/suportepopup.inc.php");
                                });
                        </script>
                ';
        } // isset uid

?>
