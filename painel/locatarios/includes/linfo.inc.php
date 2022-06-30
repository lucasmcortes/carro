<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['locatario'])) {
	$lid = $_POST['locatario'];
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

	$telefone = new Conforto($uid);
	$telefone = $telefone->FormatoTelefone($locatario['telefone'],'br');

	$alugueis_ativos = [];
	$listaveiculos = new ConsultaDatabase($uid);
	$listaveiculos = $listaveiculos->ListaVeiculos();
	if ($listaveiculos[0]['vid']!=0)  {
		foreach ($listaveiculos as $veiculo) {
			$atual = new Conforto($uid);
			$atual = $atual->AluguelAtual($veiculo['vid']);
			$aluguel = new ConsultaDatabase($uid);
			$aluguel = $aluguel->AluguelInfo($atual);
			if ($aluguel['lid']==$lid) {
				$alugueis_ativos[] = array(
					'aid'=>$aluguel['aid'],
					'veiculo'=>$veiculo['modelo']
				);
			} // o aluguel atual de algum veículo é desse locatário
		} // foreach
	} // vid >0

} else {
	$lid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloCarro(mb_strimwidth($locatario['nome'], 0, 18, "[...]")); ?>
	<div style='text-align:center;margin:0 auto;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;font-size:13px;'><?php echo $locatario['rua'].', '.$locatario['numero'] ?> </p>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<p style='display:inline-block;font-size:13px;'><?php echo $locatario['bairro'].' - '.$locatario['cidade'].' - '.$locatario['estado'] ?> </p>
		</div>
	</div>

	<div class='listawrap'>


		<?php
			echo "
				<script>
					lid = '".$locatario['lid']."';
				</script>
			";
		?>


		<div id='nomewrap' style='min-width:100%;max-width:100%;'>
			<label>Nome</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='text' placeholder='Nome' name='nome' id='nome' value='<?php echo $locatario['nome'] ?>'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- nomewrap -->

		<div id='telefonewrap' style='min-width:100%;max-width:100%;'>
			<label>Telefone</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='text' placeholder='Telefone' onkeyup='maskIt(this,event,"(##) #####-####")' name='telefone' id='telefone' value='<?php echo $telefone ?>'>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- telefonewrap -->

		<div id='cnhwrap' style='min-width:100%;max-width:100%;'>
			<label>CNH</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' onkeyup='maskIt(this,event,"###########")'  max-length='11' type='text' placeholder='CNH' name='cnh' id='cnh' value='<?php echo $locatario['cnh'] ?>'>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cnhwrap -->

		<div id='validadewrap' style='min-width:100%;max-width:100%;'>
			<label>Validade da CNH</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' onkeyup='maskIt(this,event,"##/##/####")' max-length='10' type='text' placeholder='dd/mm/aaaa' name='validade' id='validade' value='<?php echo $locatario['validade'] ?>'>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- validadewrap -->

		<div id='emailwrap' style='min-width:100%;max-width:100%;'>
			<label>E-mail</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' placeholder='E-mail' name='email' id='email' value='<?php echo $locatario['email'] ?>'>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- emailwrap -->

		<div id='observacaowrap' style='min-width:100%;max-width:100%;'>
			<label>Observação</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<textarea class='wrappedinput' id='observacao' rows='1'><?php echo $locatario['observacao'] ?></textarea>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- observacaowrap -->

	</div> <!-- listawrap -->

	<div style='min-width:100%;max-width:100%;margin-top:3%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<?php
				if (count($alugueis_ativos)>0) {
					foreach ($alugueis_ativos as $aluguel) {
						Icone('veralinfo_'.$aluguel['aid'],'aluguel '.$aluguel['veiculo'],'infoicon');
						echo "
							<script>
								$('#veralinfo_".$aluguel['aid']."').on('click', function() {
									aluguelFundamental(".$aluguel['aid'].",1);
								});
							</script>
						";
					} // foreach alugueis ativos
				} // alugueis ativos > 0
			?>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<?php
				Icone('altend','endereço','altendicon');
				if ($locatario['associado']=='S') {
					Icone('altassociado','editar','editarassociadoicon');
				} // se associado
				Icone('vercnh','habilitação','verhabilitacaoicon');
				Icone('historico','histórico','verhistoricolocatarioicon');
			?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreFundamental();

	$('.posinput').on('click', function() {
		elemento = $(this).siblings('.wrappedinput').attr('id');
		if ($('#'+elemento).hasClass('lista') || $('#'+elemento).hasClass('swap')) {
			velemento = velemento;
		} else {
			velemento = $('#'+elemento).val();
		}
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/locatarios/includes/l'+elemento+'mod.inc.php',
			data: {
				locatario: lid,
				modificacao: velemento
			},
			beforeSend: function() {
				$('#'+elemento).siblings('.preinput').removeClass('modificado');
				$('#'+elemento).siblings('.preinput').addClass('normal');
			},
			success: function(modconfig) {
				$('#'+elemento).siblings('.preinput').html('');
				if (modconfig.includes('sucesso')) {
					if ($('#'+elemento).siblings('.preinput').hasClass('bgrosa')) {
						$('#'+elemento).siblings('.preinput').removeClass('bgrosa');
					}
					$('#'+elemento).css('border', '0');
					$('#'+elemento).closest('.inputouterwrap').css('background-color', 'var(--verdedois)');
					$('#'+elemento).closest('.inputouterwrap').find('*').prop('disabled', 'disabled');
					$('#'+elemento).closest('.inputouterwrap *').css('cursor','not-allowed');
					$('#'+elemento).closest('.inputouterwrap *').css('pointer-events','none');
					$('#'+elemento).siblings('.preinput').removeClass('normal');
					$('#'+elemento).siblings('.preinput').addClass('modificado');
					mostraFooter();

				} else {
					$('#'+elemento).siblings('.preinput').addClass('bgrosa');
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

	$('#altend').on('click', function() {
		lid = '<?php echo $locatario['lid'] ?>';
		altEndereco(lid);
	});

	$('#altassociado').on('click', function() {
		lid = '<?php echo $locatario['lid'] ?>';
		altAssociado(lid);
	});

	$('#vercnh').on('click', function() {
		verCNH('<?php echo $locatario['cnh'] ?>');
	});

	$('#historico').on('click', function() {
		window.location.href=('<?php echo $dominio ?>/painel/locatarios/historico/?lid=<?php echo $locatario['lid'] ?>');
	});

</script>
