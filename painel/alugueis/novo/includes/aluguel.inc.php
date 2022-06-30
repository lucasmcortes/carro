<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitaluguel'])) {
	$alugando = "<div id='informacaoalugandowrap'><!-- informacao alugando wrap -->";
	$passe=0;
	$reserva=0;

	$hoje = $agora->format('Y-m-d H');
	if (!empty($_POST['horainicio'])) {
		$horainicio = $_POST['horainicio'].':00:00';

		$inicio = $_POST['inicio'];
		if (preg_match('/^(\d{2}\/\d{2}\/\d{4})+$/', $inicio, $inicio, PREG_UNMATCHED_AS_NULL)) {
			$inicio = $inicio[0];
			$inicio = explode('/',$inicio);
			$inicio = $inicio[2].'-'.$inicio[1].'-'.$inicio[0];
			$inicio = $inicio.' '.$horainicio.'.000000';
		} else {
			RespostaRetorno('datainicio');
			return;
		} // regex data inicio
	} else {
		RespostaRetorno('datahorario');
		return;
	}

	if (!empty($_POST['horadevolucao'])) {
		$horadevolucao = $_POST['horadevolucao'].':00:00';
		$devolucao = $_POST['devolucao'];
		if (preg_match('/^(\d{2}\/\d{2}\/\d{4})+$/', $devolucao, $devolucao, PREG_UNMATCHED_AS_NULL)) {
			$devolucao = $devolucao[0];
			$devolucao = explode('/',$devolucao);
			$devolucao = $devolucao[2].'-'.$devolucao[1].'-'.$devolucao[0];
			$devolucao = $devolucao.' '.$horadevolucao.'.000000';
		} else {
			RespostaRetorno('datadevolucao');
			return;
		} // regex data devolucao

	} else {
		RespostaRetorno('datahorario');
		return;
	}

	$comeco = new DateTime($inicio);
	$conclusao = new DateTime($devolucao);
	$diarias = $comeco->diff($conclusao);

	$vid = $_POST['veiculo'];
	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);

	$lid = $_POST['locatario'];
	if (empty($lid)) {
		RespostaRetorno('lid');
		return;
	} // lid
	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($lid);

	$validade_cnh = explode('/',$locatario['validade']);
	$validade_cnh = $validade_cnh[2].'-'.$validade_cnh[1].'-'.$validade_cnh[0].' 00:00:00.000000';
	$validade_cnh = new DateTime($validade_cnh);
	if ($validade_cnh<$agora) {
		// RespostaRetorno('validadecnh');
		// return;
	} // cnh com validade vencida

	$diaria = Sanitiza($_POST['diaria']);
	$categoria = new ConsultaDatabase($uid);
	$categoria = $categoria->VeiculoCategoria($veiculo['categoria']);
	switch ($categoria) {
		case 'Carro':
			$diaria = $configuracoes['preco_diaria_associado']?:$diaria;
			break;
		case 'Utilitário':
			$diaria = $configuracoes['preco_diaria_utilitario_associado']?:$diaria;
			break;
		case 'Moto':
			$diaria = $configuracoes['preco_diaria_moto_associado']?:$diaria;
			break;
		default:
			$diaria = $configuracoes['preco_diaria_associado']?:$diaria;
	} // switch preco diaria/categoria

	$kilometragem = Sanitiza($_POST['kilometragem']);
	if ($kilometragem==1) {
		if ($locatario['asid']==0) {
			RespostaRetorno('kmpermitida');
			return;
		} // é associado
	} // se km livre

	$kilometragematual = $_POST['kilometragematual'];
	if ($kilometragematual<$veiculo['km']) {
		echo 'O veículo tinha '.Kilometragem($veiculo['km']).' no registro mais recente';
		return;
	} // confere kilometragem atual

	$possibilidade = new Conforto($uid);
	$possibilidade = $possibilidade->AluguelPossivel($vid,$comeco,$conclusao);
	if (count($possibilidade)>0) {
		$dias_agendados = 'O(s) dia(s) ';
		foreach ($possibilidade as $dia) {
			$dia = new DateTime($dia);
			$dia = $dia->format('d/m/Y');
			$dias_agendados .= '<b>'.$dia.'</b>, ';
		} // foreach
		$dias_agendados = rtrim($dias_agendados,', ');
		$dias_agendados .= ' já estão reservados para esse veículo.<br>';
		echo $dias_agendados;
		return;
	} // existem dias desejados nessa modificação que estão agendados por outra reserva

	$paginicial = Sanitiza($_POST['paginicial']);
	if (empty($paginicial)) {
		if ($locatario['associado']=='N') {
			RespostaRetorno('paginicial');
			return;
		} // se é particular
	} // pagamento incial

	$forma = $_POST['forma'];
	if (empty($forma)) {
		if ($locatario['associado']=='N') {
			RespostaRetorno('pagforma');
			return;
		} // se é particular
	} else {
		$formapagamento = new Conforto($uid);
		$formapagamento = $formapagamento->SwitchForma($forma);
		$forma = $formapagamento;
	}// forma

	$caucao = Sanitiza($_POST['caucao']);
	if (empty($caucao)) {
		RespostaRetorno('caucao');
		return;
	} // caucao
	$formacaucao = $_POST['formacaucao'];
	if (empty($formacaucao)) {
		RespostaRetorno('caucaoforma');
		return;
	} else {
		$formapagamentocaucao = new Conforto($uid);
		$formapagamentocaucao = $formapagamentocaucao->SwitchForma($formacaucao);
		$formacaucao = $formapagamentocaucao;
	}// formacaucao

	if ( ($comeco->format('Y-m-d'))==($agora->format('Y-m-d')) ) {
		if ($_POST['horainicio']<$agora->format('H')) {
			RespostaRetorno('horarioreal');
			return;
		} // hora < agora
	} // se é hoje o dia de inicio do aluguel

	if (empty($vid) || empty($lid) || empty($kilometragem) || empty($diaria) || empty($inicio) || empty($devolucao) || empty($horainicio) || empty($horadevolucao) ) {
		RespostaRetorno('vazio');
		return;
	} else {
		if ( ($comeco->format('Y-m-d H'))>=($conclusao->format('Y-m-d H')) ) {
			RespostaRetorno('datareal');
			return;
		} else {
			$encontraadmin = new ConsultaDatabase($uid);
			$encontraadmin = $encontraadmin->AdminInfo($uid);
			if ($encontraadmin!=0) {
				if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
					// $authadmin = new ConsultaDatabase($uid);
					// $authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);
					$authadmin = 1;

					if ($authadmin==0) {
						RespostaRetorno('authadmin');
						return;
					} else {
						if ( ($comeco->format('Y-m-d H'))<($agora->format('Y-m-d H')) ) {
							RespostaRetorno('hojereal');
							return;
						} else {

							// TÁ AQUI O BICHO
							($veiculo['limpeza']=='S') ? $limpeza_inicial = 'Limpo' : $limpeza_inicial = 'À lavar';

							$total_de_dias_previsao = $diarias->format('%a');

							if ($kilometragem==1) {
								$limite_km_aluguel = 'Livre';
							} else {
								$limite_km_aluguel = Kilometragem($kilometragem);
							} // estabelecimento de limite de km

							$diaria_excedente_data = new ConsultaDatabase($uid);
							$diaria_excedente_data = $diaria_excedente_data->DiariaExcedenteData($data);
							switch ($categoria) {
								case 'Carro':
									$preco_diaria_excedente = $diaria_excedente_data['excedente_carro'];
									break;
								case 'Moto':
									$preco_diaria_excedente = $diaria_excedente_data['excedente_moto'];
									break;
								case 'Utilitário':
									$preco_diaria_excedente = $diaria_excedente_data['excedente_utilitario'];
									break;
								default:
									$preco_diaria_excedente = $diaria_excedente_data['excedente_carro'];
									break;
							}// switch excedente/categoria

							// $alugando .= "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
							// Icone('linfo','locatário','linfoicon');
							// Icone('vinfo','veículo','vinfoicon');
							// $alugando .= "
							// 	<script>
							// 		$('#vinfo').on('click', function() {
							// 			veiculoFundamental(".$vid.");
							// 		});
							//
							// 		$('#linfo').on('click',function () {
							// 			locatarioFundamental(".$lid.");
							// 		});
							// 	</script>
							// ";
							// $alugando .= "</div>";

							$alugando .= "
								<div id='alugandowrap' class='relatoriowrap'>
									<div class='slotrelatoriowrap'>
										<div class='slotrelatorio'>
											<p class='headerslotrelatorio'><b>Locatário:</b></p>
											<p class='infoslotrelatorio'>".$locatario['nome']."</p>
											<p class='headerslotrelatorio'><b>Veículo:</b></p>
											<p class='infoslotrelatorio'>".$veiculo['modelo']." (".$veiculo['placa'].")</p>
											<p class='headerslotrelatorio'><b>Limpeza atual:</b></p>
											<p class='infoslotrelatorio'>".$limpeza_inicial."</p>
											<p class='headerslotrelatorio'><b>Caução:</b></p>
											<p class='infoslotrelatorio'>".Dinheiro($caucao)." em ".mb_strtolower($formacaucao)."</p>
										</div>
									</div>
									<div class='slotrelatoriowrap'>
										<div class='slotrelatorio'>
											<p class='headerslotrelatorio'><b>Pagamento inicial:</b></p>
											<p class='infoslotrelatorio'>".Dinheiro($paginicial)." em ".mb_strtolower($forma)."</p>
											<p class='headerslotrelatorio'><b>Kilometragem atual:</b></p>
											<p class='infoslotrelatorio'>".Kilometragem($kilometragematual)."</p>
											<p class='headerslotrelatorio'><b>Limite de kilometragem:</b></p>
											<p class='infoslotrelatorio'>".$limite_km_aluguel."</p>
											<p class='headerslotrelatorio'><b>Preço por KM excedente:</b></p>
											<p class='infoslotrelatorio'>".Dinheiro($configuracoes['preco_km'])."</p>
										</div>
									</div>
									<div class='slotrelatoriowrap'>
										<div class='slotrelatorio'>
											<p class='headerslotrelatorio'><b>Início:</b></p>
											<p class='infoslotrelatorio'>".$comeco->format('d/m/y')." às ".$comeco->format('H')."h</p>
											<p class='headerslotrelatorio'><b>Devolução prevista:</b></p>
											<p class='infoslotrelatorio'>".$conclusao->format('d/m/y')." às ".$conclusao->format('H')."h</p>
											<p class='headerslotrelatorio'><b>Diárias previstas:</b></p>
											<p class='infoslotrelatorio'>".$total_de_dias_previsao." x ".Dinheiro($diaria)."</p>
											<p class='headerslotrelatorio'><b>Diária excedente:</b></p>
											<p class='infoslotrelatorio'>".Dinheiro($preco_diaria_excedente)."</p>
										</div>
									</div>
								</div>
							";
							$alugando .= "<div id='confirmaraluguel' class='confirmacao'>confirmar aluguel</div>";
							$alugando .= "<div id='voltaraluguel' class='confirmacao'>voltar</div>";
							$alugando .= "</div><!-- informacao alugando wrap -->";
							$alugando .= "
								<script>
				                                        $('#voltaraluguel').on('click', function() {
				                                                formulario.css('display', 'inline-block');
										$('#informacaoalugandowrap').html('');
				                                        });
							";

							// variaveis pra aluguel
							$lid;
							$vid;
							$diaria;
							$kilometragem;
							$inicio;
							$devolucao;
							$kilometragematual;
							$paginicial;
							$forma;
							$caucao;
							$formacaucao;

							$alugando .= "
									$('#confirmaraluguel').on('click', function() {
										$.ajax({
							                                type: 'POST',
							                                dataType: 'html',
							                                async: true,
							                                url: '".$dominio."/painel/alugueis/novo/includes/aluguelconfirmado.inc.php',
							                                data: {
							                                        aluguelconfirmado: 1,
												lid: '".$lid."',
												vid: '".$vid."',
												diaria: '".$diaria."',
												kilometragem: '".$kilometragem."',
												inicio: '".$inicio."',
												devolucao: '".$devolucao."',
												kilometragematual: '".$kilometragematual."',
												paginicial: '".$paginicial."',
												forma: '".$forma."',
												caucao: '".$caucao."',
												formacaucao: '".$formacaucao."'
							                                },
							                                success: function(possivel) {
							                                        if (possivel.includes('sucesso') == true) {
							                                        	retorno.html(possivel);
													retorno.append('<img id=\"sucessogif\" src=\"".$dominio."/img/sucesso.gif\">');

							                                        } else {
							                                                formulario.css('display', 'inline-block');
													$('#informacaoalugandowrap').html('');
												}
							                                }
							                        });
									});
							";

							$alugando .= '</script>';

						} // prazo corretamente definido
					} // authadmin
				} else {
					RespostaRetorno('adminnivel');
					return;
				} // nivel
			} else {
				RespostaRetorno('adminencontrado');
				return;
			} // admin não encontrado
		} // prazo inicio e devolucao corretamente coerente com o eixo do tempo
	} // campos preenchidos
} else {
	$alugando = ':((';
} // isset post submit

echo $alugando;

?>
