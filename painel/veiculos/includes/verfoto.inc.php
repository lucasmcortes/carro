<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['veiculo'])) {
	$vid = $_POST['veiculo'];
	$_SESSION['vid'] = $vid;

	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);
	$placa = $veiculo['placa'];
	$_SESSION['placa'] = $placa;

	$imagem = glob(__DIR__.'/../foto/'.$placa.'.*', GLOB_BRACE);
	if (!empty($imagem)) {
		usort($imagem, fn($a, $b) => filemtime($b) - filemtime($a)); // arquivo mais recente
		$imagem = basename($imagem[0]);
	} else {
		$imagem = '';
	}

} else {
	$lid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloCarro($veiculo['modelo']); ?>
	<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:8%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<?php
					if (!empty($imagem)) {
						echo "
							<div style='width:55%;display:inline-block;'>
						";
						if (strpos($imagem,'.pdf')!==false) {
							echo "
								<iframe id='fotoimg' src='".$dominio."/painel/veiculos/foto/".$imagem."?".rand(1,999)."' style='width:100%;auto;'></iframe>
							";
								MontaBotao('ver pdf','dlfoto');
							echo "
								<script>
									$('#dlfoto').on('click', function() {
										window.open('".$dominio."/painel/veiculos/foto/".$imagem."','_blank');
									})
								</script>
							";
						} else {
							echo "
								<img class='fotoimg' style='max-width:100%;max-height:100%;' src='".$dominio."/painel/veiculos/foto/".$imagem."?".rand(1, 999)."'></img>
								<script>
									$('.fotoimg').on('click', function () {
										$.ajax({
											url: '".$dominio."/includes/biggerfoto.inc.php',
											success: function(bigpic) {
												$('#vestimenta').html(bigpic);
											},
										});
									});
								</script>
							";
						}
							MontaBotao('atualizar imagem','atualizaimgfoto');
						echo "
							<script>
								$('#atualizaimgfoto').on('click', function () {
									loadVestimenta('".$dominio."/painel/veiculos/novo/includes/atualizaimgfoto.inc.php');
								});
							</script>
							</div>
						";
					} else {
						echo "
						  <!-- img_foto_outer_wrap -->
  					        <div id='img_foto_outer_wrap' class='uploadouterwrap'>
  					                <label>Foto do veículo:</label>
  					                <div id='img_foto_wrap' class='uploadwrap'>
  					                        <label id='label_img_foto' for='img_foto' class='upload'>
					                                <img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
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

  					                                ajax.open('POST','".$dominio."/painel/veiculos/novo/includes/addimgfoto.inc.php');
  					                                ajax.send(formdata);
  					                        }

  					                        function progressHandlerFoto(event) {
  					                                percent = (event.loaded / event.total) * 100;
  					                                $('#progressBar_foto').width(Math.round(percent) + '%');
  					                                document.getElementById('statusUpload_foto').innerHTML = Math.round(percent) + '%';
  					                        }

  					                        function completeHandlerFoto(event) {
  					                                document.getElementById('img_foto_wrap').innerHTML = event.target.responseText;

									$.ajax({
										  url: '".$dominio."/painel/veiculos/novo/includes/salvaimgfoto.inc.php',
										  success: function(salvafoto) {
											  $('#fotoimg').attr('src','".$dominio."/painel/veiculos/foto/'+salvafoto+'?".rand(1, 999)."');
										  }
									  });

									  $('#remove_img_foto').on('click',function() {
										  $('#img_foto_outer_wrap').html(img_foto_outer_wrap);
										  $.ajax({
											  url: '".$dominio."/painel/veiculos/novo/includes/unsetfoto.inc.php'
										  });
									  });

  					                                $('#remove_img_foto').on('click',function() {
  					                                        $('#img_foto_outer_wrap').html(img_foto_outer_wrap);
  					                                        $.ajax({
  					                                                url: '".$dominio."/painel/veiculos/novo/includes/unsetfoto.inc.php'
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
						";
					} // se existe a imagem da doc
				?>

			</div>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:5%;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreFundamental();
	$('#voltar').on('click',function() {
		veiculoFundamental(<?php echo $veiculo['vid'] ?>);
	});
</script>
