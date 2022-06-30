<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_REQUEST['veiculo'])) {
	$vid = $_REQUEST['veiculo'];
	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);

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

	$id = [];
	$id['aluguel'] = 0;
	$id['reserva'] = 0;
	$id['manutencao'] = 0;
	$id['agendamentomanutencao'] = 0;

	if ($disponibilidade=='Alugado') {
		$id['aluguel'] = $disponibilidade_veiculo['disponibilidade'][$agora->format('Y-m-d')]['id'];
	} // disponibilidade aluguel

	// pega aid da próxima reserva pro icone
	$reservado = new ConsultaDatabase($uid);
	$reservado = $reservado->Reservados($vid);
	if ($reservado[0]!=0) {
		foreach ($reservado as $reserva) {
			if ($reserva!=$id['aluguel']) {
				$reservaativa = new ConsultaDatabase($uid);
				$reservaativa = $reservaativa->ReservaAtiva($reserva);
				if ($reservaativa['ativa']=='S') {
					$id['reserva'] = $reserva;
				} // ativa
				break;
			} // quando é a reserva mais recente difernete do aluguel
		} // each reserva
	} // reservado != 0

	$manutencaoatual = new ConsultaDatabase($uid);
	$manutencaoatual = $manutencaoatual->ManutencoesAtivas($vid);
	if ($manutencaoatual[0]['mid']!=0) {
		$consultaretorno = new ConsultaDatabase($uid);
		$consultaretorno = $consultaretorno->Retorno($manutencaoatual[0]['mid']);
		if ($consultaretorno['rid']==0) {
			$id['manutencao'] = $manutencaoatual[0]['mid'];
			$inicio_manutencao = new DateTime($manutencaoatual[0]['inicio']);
			$devolucao_manutencao = new DateTime($manutencaoatual[0]['devolucao']);

			//MREID ATUAL
			$mreidatual = new ConsultaDatabase($uid);
			$mreidatual = $mreidatual->ManutencaoReserva($manutencaoatual[0]['mid']);
			if ($mreidatual['mreid']!=0) {
				$id['agendamentomanutencao'] = $mreidatual['mreid'];
			} else {
				$id['agendamentomanutencao'] = 0;
			}

		} // se ainda não retornou
	} // id do agendamento

} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php
		tituloCarro($veiculo['modelo'].' '.$potencia);
		Icone('relatorioveiculo','relatório','relatorioicon');
		Icone('editarveiculo','editar','editaricon');
		Icone('vercalendario','calendário','calendarioicon');
		Icone('verdoc','documento','verdocicon');
		Icone('verfoto','foto','verfotoicon');
	?>
	<div style='text-align:center;margin:0 auto;'>
		<div style='min-width:100%;max-width:100%:display:inline-block;'>
			<p style='display:inline-block;'><b><?php echo $disponibilidade ?></b></p>
		</div>
		<div style='min-width:100%;max-width:100%:display:inline-block;'>
			<p style='display:inline-block;'><?php echo $categoria ?>, </p>
			<?php
				if (!empty($veiculo['marca'])) {
					//($veiculo['marca']==0) ? $marca = 0 : $marca = $veiculo['marca'];
					echo "<p style='display:inline-block;'>".$veiculo['marca'].", </p>";
				} //
			?>
			<p style='display:inline-block;'><?php echo $veiculo['cor'] ?>, </p>
			<p style='display:inline-block;'><?php echo $veiculo['ano'] ?> </p>
		</div>
		<div style='min-width:100%;max-width:100%:display:inline-block;'>
			<p style='display:inline-block;'><?php echo $veiculo['placa'] ?>, </p>
			<p style='display:inline-block;'><?php echo Kilometragem($veiculo['km']) ?></p>
		</div>
		<?php
			$revisao_dez_mil_km = new Conforto($uid);
			$revisao_dez_mil_km = $revisao_dez_mil_km->RevisaoDezKm($veiculo['vid']);
			if ($revisao_dez_mil_km!=0) {
				echo "<div style='min-width:100%;max-width:100%:display:inline-block;'>";
				echo $revisao_dez_mil_km;
				echo "</div>";
			} // fazer revisão dos 10k
		?>
	</div>

	<div class='listawrap' style='margin-top:2px;'>
		<div id='limpezawrap' style='min-width:100%;max-width:100%;margin:3px auto;margin-top:5%;'>
			<!-- <label>Limpeza</label> -->
			<span id='limpezainfo' class='info' aria-label='<?php echo ($veiculo['limpeza']=='S') ? 'Limpo' : 'Lavar'; ?>'>
				<?php SwitchBox('limpezaswitch','Limpo','Lavar'); ?>
			</span>
		</div>
		<script>
			$('#limpezaswitch').prop('checked', <?php echo ($veiculo['limpeza']=='S') ? 'true' : 'false'; ?>);
			$('#limpezaswitch').on('change',function() {
				if (this.checked) {
					vlimpeza = 'S';
				} else {
					vlimpeza = 'N';
				}
				$('#limpezainfo').attr('aria-label', (vlimpeza=='S') ? 'Limpo' : 'Lavar');
				$.ajax({
					type: 'POST',
					url: '<?php echo $dominio ?>/painel/veiculos/includes/vlimpezamod.inc.php',
					data: {
						veiculo: '<?php echo $veiculo['vid'] ?>',
						modificacao: vlimpeza
					},
					success: function(modlimpeza) {
						if (modlimpeza.includes('sucesso')) {
							$('#limpezaswitch').prop('checked', (vlimpeza=='S') ? true : false);
							$('#limpezainfo').attr('aria-label', (vlimpeza=='S') ? 'Limpo' : 'Lavar');
						} else {
							$('#limpezaswitch').prop('checked', <?php echo ($veiculo['limpeza']=='S') ? 'true' : 'false'; ?>);
							$('#limpezainfo').attr('aria-label', '<?php echo ($veiculo['limpeza']=='S') ? 'Limpo' : 'Lavar' ?>');
						}
					}
				});
			});
		</script>

		<div id='observacaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Observação</label>
			<div id='observacaoinner' style='display:inline-block;'>
				<textarea id='observacao' rows='9' style='vertical-align:middle;border:1px solid var(--preto);border-radius:var(--radius);'><?php echo $veiculo['observacao'] ?></textarea>
			</div>
			<p id='enviarobservacao' class='salvarconfig' style='display:none;vertical-align:top;'></p>
		</div> <!-- observacaowrap -->
		<script>
			obsclick = 0;
			$('#observacaoinner').css({
				'min-width':'100%',
				'max-width':'100%'
			});
			$('#observacao').on('click', function() {
				if (obsclick==0) {
					$('#observacaoinner').css({
						'display':'block',
						'min-width':'77.6%',
						'max-width':'77.6%'
					});
					setTimeout(function() {
						$('#observacaoinner').css({
							'float':'none',
							'display':'inline-block'
						});
						$('#enviarobservacao').css('display','inline-block');
		                        }, 350);

					obsclick = 1;
					if (obsclick==1) {
						$('body').on('click', function() {
							$('#observacaoinner').css({
								'display':'block',
								'min-width':'100%',
								'max-width':'100%'
							});
							$('#enviarobservacao').css('display','none');
							obsclick = 0;
						});
					} // obsclick = 1
				} // obsclick = 0
				return false;
			});
		</script>

	</div> <!-- listawrap -->

	<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
		<?php
			Icone('reservaresseveiculo','reservar','reservaricon');
			if ($id['aluguel']!=0) {
				Icone('devolverveiculo','devolver','devolvericon');
				Icone('veralinfo','aluguel','infoicon');
			}

			if ($id['reserva']!=0) {
				if ($id['reserva']!=$id['aluguel']) {
					Icone('verreserva','próxima reserva','reservainfoicon');
					if ($id['aluguel']==0) {
						Icone('alugaresseveiculo','alugar','alugaricon');
					} // aluguel
				} // se a próxima reserva é diferente do aluguel atual
			} // agendamentos

			if ( ($id['aluguel']==0) && ($id['reserva']==0) ) {
				Icone('alugaresseveiculo','alugar','alugaricon');
			} // disponivel

			if ($id['manutencao']==0) {
				Icone('enviarmanutencao','manutenção','iniciarmanutencaoicon');
			} else {
				$manutencaoativacao = new ConsultaDatabase($uid);
				$manutencaoativacao = $manutencaoativacao->ManutencaoAtivacao($id['agendamentomanutencao']);
				if ($manutencaoativacao['ativa']==='S') {
					if ($inicio_manutencao<=$agora) {
						if ($manutencaoatual[0]['confirmada']==1) {
							Icone('disponibilizarveiculo','disponibilizar','disponibilizaricon');
							Icone('minfo','manutenção','manutencaoicon');
						} else {
							Icone('enviarmanutencao','manutenção','iniciarmanutencaoicon');
						} // confirmada
					} else {
						Icone('enviarmanutencao','manutenção','iniciarmanutencaoicon');
						Icone('minfo','próxima manutenção','manutencaoinfoicon');
					} // data
				} else if ($manutencaoativacao['ativa']==='N') {
					Icone('enviarmanutencao','manutenção','iniciarmanutencaoicon');
				} else {
					Icone('disponibilizarveiculo','disponibilizar','disponibilizaricon');
					Icone('minfo','manutenção','manutencaoicon');
				} // ativa
			}
		?>
	</div>

</div>
<!-- items -->

<script>
	abreFundamental();

	$('#minfo').on('click',function () {
		manutencaoFundamental(<?php echo $id['manutencao'] ?>);
	});

	$('#relatorioveiculo').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/veiculos/relatorio/?v=<?php echo $veiculo['vid'] ?>';
	});

	$('#editarveiculo').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/veiculos/editar/?v=<?php echo $veiculo['vid'] ?>';
	});

	$('#verreserva').on('click', function() {
		reservaFundamental(<?php echo $id['reserva'] ?>, 1);
	});

	$('#verdoc').on('click',function () {
		verDoc(<?php echo $veiculo['vid'] ?>);
	});

	$('#verfoto').on('click',function () {
		verFoto(<?php echo $veiculo['vid'] ?>);
	});

	$('#vercalendario').on('click',function () {
		calendarioPop(3,'vestimenta',<?php echo $veiculo['vid'] ?>);
	});

	<?php
		$atual = new Conforto($uid);
		$atual = $atual->AluguelAtual($veiculo['vid']);
	?>
	$('#veralinfo').on('click',function () {
		aluguelFundamental(<?php echo $atual ?>,1);
	});
	$('#alugaresseveiculo').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/alugueis/novo/?v=<?php echo $veiculo['vid'] ?>';
	});
	$('#reservaresseveiculo').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/alugueis/novo/?v=<?php echo $veiculo['vid'] ?>';
	});
	$('#devolverveiculo').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/devolucoes/novo/?v=<?php echo $veiculo['vid'] ?>';
	});
	$('#disponibilizarveiculo').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/retornos/novo/?v=<?php echo $veiculo['vid'] ?>';
	});
	$('#enviarmanutencao').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/manutencoes/novo/?v=<?php echo $veiculo['vid'] ?>';
	});

	$('#enviarobservacao').on('click', function() {
		vobservacao = $('#observacao').val();
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/veiculos/includes/vobservacaomod.inc.php',
			data: {
				veiculo: '<?php echo $veiculo['vid'] ?>',
				observacao: vobservacao
			},
			success: function(modobs) {
				if (modobs.includes('sucesso')) {
					$('#observacao').prop('disabled', 'disabled');
					$('#observacao').css('cursor', 'not-allowed');
					$('#observacao').css('background-color', 'var(--verde)');
					$('#observacao').css('color', 'var(--preto)');
					$('#observacao').css('border', '1px solid var(--preto)');
					$('#enviarobservacao').css('display', 'none');
					$('#observacaoinner').css('min-width', '100%');
					$('#observacaoinner').css('max-width', '100%');
					$('#observacaoenviar').css('display', 'none');
					mostraFooter();
				} else {
					$('#observacao').css('border', '1px solid var(--rosa)');
					$('#observacao').css('background-color', 'var(--branco)');
					$('#observacao').css('color', 'var(--preto)');
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

</script>
