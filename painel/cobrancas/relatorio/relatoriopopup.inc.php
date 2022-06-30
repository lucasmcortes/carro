<?php
	require_once __DIR__.'/../../../includes/setup.inc.php';
	BotaoFechar();

	if (!isset($uid)) {
		echo "
			<script>
				$('#fechar').trigger('click');
			</script>
		";
	} else {
		$veiculos = new ConsultaDatabase($uid);
		$veiculos = $veiculos->ListaVeiculos();
		if ($veiculos[0]['vid']!=0) {
			$cadastros = [];
			foreach ($veiculos as $veiculo) {
				$cadastros[] = $veiculo['data'];
			} // foreach veiculo
			// pega as datas de cadastro e vê a mais antiga pra ser o primeiro mês disponível pra fazer o relatório
			sort($cadastros);
			$anominimo = new DateTime($cadastros[0]);
		} else {
			echo "
				Nenhum veículo cadastrado.
			";
		} // vid != 0;
		$anominimo = new DateTime($veiculo['data']);

		$inicioperiodo = $_GET['de']??$agora->format('m/Y');
		$inicioperiodo = explode('/',$inicioperiodo);
		$anoinicioperiodo = $inicioperiodo[1];
		$anoinicioperiodo = $inicioperiodo??$agora->format('Y');
		($anoinicioperiodo<$anominimo->format('Y')) ? $anoinicioperiodo = $anominimo->format('Y') : (($anoinicioperiodo==$agora->format('Y')) ? $anoinicioperiodo = $agora->format('Y') : $anoinicioperiodo = $anoinicioperiodo[1]);

		$mesinicioperiodo = $inicioperiodo[0];

		$string_data_inicial_periodo = $anoinicioperiodo.'-'.$mesinicioperiodo.'-01';
		$data_desejada_periodo = new DateTime($string_data_inicial_periodo);
		($data_desejada_periodo<$anominimo) ? $string_data_inicial_periodo = $anoinicioperiodo.'-'.$anominimo->format('m').'-01' : $string_data_inicial_periodo = $string_data_inicial_periodo;
		($data_desejada_periodo>$agora) ? $string_data_inicial_periodo = $anoinicioperiodo.'-'.$agora->format('m').'-01' : $string_data_inicial_periodo = $string_data_inicial_periodo;
		$data_inicial_periodo = new DateTime($string_data_inicial_periodo);

		$devolucaoperiodo = $_GET['ate']??$agora->format('m/Y');
		$devolucaoperiodo = explode('/',$devolucaoperiodo);
		$anodevolucaoperiodo = $devolucaoperiodo[1];
		$anodevolucaoperiodo = $anodevolucaoperiodo??$agora->format('Y');
		($anodevolucaoperiodo<$anominimo->format('Y')) ? $anodevolucaoperiodo = $anominimo->format('Y') : (($anodevolucaoperiodo>$agora->format('Y')) ? $anodevolucaoperiodo = $agora->format('Y') : $anodevolucaoperiodo = $anodevolucaoperiodo);

		$mesdevolucaoperiodo = $devolucaoperiodo[0];

		$string_data_devolucao_periodo = $anodevolucaoperiodo.'-'.$mesdevolucaoperiodo.'-01';
		$data_desejada_periodo = new DateTime($string_data_devolucao_periodo);
		($data_desejada_periodo<$anominimo) ? $string_data_devolucao_periodo = $anodevolucaoperiodo.'-'.$anominimo->format('m').'-01' : $string_data_devolucao_periodo = $string_data_devolucao_periodo;
		($data_desejada_periodo>$agora) ? $string_data_devolucao_periodo = $anoinicioperiodo.'-'.$mesinicioperiodo.'-01' : $string_data_devolucao_periodo = $string_data_devolucao_periodo;
		($data_desejada_periodo<$anoinicioperiodo) ? $string_data_devolucao_periodo = $anodevolucaoperiodo.'-'.$anoinicioperiodo.'-01' : $string_data_devolucao_periodo = $string_data_devolucao_periodo;
		$data_devolucao_periodo = new DateTime($string_data_devolucao_periodo);
		$data_devolucao_periodo = $data_devolucao_periodo->modify('last day of this month');

		if ($data_inicial_periodo>$data_devolucao_periodo) {
			$data_devolucao_periodo= new DateTime($string_data_inicial_periodo);
		} // conserta data
	}
?>

<!-- items -->
<div class='items'>

	<?php
		tituloPagina('relatório');
		EnviandoImg();
	?>

	<div id='resultado' style='text-align:center;margin:0 auto;'>

		<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:5%;'>
			<p id='resultadotexto' style='margin:0 auto;'>
				Escolha o mês
			</p>
		</div>

		<div class='relatorio' style='margin:5% auto;margin-top:3%;'>
			<div style='display:inline-block;'> <!-- de -->

				<div style='display:inline-block;'> <!-- data definida-->
					<div style='display:inline-block;'>
						<select id='mesiniciorelatorio' class='escolhaperiodo' style='max-width:100%;min-width:120px;'>
							<option value='01'>Janeiro</option>
							<option value='02'>Fevereiro</option>
							<option value='03'>Março</option>
							<option value='04'>Abril</option>
							<option value='05'>Maio</option>
							<option value='06'>Junho</option>
							<option value='07'>Julho</option>
							<option value='08'>Agosto</option>
							<option value='09'>Setembro</option>
							<option value='10'>Outubro</option>
							<option value='11'>Novembro</option>
							<option value='12'>Dezembro</option>
						</select>
						<script>
							$('#mesiniciorelatorio').val('<?php echo $data_inicial_periodo->format('m') ?>');
						</script>
					</div>

					<div style='display:inline-block;'>
						<select id='anoiniciorelatorio' class='escolhaperiodo' style='max-width:100%;min-width:120px;'>
							<?php
								$anos = 0;
								for ($anoinicioperiodo=$anominimo->format('Y');$anominimo<=$agora;$anominimo->modify('+1 year')) {
									echo "<option value='".$anoinicioperiodo."'>".$anoinicioperiodo."</option>";
									$anos++;
								} // for
								echo "<option value='".$agora->format('Y')."'>".$agora->format('Y')."</option>";
								$anominimo->modify('-'.$anos.' years');
							?>
						</select>
						<script>
							$('#anoiniciorelatorio').val('<?php echo $data_inicial_periodo->format('Y') ?>');
						</script>
					</div>

				</div> <!-- data definida -->

			</div> <!-- de -->
		</div>
		<!-- relatorio -->

		<p id='aviso' class='aviso sombraabaixo'></p>

		<div id='avisowrap' style='min-width:100%;max-width:100%;display:inline-block;'>
			<p class='aviso sombraabaixo'>
				Será gerado o arquivo .xlsx em uma nova aba com o relatório de aluguéis para associados devolvidos no período especificado que tenham contido diárias de cortesia ou tenham sido gerados por um acionamento
			</p>
			<p class='aviso sombraabaixo'>
				Após a criação do arquivo, ele será baixado automaticamente para o seu dispositivo
			</p>
		</div>

		<div style='min-width:100%;max-width:100%;display:inline-block;'>
			<?php MontaBotao('gerar relatório','gerarxlsx'); ?>
		</div>

	</div>
</div>
<!-- items -->

<script>
	abreFundamental();
	function Contagem() {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/cobrancas/relatorio/includes/contagem.inc.php',
			data: {
				mes: $('#mesiniciorelatorio').val(),
				ano: $('#anoiniciorelatorio').val()
			},
			beforeSend: function () {
				$('#aviso').html("<div id='enviando' style='display:inline-block;'><div id='enviandospinner'></div></div>");
			},
			success: function(contagem) {
				$('#aviso').html(contagem+' entrada(s) para o período');
				entradas = contagem;
			}
		});
	}
	Contagem();
	$('.escolhaperiodo').on('change',function () {
		Contagem();
	});

	$('#gerarxlsx').on('click',function () {
		if (entradas>0) {
			mes = $('#mesiniciorelatorio').val();
			ano = $('#anoiniciorelatorio').val();
			window.open(
				'<?php echo $dominio ?>/painel/cobrancas/relatorio/?mes='+mes+'&ano='+ano,
				'_blank'
			);
		} else {
			$('#aviso').html('Nenhum registro encontrado para o período especificado.');
		}
	});
</script>
