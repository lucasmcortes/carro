<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
require_once __DIR__.'/../../../includes/cep.inc.php';
carregaJS();
BotaoFechar();

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

} else {
	$lid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php
		tituloCarro($locatario['nome']);
		EnviandoImg();
	?>

	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p id='retorno' class='retorno'>
	        </p> <!-- retorno -->
		<div id='altendwrap' style='min-width:100%;max-width:100%;display:inline-block;'>
			<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
				<div style='max-width:39%;min-width:39%;margin:0 auto;float:left;'>
					<label>CEP</label>
					<input style='max-width:100%;min-width:100%;' onkeyup='maskIt(this,event,"##.###-###")' max-length='8' type='text' placeholder='CEP' name='cep' id='cep'>
				</div>
				<div style='max-width:59%;min-width:59%;margin:0 auto;float:right;'>
					<label>Rua</label>
					<input style='max-width:100%;min-width:100%;' type='text' placeholder='Rua' name='rua' id='rua'>
				</div>
			</div>

			<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
				<div style='max-width:29%;min-width:29%;margin:0 auto;float:left;'>
					<label>Número</label>
					<input style='max-width:100%;min-width:100%;' type='text' placeholder='Número' name='numero' id='numero'>
				</div>
				<div style='max-width:69%;min-width:69%;margin:0 auto;float:right;'>
					<label>Bairro</label>
					<input style='max-width:100%;min-width:100%;' type='text' placeholder='Bairro' name='bairro' id='bairro'>
				</div>
			</div>

			<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
				<div style='max-width:79%;min-width:79%;margin:0 auto;float:left;'>
					<label>Cidade</label>
					<input style='max-width:100%;min-width:100%;' type='text' placeholder='Cidade' name='cidade' id='cidade'>
				</div>
				<div style='max-width:19%;min-width:19%;margin:0 auto;float:right;'>
					<label>Estado</label>
					<input style='max-width:100%;min-width:100%;' type='text' placeholder='Estado' name='estado' id='estado'>
				</div>
			</div>

			<div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
				<div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
					<label>Complemento</label>
					<input style='max-width:100%;min-width:100%;' type='text' placeholder='Complemento' name='complemento' id='complemento'>
				</div>
			</div>

			<?php InputGeral('Senha','pwd','pwd','password','100'); ?>

			<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:1.8%;'>
				<?php MontaBotao('salvar endereço','enviaraltend'); ?>
			</div>

		</div> <!-- altend wrap -->

		<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:1.8%;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreFundamental();

	$('#enviaraltend').on('click',function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviaraltend');
                retorno = $('#retorno');
                formulario = $('#altendwrap');

		lid = <?php echo $locatario['lid'] ?>;
		valcep = $('#cep').val();
		valrua = $('#rua').val();
		valnumero = $('#numero').val();
		valbairro = $('#bairro').val();
		valcidade = $('#cidade').val();
		valestado = $('#estado').val();
		valcomplemento = $('#complemento').val();
		valpwd = $('#pwd').val();
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/locatarios/includes/altendmod.inc.php',
			data: {
				locatario: lid,
				cep: valcep,
				rua: valrua,
				numero: valnumero,
				bairro: valbairro,
				cidade: valcidade,
				estado: valestado,
				complemento: valcomplemento,
				pwd: valpwd
			},
			beforeSend: function(altendmod) {
				window.scrollTo(0,0);
				enviandoimg.css('display', 'block');
				formulario.css('display', 'none');
				retorno.css('display', 'none');
			},
			success: function(altendmod) {
				window.scrollTo(0,0);
				bordaRosa();
				enviandoimg.css('display', 'none');
				formulario.css('display', 'inline-block');
				retorno.css('display', 'inline-block');

				retorno.html(altendmod);

				if (altendmod.includes('sucesso') == true) {
					formulario.remove();
					retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					setTimeout(function() {
						$('#voltar').trigger('click');
					},1234);
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

	$('#voltar').on('click',function() {
		lid = <?php echo $locatario['lid'] ?>;
		locatarioFundamental(lid);
	});
</script>
