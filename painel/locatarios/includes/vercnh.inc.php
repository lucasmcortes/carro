<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['habilitacao'])) {
	$cnh = $_POST['habilitacao'];
	$_SESSION['cnh'] = $cnh;
	$habilitacao = new ConsultaDatabase($uid);
	$habilitacao = $habilitacao->Habilitacao($cnh);

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($habilitacao['lid']);

	$imagem = glob(__DIR__.'/../cnh/'.$cnh.'.*', GLOB_BRACE);
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
	<?php tituloCarro($locatario['nome']); ?>
	<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:8%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<?php
					if (!empty($imagem)) {
					echo "
						<div style='width:55%;display:inline-block;'>
					";
					if (strpos($imagem,'.pdf')!==false) {
						echo "
							<iframe id='cnhimg' src='".$dominio."/painel/locatarios/cnh/".$imagem."?".rand(1,999)."' style='width:100%;auto;'></iframe>
							<p id='dldoc' style='background-color:var(--preto);padding:3px 5px;border-radius:var(--radius);color:var(--branco);margin-bottom:1.8%;'>ver pdf</p>
							<script>
								$('#dldoc').on('click', function() {
									window.open('".$dominio."/painel/locatarios/cnh/".$imagem."','_blank');
								})
							</script>
						";
					} else {
						echo "
							<img class='cnhimg' style='max-width:100%;max-height:100%;' src='".$dominio."/painel/locatarios/cnh/".$imagem."?".rand(1, 999)."'></img>
							<script>
								$('.cnhimg').on('click', function () {
									$.ajax({
										url: '".$dominio."/includes/biggercnh.inc.php',
										success: function(bigpic) {
											$('#vestimenta').html(bigpic);
										},
									});
								});
							</script>
						";
					}
							MontaBotao('atualizar imagem','atualizaimgcnh');
						echo "
							<script>
								$('#atualizaimgcnh').on('click', function () {
									loadVestimenta('".$dominio."/painel/locatarios/novo/includes/atualizaimgcnh.inc.php');
								});
							</script>
							</div>
						";
					} else {
						echo "
						<!-- img_cnh_outer_wrap -->
	                                        <div id='img_cnh_outer_wrap' class='uploadouterwrap'>
	                                                <label>Foto da CNH:</label>
	                                                <div id='img_cnh_wrap' class='uploadwrap'>
	                                                        <label id='label_img_cnh' for='img_cnh' class='upload'>
									<img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
									<p class='uploadcaption'>
										adicionar imagem
									</p>
	                                                        </label>
	                                                        <input type='file' name='img_cnh' id='img_cnh' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
	                					<div style='min-width:100%;max-width:100%;display:inline-block;'>
	                						<div id='progressBarWrap_cnh' class='uploadprogressbar'>
	                							<div id='progressBar_cnh' class='uploadprogressbarinner'></div>
	                							<p id='statusUpload_cnh' class='uploadstatusupload'></p>
	                						</div>
	                					</div>
	                                                </div>


	                                                <script>
	                                                        img_cnh_outer_wrap = $('#img_cnh_outer_wrap').html();
	                                                        function uploadFile(elemento) {
	                                                                file = document.getElementById(elemento).files[0];

	                                                                formdata = new FormData();
	                                                                formdata.append('img_cnh', file);
	                                                                formdata.append('uploaded_file_name', file.name);

	                                                                ajax = new XMLHttpRequest();
	                        					ajax.upload.addEventListener('progress', progressHandler, false);
	                                                                ajax.addEventListener('load', completeHandler, false);
	                                                                ajax.addEventListener('error', errorHandler, false);
	                                                                ajax.addEventListener('abort', abortHandler, false);

	                                                                ajax.open('POST','".$dominio."/painel/locatarios/novo/includes/addimgcnh.inc.php');
	                                                                ajax.send(formdata);
	                                                        }

	                        				function progressHandler(event) {
	                        					percent = (event.loaded / event.total) * 100;
	                        					$('#progressBar_cnh').width(Math.round(percent) + '%');
	                        					document.getElementById('statusUpload_cnh').innerHTML = Math.round(percent) + '%';
	                        				}

	                                                        function completeHandler(event) {
	                                                                document.getElementById('img_cnh_wrap').innerHTML = event.target.responseText;

									$.ajax({
										url: '".$dominio."/painel/locatarios/novo/includes/salvaimgcnh.inc.php'
									});

	                                                                $('#remove_img_cnh').on('click',function() {
	                                                                        $('#img_cnh_outer_wrap').html(img_cnh_outer_wrap);
	                                                                        $.ajax({
	                                                                                url: '".$dominio."/painel/locatarios/novo/includes/unsetcnh.inc.php'
	                                                                        });
	                                                                });
	                                                        }

	                                                        function errorHandler(event) {
	                                                                document.getElementById('img_cnh_wrap').innerHTML = 'Upload falhou';
	                                                        }

	                                                        function abortHandler(event) {
	                                                                document.getElementById('img_cnh_wrap').innerHTML = 'Upload cancelado';
	                                                        }

	                                                        $('#img_cnh').change(function() {
	                                                                elemento = $(this).attr('id');
	                                                                uploadFile(elemento);
	                                                        });
	                                                </script>
	                                        </div>
	                                        <!-- img_cnh_outer_wrap -->
						";
					} // se existe a imagem da cnh
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
		lid = <?php echo $locatario['lid'] ?>;
		locatarioFundamental(lid);
	});
</script>
