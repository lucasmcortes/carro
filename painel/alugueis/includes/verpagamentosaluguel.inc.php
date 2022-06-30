<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['aluguel'])) {
	$aid = $_POST['aluguel'];
	$ativo = $_POST['ativo'];
	$aluguel = new ConsultaDatabase($uid);
	$aluguel = $aluguel->AluguelInfo($aid);
} else {
	$aid = 0;
}// $_post

?>

<!-- items -->
<div class='items'>
	<?php tituloPagina('pagamentos efetuados'); ?>

	<div id='resultado' style='text-align:center;margin:0 auto;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
			<?php
				if ($aluguel['valor']>0) {
					$recebedor = new ConsultaDatabase($uid);
					$recebedor = $recebedor->AdminInfo($aluguel['uid']);
					$data_pagamento = new DateTime($aluguel['data']);
					echo "
						<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
							<p style='display:inline-block;'>
								<b>".Dinheiro($aluguel['valor'])."</b> em ".mb_strtolower($aluguel['forma'])." recebidos por ".$recebedor['nome']." no dia <b>".$data_pagamento->format('d/m/Y')." às ".$data_pagamento->format('H')."h".$data_pagamento->format('i')."</b> como pagamento inicial<br>
							</p>
						</div>
					";
				} // pagamento inicial > 0

				$pagamentos = new ConsultaDatabase($uid);
				$pagamentos = $pagamentos->PagamentosParciais($aid);
				if ($pagamentos[0]['papid']!=0) {
					foreach ($pagamentos as $pagamento) {
						$recebedor = new ConsultaDatabase($uid);
						$recebedor = $recebedor->AdminInfo($pagamento['uid']);
						$data_pagamento = new DateTime($pagamento['data']);
						echo "
							<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
								<p style='display:inline-block;'>
									<b>".Dinheiro($pagamento['valor'])."</b> em ".mb_strtolower($pagamento['forma'])." recebidos por ".$recebedor['nome']." no dia <b>".$data_pagamento->format('d/m/Y')." às ".$data_pagamento->format('H')."h".$data_pagamento->format('i')."</b> como pagamento parcial<br>
								</p>
							</div>
						";
					} // foreach
				} // papid > 0

				$devolucao = new ConsultaDatabase($uid);
				$devolucao = $devolucao->Devolucao($aid);
				if ($devolucao['deid']!=0) {
					$cobranca = new ConsultaDatabase($uid);
					$cobranca = $cobranca->CobrancaAluguel($aid);
					$parciais = new ConsultaDatabase($uid);
					$parciais = $parciais->CobrancaParcial($cobranca['coid']);
					if ($parciais[0]['copid']!=0) {
						foreach ($parciais as $pagamento) {
							$recebedor = new ConsultaDatabase($uid);
							$recebedor = $recebedor->AdminInfo($pagamento['recebedor']);
							$data_pagamento_parcial = new DateTime($pagamento['data_pagamento']);
							echo "
								<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
									<p style='display:inline-block;'><b>".Dinheiro($pagamento['valor'])."</b> em ".mb_strtolower($pagamento['forma'])."  como pagamento parcial da fatura em <b>".$data_pagamento_parcial->format('d/m/Y')."</b> recebidos por ".$recebedor['nome']."</p>
								</div>
							";
						} // pagamento parcial
					} // pagou parcial
				} // deid != 0
				if (isset($cobranca)) {
					if ($cobranca['tid']!=0) {
						$data_pagamento = new DateTime($cobranca['data_pagamento']);

						$somaparciais = new Conforto($uid);
						$somaparciais = $somaparciais->SomaParciais($cobranca['coid']);

						$pagamentosaluguel = new Conforto($uid);
						$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aluguel['aid']);
						$valor_mostrado = $cobranca['valor']-$pagamentosaluguel-$somaparciais;

						$recebedor = new ConsultaDatabase($uid);
						$recebedor = $recebedor->AdminInfo($cobranca['recebedor']);
						$transacao = new ConsultaDatabase($uid);
						$transacao = $transacao->Transacao($cobranca['tid']);

						$pagoateomomento = $pagamentosaluguel+$somaparciais;

						if ( ($somaparciais>0) || ($pagamentosaluguel>0) ) {
							echo "
								<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
									<p style='display:inline-block;'>Pagamento integral do residual  em ".mb_strtolower($transacao['forma'])." no valor de <b>".Dinheiro($valor_mostrado - ($valor_mostrado * ($transacao['desconto']/100)))."</b>
							";
									if ($transacao['desconto']>0) {
										echo "<b>com ".$transacao['desconto']."% de desconto (sobre o valor ".Dinheiro($cobranca['valor']-$pagoateomomento).")</b>";
									} // com desconto
							echo " no dia <b>".$data_pagamento->format('d/m/Y')."</b> às <b>".$data_pagamento->format('H')."h".$data_pagamento->format('i')."</b> recebidos por ".$recebedor['nome'];
							echo "
									</p>
								</div>
							";
						} else {
							echo "
								<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
									<p style='display:inline-block;'>Pagamento integral da fatura  em ".mb_strtolower($transacao['forma'])." por: <b>".Dinheiro(($valor_mostrado - ($valor_mostrado * ($transacao['desconto']/100))))."</b>
							";
									if ($transacao['desconto']>0) {
										echo "<b>com ".$transacao['desconto']."% de desconto</b>";
									} // com desconto
							echo " no dia <b>".$data_pagamento->format('d/m/Y')."</b> às <b>".$data_pagamento->format('H')."h".$data_pagamento->format('i')."</b> recebidos por ".$recebedor['nome'];
							echo "
									</p>
								</div>
							";
						} // se existiu pagamento parcial antes

					} else {
						// ainda não completou o valor total da cobrança
						if ( (isset($pagamentosaluguel)) && ($pagamentosaluguel>0) ) {
							echo "
								<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
									<p style='display:inline-block;'>Descontando <b>".Dinheiro($pagamentosaluguel)."</b> pagos até o momento</p>
								</div>
							";
							if (($valor_mostrado)>0) {
								echo "
									<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
									<p style='display:inline-block;' class='bloquinho'>Totalizando <b>".Dinheiro(str_replace('-','',$valor_mostrado))."</b> a serem pagos pelo locatário</p>
									</div>
								";
							} else {
								$devolvidolocatario = 1;
								echo "
									<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'>
										<p style='display:inline-block;' class='bloquinho'>Totalizando <b>".Dinheiro(str_replace('-','',$valor_mostrado))."</b> devolvidos ao locatário</p>
									</div>
								";
							} // valor_mostrado > 0
						} // valorinicial > 0
					} // tid > 0
				} // existe cobrança criada
			?>
		</div>

		<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
			<?php MontaBotao('ok','ok'); ?>
		</div>

	</div>

</div>
<!-- items -->

<script>
	abreVestimenta();
	$('#ok').on('click',function () {
		//aluguelFundamental(<?php echo $aid ?>,<?php echo $ativo ?>);
		$('#fecharvestimenta').trigger('click');
	});
</script>
