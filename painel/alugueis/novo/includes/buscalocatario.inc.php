<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['locatario'])) {
	$opcoes = [];
	$informacoes = '';
	$placas_disponiveis = [];

	$termo = '%'.$_POST['locatario'].'%';
	$locatarios = new ConsultaDatabase($uid);
	$locatarios = $locatarios->BuscaLocatario($termo);
	if ($locatarios[0]['lid']!=0) {
		foreach ($locatarios as $locatario) {

			$telefone = new Conforto($uid);
			$telefone = $telefone->FormatoTelefone($locatario['telefone'],'br');

			($locatario['associado']=='S') ? $associado = 'Desde '.strftime('%d de %B de %Y', strtotime($locatario['data_associado'])) : $associado = 'Não';
			$div = "
				<div id='locatarioswrap_".$locatario['lid']."' class='relatoriowrap opcaolocatario'>
					<div class='slotrelatoriowrap'>
						<div class='slotrelatorio'>
							<p class='headerslotrelatorio'><b>Nome:</b></p>
							<p class='infoslotrelatorio'>".$locatario['nome']."</p>
						</div>
					</div>
					<div class='slotrelatoriowrap'>
						<div class='slotrelatorio'>
							<p class='headerslotrelatorio'><b>Telefone:</b></p>
							<p class='infoslotrelatorio'>".$telefone."</p>
							<!-- <p class='headerslotrelatorio'><b>CPF:</b></p>
							<p class='infoslotrelatorio'>".$locatario['documento']."</p>
							<p class='headerslotrelatorio'><b>CNH:</b></p>
							<p class='infoslotrelatorio'>".$locatario['cnh']."</p> -->
						</div>
					</div>
					<div class='slotrelatoriowrap'>
						<div class='slotrelatorio'>
							<p class='headerslotrelatorio'><b>Email:</b></p>
							<p class='infoslotrelatorio'>".$locatario['email']."</p>
							<!-- <p class='headerslotrelatorio'><b>Associado:</b></p>
							<p class='infoslotrelatorio'>".$associado."</p> -->
						</div>
					</div>
				</div>
			";
			$encontrados[] = array(
				'nome'=>$locatario['nome'],
				'lid'=>$locatario['lid'],
				'div'=>$div
			);

		} // foreach
	} else {
		$encontrados = 'Locatário não encontrado. <span id="addlocatario" style="cursor:pointer;text-decoration:underline;">Adicionar</span>';
		$encontrados .= "
					<script>
						$('#addlocatario').on('click',function () {
							window.location.href='".$dominio."/painel/locatarios/novo/'
						});
					</script>
		";;
	}



	$resultado = $encontrados;

	header('Content-Type: application/json;');
	echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);


} // $_post

?>
