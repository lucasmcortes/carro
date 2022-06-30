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

	$imagem = glob(__DIR__.'/../doc/'.$placa.'.*', GLOB_BRACE);
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
								<iframe id='docimg' src='".$dominio."/painel/veiculos/doc/".$imagem."?".rand(1,999)."' style='width:100%;auto;'></iframe>
							";
								MontaBotao('ver pdf','dldoc');
							echo "
								<script>
									$('#dldoc').on('click', function() {
										window.open('".$dominio."/painel/veiculos/doc/".$imagem."','_blank');
									})
								</script>
							";
						} else {
							echo "
								<img class='docimg' style='max-width:100%;max-height:100%;' src='".$dominio."/painel/veiculos/doc/".$imagem."?".rand(1, 999)."'></img>
								<script>
									$('.docimg').on('click', function () {
										$.ajax({
											url: '".$dominio."/includes/biggerdocumento.inc.php',
											success: function(bigpic) {
												$('#vestimenta').html(bigpic);
											},
										});
									});
								</script>
							";
						}
							MontaBotao('atualizar imagem','atualizaimgdoc');
						echo "
							<script>
								$('#atualizaimgdoc').on('click', function () {
									loadVestimenta('".$dominio."/painel/veiculos/novo/includes/atualizaimgdoc.inc.php');
								});
							</script>
							</div>
						";
					} else {
						echo "
						<!-- img_doc_outer_wrap -->
			                        <div id='img_doc_outer_wrap' class='uploadouterwrap'>
			                                <label>Foto da documentação:</label>
			                                <div id='img_doc_wrap' class='uploadwrap'>
			                                        <label id='label_img_doc' for='img_doc' class='upload'>
					                                <img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
					                                <p class='uploadcaption'>
					                                        adicionar imagem
					                                </p>
			                                        </label>
			                                        <input type='file' name='img_doc' id='img_doc' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
			                                        <div style='min-width:100%;max-width:100%;display:inline-block;'>
			                                                <div id='progressBarWrap_doc' class='uploadprogressbar'>
			                                                        <div id='progressBar_doc' class='uploadprogressbarinner'></div>
			                                                        <p id='statusUpload_doc' class='uploadstatusupload'></p>
			                                                </div>
			                                        </div>
			                                </div>


			                                <script>
			                                        img_doc_outer_wrap = $('#img_doc_outer_wrap').html();
			                                        function uploadFile(elemento) {
			                                                file = document.getElementById(elemento).files[0];

			                                                formdata = new FormData();
			                                                formdata.append('img_doc', file);
			                                                formdata.append('uploaded_file_name', file.name);

			                                                ajax = new XMLHttpRequest();
			                                                ajax.upload.addEventListener('progress', progressHandler, false);
			                                                ajax.addEventListener('load', completeHandler, false);
			                                                ajax.addEventListener('error', errorHandler, false);
			                                                ajax.addEventListener('abort', abortHandler, false);

			                                                ajax.open('POST','".$dominio."/painel/veiculos/novo/includes/addimgdoc.inc.php');
			                                                ajax.send(formdata);
			                                        }

			                                        function progressHandler(event) {
			                                                percent = (event.loaded / event.total) * 100;
			                                                $('#progressBar_doc').width(Math.round(percent) + '%');
			                                                document.getElementById('statusUpload_doc').innerHTML = Math.round(percent) + '%';
			                                        }

			                                        function completeHandler(event) {
			                                                document.getElementById('img_doc_wrap').innerHTML = event.target.responseText;

					                                $.ajax({
					                                        url: '".$dominio."/painel/veiculos/novo/includes/salvaimgdoc.inc.php',
					                                        success: function(salvadoc) {
					                                                $('#docimg').attr('src','".$dominio."/painel/veiculos/doc/'+salvadoc+'?".rand(1, 999)."');
					                                        }
					                                });

			                                                $('#remove_img_doc').on('click',function() {
			                                                        $('#img_doc_outer_wrap').html(img_doc_outer_wrap);
			                                                        $.ajax({
			                                                                url: '".$dominio."/painel/veiculos/novo/includes/unsetdoc.inc.php'
			                                                        });
			                                                });
			                                        }

			                                        function errorHandler(event) {
			                                                document.getElementById('img_doc_wrap').innerHTML = 'Upload falhou';
			                                        }

			                                        function abortHandler(event) {
			                                                document.getElementById('img_doc_wrap').innerHTML = 'Upload cancelado';
			                                        }

			                                        $('#img_doc').change(function() {
			                                                elemento = $(this).attr('id');
			                                                uploadFile(elemento);
			                                        });
			                                </script>
			                        </div>
			                        <!-- img_doc_outer_wrap -->
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
