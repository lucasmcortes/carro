<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitdevolucao'])) {
	$devolvendo = "<div id='informacaodevolucaowrap'><!-- informacao devolucao wrap -->";

	$cobranca = 1;
	$cortesias = 0;
	$cortesias_por_placa = 0;
	$cortesias_da_placa_utilizadas_no_ultimo_mes = 0;
	$cortesias_da_placa_utilizadas_no_ultimo_ano = 0;
	$adicional=0;
	$adicionais='';
	$atenuada=0;
	$atenuadas='';

	$hoje = $agora->format('Y-m-d H');

	$vid = $_POST['veiculo'];
	if (empty($vid)) {
		RespostaRetorno('veiculo');
		return;
	}
	$veiculo = new ConsultaDatabase($uid);
	$veiculo = $veiculo->Veiculo($vid);

	$alugueisativos = [];
	$listaalugueisveiculo = new ConsultaDatabase($uid);
	$listaalugueisveiculo = $listaalugueisveiculo->ListaAlugueisVeiculo($vid);
	if ($listaalugueisveiculo[0]['aid']!=0) {
		foreach ($listaalugueisveiculo as $aluguelveiculo) {
			$devolucaoaluguel = new ConsultaDatabase($uid);
			$devolucaoaluguel = $devolucaoaluguel->Devolucao($aluguelveiculo['aid']);
			if ($devolucaoaluguel['aid']==0) {
				$reserva = new ConsultaDatabase($uid);
				$reserva = $reserva->ReservaDevolvida($aluguelveiculo['aid']);
				if ($reserva['aid']!=0) {
					// reserva
					$ativa = new ConsultaDatabase($uid);
					$ativa = $ativa->Ativacao($reserva['reid']);
					if ($ativa['ativa']=='S') {
						// vigente
						$inicio = new DateTime($reserva['inicio']);
						if ($inicio<$agora) {
							if ($reserva['confirmada']==1) {
								$alugueisativos[] = $aluguelveiculo['aid'];
							} // confirmada
						} // se já começou
					} // ativa
				} else {
					// aluguel
					$alugueisativos[] = $aluguelveiculo['aid'];
				} // reserva
			} // não foi devolvido
		} // foreach aluguel
	} // aid != 0

	$aluguelatual = $alugueisativos[0]; // coloca o atual como o aluguel ativo mais antigo

	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aluguelatual);
	$inicio_aluguel = new DateTime($aluguel['inicio']);
	$devolucao_prevista = new DateTime($aluguel['devolucao']);
	$valorinicial = $aluguel['valor'];
	$formavalorinicial = $aluguel['forma'];

	$preco_diaria_excedente = new Conforto($uid);
	$preco_diaria_excedente = $preco_diaria_excedente->ExcedenteData($aluguel['aid']);

	$previsao_diarias = $inicio_aluguel->diff($devolucao_prevista);
	$total_de_dias_previsao = $previsao_diarias->format('%a');

	$atualizadas = new Conforto($uid);
	$atualizadas = $atualizadas->DatasAtualizadas($aluguel['aid']);
	$inicio = $atualizadas['inicio'];
	$devolucao = new DateTime($atualizadas['devolucao']->format('Y-m-d').' '.$atualizadas['devolucao']->format('H:i:s.u'));

	$hora = $inicio->format('H');

	$d=0;
	$diarias = $inicio->diff($devolucao);

	$total_de_dias = $diarias->format('%a');
	for ($i=$inicio; $i<$devolucao; $i->modify('+1 day')) {
		$cada_dia = $i->format('d/m/Y');
		$d++;
		$i->modify('+1 day'); // adiciona pra mostrar o limite da diária atual na string
		$i->modify('-1 day'); // diminui pra voltar pro dia correto do cálculo
	} // cada dia

	$atualizadas = new Conforto($uid);
	$atualizadas = $atualizadas->DatasAtualizadas($aluguel['aid']);
	$inicio = $atualizadas['inicio'];
	$devolucao = new DateTime($atualizadas['devolucao']->format('Y-m-d').' '.$atualizadas['inicio']->format('H:i:s.u'));

	$devolucao_com_tolerancia = new Conforto($uid);
	$devolucao_com_tolerancia = $devolucao_com_tolerancia->Tolerancia($devolucao_para_tolerancia = new DateTime($devolucao->format('Y-m-d').' '.$atualizadas['devolucao']->format('H:i:s.u'))); // atualiza data de devolução pra dar a tolerância configurada
	$devolucao = $devolucao_com_tolerancia;


	$diaria = $aluguel['diaria'];

	$locatario = new ConsultaDatabase($uid);
	$locatario = $locatario->LocatarioInfo($aluguel['lid']);


	//////////// COLOCA NA TABELA LIMPEZA
	$limpezavalor = 0;
	$limpeza_anterior = $veiculo['limpeza'];
	if ($limpeza_anterior=='S') {
		// estava limpo quando alugou
		$limpeza_status = '• estava limpo quando alugou';
	} else if ($limpeza_anterior=='N') {
		$limpeza_status = '• precisava lavar quando alugou';
	} // limpeza anterior
	$limpeza_devolucao = $_POST['limpeza'];
	if ($limpeza_devolucao=='S') {
		$limpeza_status .= ' e está limpo agora';
		$limpezatipo = 'Limpo';
	} else if ($limpeza_devolucao=='N') {
		$limpeza_status .= ' e precisa lavar agora';
		switch ($_POST['limpezatipo']) {
			case '1':
				$limpezatipo = 'Limpeza executiva';
				$limpezavalor = $configuracoes['preco_le'];
				break;
			case '2':
				$limpezatipo = 'Limpeza completa';
				$limpezavalor = $configuracoes['preco_lc'];
				break;
			case '3';
				$limpezatipo = 'Limpeza completa com motor';
				$limpezavalor = $configuracoes['preco_lm'];
				break;
			default:
				$limpezatipo = 'Limpeza completa com motor';
				$limpezavalor = $configuracoes['preco_lm'];
				break;
		}
		$limpeza_status .= ', somando <b>'.Dinheiro($limpezavalor).'</b> ao preço total pela <b>'.mb_strtolower($limpezatipo).'</b>';
	} // limpeza atual


	/////////////////// COLOCA NA TABELA VALOR ADICIONAL
	$valor_adicional = Sanitiza($_POST['valoradicional']?:0);
	$descricao_valor_adicional = $_POST['descricao']?:0;
	if ($valor_adicional>0) {
		if (empty($descricao_valor_adicional)) {
			RespostaRetorno('descvaladicional');
			return;
		} //  descricao
	} // valor adicional > 0



	//////////// COLOCA NA TABELA KILOMETRAGEM
	$km_anterior = new ConsultaDatabase($uid);
	$km_anterior = $km_anterior->Kilometragem($aluguel['vid']);
	$kilometragem_anterior = $km_anterior['km'];
	$limite_km_aluguel = $aluguel['kilometragem'];
	$kilometragem_devolucao = Sanitiza($_POST['kilometragem']);

	if ($kilometragem_devolucao<$kilometragem_anterior) {
		echo 'O veículo tinha '.Kilometragem($kilometragem_anterior).' quando foi alugado';
		return;
	} else if ($kilometragem_devolucao==$kilometragem_anterior) {
		RespostaRetorno('kmigual');
		return;
	} // se agora tem menos km rodados que quando alugou

	$km = $kilometragem_devolucao - $kilometragem_anterior;
	$km_usados = $kilometragem_devolucao - $kilometragem_anterior;
	$kilometragem_atual = $kilometragem_anterior + $km_usados;

	if ($limite_km_aluguel==1) {
		// é kilometragem livre
		$resposta_kilometragem = '• kilometragem livre<br>';
		$valor_km_a_mais = 0;
	} else {
		if ($km_usados<=$limite_km_aluguel) {
			$valor_km_a_mais = 0;
			$resposta_kilometragem = '• kilometragem ok; usou <b>'.Kilometragem($km_usados).'</b> dos <b>'.Kilometragem($limite_km_aluguel).'</b> combinados no aluguel<br>';
		} else if ($km_usados>$limite_km_aluguel) {
			$total_km_a_mais = $km_usados - $limite_km_aluguel;
			$valor_km_a_mais = $configuracoes['preco_km'] * $total_km_a_mais;
			$resposta_kilometragem = '• foram usados <b>'.Kilometragem($km_usados).'</b>, <b>'.Kilometragem($total_km_a_mais).'</b> a mais do que os <b>'.Kilometragem($limite_km_aluguel).'</b> combinados no aluguel, ocasionando uma cobrança de <b>'.Dinheiro($valor_km_a_mais).'</b> pelos kilometros excedentes com o preço do km custando <b>'.Dinheiro($configuracoes['preco_km']).'</b><br>';
		} // cálculo km
	} // limite de kilometragem
	$resposta_kilometragem .= '• agora o '.$veiculo['modelo'].' tem <b>'.Kilometragem($kilometragem_atual).'</b> rodados<br>';



	if ($agora->format('Y-m-d H:i')<=$devolucao->format('Y-m-d H:i')) {
		if ($agora->format('Y-m-d')<=$devolucao->format('Y-m-d')) {
			//$devolucao_original = $atualizadas['devolucao'];
			$devolucao_original = $devolucao;
			for ($i=$agora->modify('+1 day'); $i<$devolucao_original; $i->modify('+1 day')) {
				$dia_atenuada = $i->format('Y-m-d H');
				$atenuada++;

				$i->modify('-1 day'); // diminui pra voltar pro dia correto do cálculo
				$atenuadas .= "<p style='min-width:100%;max-width:100%;display:inline-block;'>";
				$atenuadas .= '• <b>diária '.$atenuada.'</b>: de <b>'.$i->format('d/m/Y').'</b> às <b>'.$devolucao_original->format('H').'h</b>';
				$i->modify('+1 day'); // adiciona pra mostrar o limite da diária atual na string
				$atenuadas .= ' até <b>'.$i->format('d/m/Y').'</b> às <b>'.$devolucao_original->format('H').'h</b>';
				$atenuadas .= "</p>";

				$diarias_atenuadas[] = $i->format('Y-m-d').' '.$devolucao_original->format('H:i:s.u'); // coloca no array pra arrumar a disponibilidade
			} // cada dia
		} // entregou antes do combinado

	} else if ($agora->format('Y-m-d H:i')>$devolucao->format('Y-m-d H:i')) {
		//$devolucao_original = $atualizadas['devolucao'];
		$devolucao_original = $devolucao;
		for ($i=$devolucao_original; $i<=$agora; $i->modify('+1 day')) {
			$dia_adicional = $i->format('Y-m-d H');
			$adicional++;

			$adicionais .= "<p style='min-width:100%;max-width:100%;display:inline-block;'>";
			$adicionais .= "• <b>diária adicional ".$adicional."</b>: de <b>".$i->format('d/m/Y')."</b> às <b>".$devolucao_original->format('H')."h</b>";
			$i->modify('+1 day'); // adiciona pra mostrar o limite da diária atual na string
			$adicionais .= " até <b>".$i->format('d/m/Y')."</b> às <b>".$devolucao_original->format('H')."h</b>";
			$adicionais .= "</p>";
			$i->modify('-1 day'); // adiciona pra mostrar o limite da diária atual na string

			$diarias_adicionais[] = $i->format('Y-m-d').' '.$devolucao_original->format('H:i:s.u'); // coloca no array pra arrumar a disponibilidade
		} // cada dia adicional
	} // prazo


	$numero_real_de_dias_na_devolucao = $diarias->format('%a') + $adicional - $atenuada;
	($numero_real_de_dias_na_devolucao==0) ? $numero_real_de_dias_na_devolucao = 1 : $numero_real_de_dias_na_devolucao = $numero_real_de_dias_na_devolucao;

	$cortesias_utilizadas_nesse_aluguel = $cortesias-$numero_real_de_dias_na_devolucao;
	($cortesias_utilizadas_nesse_aluguel<=0) ? $cortesias_utilizadas_nesse_aluguel = 0 : $cortesias_utilizadas_nesse_aluguel = $cortesias_utilizadas_nesse_aluguel;

	$cortesias_utilizadas_nesse_aluguel = ($placa_definida['cortesias_disponiveis']??0) - $cortesias_utilizadas_nesse_aluguel;

	$preco_desconto = 0;
	if ($cortesias_utilizadas_nesse_aluguel>$numero_real_de_dias_na_devolucao) {
		$uso_necessario_de_cortesias = 'todas';
	} else {
		$uso_necessario_de_cortesias = $numero_real_de_dias_na_devolucao;
	}

	$preco_desconto = $diaria * $cortesias;

	if ($numero_real_de_dias_na_devolucao>$total_de_dias_previsao) {
		$preco_previsto = $diaria * $total_de_dias_previsao;
		$diferenca_adicionais = $numero_real_de_dias_na_devolucao - $total_de_dias_previsao;
		$preco_adicionais = $preco_diaria_excedente * $diferenca_adicionais;

		$preco_final = $preco_previsto + $preco_adicionais - $preco_desconto;
		$preco_total = $preco_previsto + $preco_adicionais;
	} else {
		$preco_final = $diaria * ($numero_real_de_dias_na_devolucao-$cortesias);
		$preco_total = $diaria * $numero_real_de_dias_na_devolucao;
	} // se tem diárias excedentes

	if ($preco_final<0) {
		$preco_final = 0;
		$cobranca = 0;
	} // preco final

	$valor_total = $preco_final+$valor_km_a_mais+$limpezavalor+$valor_adicional;
	$cortesias_devolucao = $cortesias_utilizadas_nesse_aluguel;
	($cortesias_utilizadas_nesse_aluguel<0) ? $cortesias_utilizadas_nesse_aluguel = 0 : $cortesias_utilizadas_nesse_aluguel =$cortesias_utilizadas_nesse_aluguel;

	$pagamentosaluguel = new Conforto($uid);
	$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aluguel['aid']);

	$valor_mostrado = $valor_total-$pagamentosaluguel;


	////////////////////// TÁ AQUI O BICHO
	($_POST['limpeza']=='S') ? $limpeza_atual = 'Limpo' : $limpeza_atual = 'À lavar';
	($veiculo['limpeza']=='S') ? $limpeza_inicial = 'Limpo' : $limpeza_inicial = 'À lavar';
	($locatario['associado']=='S') ? $associado = 'Associado' : $associado = 'Não associado';
	$devolucao_atual = new DateTime($data);

	$devolvendo .= "
		<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap'>
			<div class='slotrelatoriowrap'>
				<div class='slotrelatorio'>
					<p class='headerslotrelatorio'><b>Locatário:</b></p>
					<p class='infoslotrelatorio'>".$locatario['nome']."</p>
					<p class='headerslotrelatorio'><b>Modelo:</b></p>
					<p class='infoslotrelatorio'>".$veiculo['modelo']." (".$veiculo['placa'].")</p>
					<p class='headerslotrelatorio'><b>Caução:</b></p>
					<p class='infoslotrelatorio'>".Dinheiro($aluguel['valor_caucao'])." em ".mb_strtolower($aluguel['forma_caucao'])."</p>
					<p class='headerslotrelatorio'><b>Kilometragem para o aluguel:</b></p>
					<p class='infoslotrelatorio'>".Kilometragem($aluguel['kilometragem'])."</p>
					<p class='headerslotrelatorio'><b>Data de início:</b></p>
					<p class='infoslotrelatorio'>".$inicio->format('d/m/Y')." às ".$inicio->format('H')."h</p>
					<p class='headerslotrelatorio'><b>Devolução prevista:</b></p>
					<p class='infoslotrelatorio'>".$devolucao_prevista->format('d/m/y')." às ".$devolucao->format('H')."h</p>
					<p class='headerslotrelatorio'><b>Previsão de diárias:</b></p>
					<p class='infoslotrelatorio'>".$total_de_dias." x ".Dinheiro($aluguel['diaria'])."</p>
				</div>
			</div>
			<div class='slotrelatoriowrap'>
				<div class='slotrelatorio'>
					<p class='headerslotrelatorio'><b>Limpeza inicial:</b></p>
					<p class='infoslotrelatorio'>".$limpeza_inicial."</p>
					<p class='headerslotrelatorio'><b>Limpeza atual:</b></p>
					<p class='infoslotrelatorio'>".$limpeza_atual."</p>
					<p class='headerslotrelatorio'><b>Kilometragem inicial:</b></p>
					<p class='infoslotrelatorio'>".Kilometragem($km_anterior['km'])."</p>
					<p class='headerslotrelatorio'><b>Kilometragem atual:</b></p>
					<p class='infoslotrelatorio'>".Kilometragem($kilometragem_atual)."</p>
					<p class='headerslotrelatorio'><b>Kilometros rodados:</b></p>
					<p class='infoslotrelatorio'>".Kilometragem($km_usados)."</p>
					<p class='headerslotrelatorio'><b>Devolução:</b></p>
					<p class='infoslotrelatorio'>".$devolucao_atual->format('d/m/Y')." às ".$devolucao_atual->format('H')."h".$devolucao_atual->format('i')."</p>
	";

	if ($numero_real_de_dias_na_devolucao>$total_de_dias_previsao) {
		$devolvendo .= "
			<p class='headerslotrelatorio'><b>Diárias utilizadas:</b></p>
			<p class='infoslotrelatorio'>".$total_de_dias_previsao." x ".Dinheiro($aluguel['diaria'])." + ".$diferenca_adicionais." x ".Dinheiro($preco_diaria_excedente)."</p>
		";
	} else {
		$devolvendo .= "
			<p class='headerslotrelatorio'><b>Diárias utilizadas:</b></p>
			<p class='infoslotrelatorio'>".$numero_real_de_dias_na_devolucao." x ".Dinheiro($aluguel['diaria'])."</p>
		";
	} // se tem diárias excedentes

	$devolvendo .="
				</div>
			</div>
			<div class='slotrelatoriowrap'>
				<div class='slotrelatorio'>
					<p class='headerslotrelatorio'><b>Preço pelas diárias utilizadas:</b></p>
					<p class='infoslotrelatorio'>".Dinheiro($preco_total)."</p>
	";

	if ($cortesias_utilizadas_nesse_aluguel>0) {
		$exibe_preco_final = Dinheiro($preco_final?:0).' (desconto de '.$cortesias_utilizadas_nesse_aluguel.' cortesia(s) x '.Dinheiro($diaria).')';
	} else {
		$exibe_preco_final = Dinheiro($preco_final?:0);
	}

	$devolvendo .= "
			<p class='headerslotrelatorio'><b>Preço a ser pago pelas diárias utilizadas:</b></p>
			<p id='preco_final' class='infoslotrelatorio'>".$exibe_preco_final."</p>
			<p class='headerslotrelatorio'><b>Preço pela kilometragem excedente:</b></p>
			<p class='infoslotrelatorio'>".Dinheiro($valor_km_a_mais)."</p>
	";
	if ($limpezavalor>0) {
		$devolvendo .= "
			<p class='headerslotrelatorio'><b>".$limpezatipo.":</b></p>
			<p class='infoslotrelatorio'>".Dinheiro($limpezavalor)."</p>
		";
	} else {
		$devolvendo .= "
			<p class='headerslotrelatorio'><b>Limpeza:</b></p>
			<p class='infoslotrelatorio'>".Dinheiro($limpezavalor)."</p>
		";
	} // limpezavalor > 0

	if ($valor_adicional>0) {
		$devolvendo .= "
			<p class='headerslotrelatorio'><b>".$descricao_valor_adicional.":</b></p>
			<p class='infoslotrelatorio'>".Dinheiro($valor_adicional)."</p>
		";
	} // valor adicional > 0

	$devolvendo .= "
		<p class='headerslotrelatorio'><b>Soma dos débitos:</b></p>
		<p id='valor_total' class='infoslotrelatorio'>".Dinheiro($valor_total)."</p>
		<p class='headerslotrelatorio'><b>Valor pago até o momento:</b></p>
		<p class='infoslotrelatorio'>".Dinheiro($pagamentosaluguel)."</p>
	";

	if (($valor_mostrado)>0) {
		$devolvendo .= "
			<div id='valor_mostrado' class='slotrelatorio'>
				<p class='headerslotrelatorio'><b>Total a ser pago:</b></p>
				<p class='infoslotrelatorio'>".Dinheiro($valor_mostrado)."</p>
			</div>
		";
	} else {
		$devolvendo .= "
			<div id='valor_mostrado' class='slotrelatorio'>
				<p class='headerslotrelatorio'><b>Total a ser devolvido:</b></p>
				<p class='infoslotrelatorio'>".Dinheiro(str_replace('-','',$valor_mostrado))."</p>
			</div>
		";
	} // valor total > 0

	$devolvendo .= "
				<script>
					$('.relatoriowrap').on('click', function() {
						aid = $(this).attr('id').split('_')[1];
						aluguelFundamental(aid,1);
					});
				</script>
				</div>
			</div>
		</div>
	";

	$devolvendo .= "
		<script>
			$('#input_cortesias_utilizadas_nesse_aluguel').on('click', function () {
				$.ajax({
					type: 'POST',
					url: '".$dominio."/painel/devolucoes/includes/atualizacortesia.inc.php',
					data: {
						cortesias_utilizadas_nesse_aluguel: $('#input_cortesias_utilizadas_nesse_aluguel').val(),
						diaria: '".$diaria."',
						dias_previstos: '".$total_de_dias_previsao."',
						preco_diaria_excedente: '".$preco_diaria_excedente."',
						numero_real_de_dias_na_devolucao: '".$numero_real_de_dias_na_devolucao."',
						valor_km_a_mais: '".$valor_km_a_mais."',
						limpezavalor: '".$limpezavalor."',
						valor_adicional: '".$valor_adicional."',
						pagamentosaluguel: '".$pagamentosaluguel."',
						valor_total: '".$valor_total."'
					},
					success: function(precos) {
						$('#cortesias_utilizadas_card').html($('#input_cortesias_utilizadas_nesse_aluguel').val());
						$('#preco_final').html(precos['preco_final']);
						$('#valor_total').html(precos['valor_total']);
						$('#valor_mostrado').html(precos['valor_mostrado']);
					}
				});
			});

			/* aqui triga o input de cortesias pra atualizar os valores quando abre a confirmação de devolução*/
			$('#input_cortesias_utilizadas_nesse_aluguel').trigger('click');

			$('#placaspossiveis').on('change',function() {
				if ($(this).val()!='') {
					$('#placadefinida').html($(this).find('option:selected').text());
					$.ajax({
						type: 'POST',
						url: '".$dominio."/painel/devolucoes/includes/atualizacortesiasanualplaca.inc.php',
						data: {
							placa: $(this).val()
						},
						success: function(cortesiasanual) {
							$('#placadefinidaanual').html('A placa teve <b>'+cortesiasanual+'</b> cortesias utilizadas nos últimos 360 dias');
						}
					});
				} else {
					$('#placadefinida').html('".($placa_definida['placa']??0)."');
				}
			});
			$('#placaspossiveis').trigger('change');
		</script>
	";

	// $pwd = $_POST['pwd'];
	// if (empty($pwd)) {
	// 	RespostaRetorno('senha');
	// 	return;
	// } // pwd

	if (empty($vid) || empty($limpeza_devolucao) || empty($kilometragem_devolucao) ) {
		RespostaRetorno('vazio');
		return;

	} else {
		$encontraadmin = new ConsultaDatabase($uid);
		$encontraadmin = $encontraadmin->AdminInfo($uid);
		if ($encontraadmin!=0) {
			if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
				//$authadmin = new ConsultaDatabase($uid);
				//$authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);
				$authadmin = 1;

				if ($authadmin==0) {
					RespostaRetorno('authadmin');
					return;
				} else {
					$devolvendo .= "<div id='confirmardevolucao' class='confirmacao'>confirmar devolução</div>";
					$devolvendo .= "<div id='voltardevolucao' class='confirmacao'>voltar</div>";
					$devolvendo .= "</div><!-- informacao devolucao wrap -->";
					$devolvendo .= "
						<script>
		                                        $('#voltardevolucao').on('click', function() {
		                                                formulario.css('display', 'block');
								$('#informacaodevolucaowrap').html('');
		                                        });
					";

					// variaveis pra devolução
					$aid = $aluguel['aid'];
					$limpezavalor;
					$cortesias_devolucao;
					$kilometragem_devolucao;
					$pid = $placa_definida['pid']??0;
					$cortesias_utilizadas_nesse_aluguel;
					$vid = $aluguel['vid'];
					$limpeza_devolucao;
					$kilometragem_atual;
					$valor_adicional;
					$descricao_valor_adicional;
					$valor_total;
					$valor_mostrado;

					$devolvendo .= "
							$('#confirmardevolucao').on('click', function() {
								$.ajax({
					                                type: 'POST',
					                                dataType: 'html',
					                                async: true,
					                                url: '".$dominio."/painel/devolucoes/novo/includes/devolucaoconfirmada.inc.php',
					                                data: {
					                                        devolucaoconfirmada: 1,
										aid: '".$aid."',
										diaria: '".$diaria."',
										dias_previstos: '".$total_de_dias_previsao."',
										preco_diaria_excedente: '".$preco_diaria_excedente."',
										numero_real_de_dias_na_devolucao: '".$numero_real_de_dias_na_devolucao."',
										limpezavalor: '".$limpezavalor."',
										cortesias_devolucao: '".$cortesias_devolucao."',
										kilometragem_devolucao: '".$kilometragem_devolucao."',
										pid: $('#placaspossiveis').val() || '".$pid."',
										cortesias_utilizadas_nesse_aluguel: '".$cortesias_utilizadas_nesse_aluguel."',
										input_cortesias_utilizadas_nesse_aluguel: $('#input_cortesias_utilizadas_nesse_aluguel').val() || '".$cortesias_utilizadas_nesse_aluguel."',
										vid: '".$vid."',
										limpeza_devolucao: '".$limpeza_devolucao."',
										valor_km_a_mais: '".$valor_km_a_mais."',
										kilometragem_atual: '".$kilometragem_atual."',
										valor_adicional: '".$valor_adicional."',
										descricao_valor_adicional: '".$descricao_valor_adicional."',
										valor_total: '".$valor_total."',
										pagamentosaluguel: '".$pagamentosaluguel."',
										valor_mostrado: '".$valor_mostrado."'
					                                },
					                                success: function(possivel) {
					                                        if (possivel.includes('sucesso') == true) {

					                                        	retorno.html(possivel);
											retorno.append('<img id=\"sucessogif\" src=\"".$dominio."/img/sucesso.gif\">');

					                                                $('#adicionarpagamentowrap').css('display','inline-block');
											$('#adicionarpagamento').on('click', function() {
												$.ajax({
									                                type: 'POST',
									                                dataType: 'html',
									                                async: true,
									                                url: '".$dominio."/painel/cobrancas/includes/buscacobrancaaluguel.inc.php',
									                                data: { aid: '".$aid."' },
									                                success: function(coid) {
														if (coid!=0) {
															window.location.href='".$dominio."/painel/cobrancas/novo/?c='+coid;
														} else {
															window.location.href='".$dominio."/painel/cobrancas/';
														}
													}
												});
											});
					                                        } else {
					                                                formulario.css('display', 'inline-block');
											$('#informacaodevolucaowrap').html('');
										}
					                                }
					                        });
							});
					";

					$devolvendo .= '</script>';
				} // autorizacao
			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // encontraadmin
	} // campos preenchidos
} else {
	$devolvendo = ':((';
} // isset post submit

echo $devolvendo;

?>
