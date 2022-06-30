<div class='wrap wrapentrar'>

        <?php
                tituloPagina('entrar');
                EnviandoImg();
        ?>

        <p id='resultentrar' class='retorno'>
                <?php
                        if ( (isset($_GET['u'])) && ($_GET['u'] == 'confirmado') ) {
                                // coloca o background roxo se vier confirmado do email
                                echo '<script> $("#resultentrar").css("background-color", "transparent"); </script>';
                                echo 'E-mail confirmado';
                        } else {
                                // camufla o background se tiver no preset pra entrar padrão
                                echo '<script> $("#resultentrar").css("background-color", "transparent"); </script>';
                                echo 'Faça login para entrar';
                        } // get
                ?>
        </p> <!-- result entrar -->

        <div class='innerwrapentrar'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block:'>
                                <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                          <input style='max-width:100%;min-width:100%;' type='email' placeholder='E-mail' name='email' id='email'>
                                </div>
                                <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                          <input style='max-width:100%;min-width:100%;' type='password' placeholder='Senha' name='pwd' id='pwd'>
                                </div>
                                <?php
                                        MontaBotao('entrar','enviarentrar');
                                ?>
                                <input name='request' id='request' value='<?php echo $_SERVER["REQUEST_URI"] ?? 0; ?>' type='hidden'></input>
                                <div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
                                        <a style='text-decoration:none;' href='<?php echo $dominio ?>/entrar/recuperar-senha/'>
                                                <p class='botaosecundario'>
                                                        nova senha
                                                </p>
                                        </a>
                                </div>
                        </div>
                </div> <!-- container -->
        </div><!--innerwrapentrar-->
</div> <!-- content -->
