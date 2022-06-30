<?php
	require_once __DIR__.'/../../../includes/setup.inc.php';

        use \App\Files\CSV;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	if (isset($_SESSION['l_id'])) {

		// Os cabeçalhos do arquivo
		$informacoes_csv[] = array(
			'Data de início',
			'Data de devolução',
			'Locatário',
			'Modalidade',
			'Placa',
			'Diárias de cortesia/acionamento',
			'Total (R$)',
			'Número de registro'
		);

		$mesinicial = $_REQUEST['mes'];
		$anoinicial = $_REQUEST['ano'];

		$inicio_relatorio_string = $anoinicial.'-'.$mesinicial.'-01 00:00:00.000000';
		$inicio_relatorio = new DateTime($inicio_relatorio_string);
		$conclusao_relatorio = $inicio_relatorio->modify('+1 month');
		$conclusao_relatorio = new DateTime($conclusao_relatorio->format('Y-m-d H:i:s.u'));
		$conclusao_relatorio_string = $conclusao_relatorio->format('Y-m-d H:i:s.u');
		$inicio_relatorio->modify('-1 month');

		$informacoes_relatorio = [];

		$listacobrancas = new ConsultaDatabase($uid);
		$listacobrancas = $listacobrancas->ListaCobrancasEpoca($inicio_relatorio_string,$conclusao_relatorio_string);
		if ($listacobrancas[0]['coid']!=0) {
			foreach ($listacobrancas as $cobranca) {
				$aid = $cobranca['aid'];
				$aluguel = new ConsultaDatabase($uid);
				$aluguel = $aluguel->AluguelInfo($aid);

				$locatario = new ConsultaDatabase($uid);
				$locatario = $locatario->LocatarioInfo($aluguel['lid']);

				$placas = new ConsultaDatabase($uid);
				$placas = $placas->Placas($locatario['lid']);
				$placa_locatario = $placas[0]['placa'];

				$reserva = new ConsultaDatabase($uid);
				$reserva = $reserva->ReservaDevolvida($aluguel['aid']);
				if ($reserva['reid']!=0) {
					$ativacao = new ConsultaDatabase($uid);
					$ativacao = $ativacao->Ativacao($reserva['reid']);
					if ($ativacao['ativa']=='S') {
						$devolucao = new ConsultaDatabase($uid);
						$devolucao = $devolucao->Devolucao($reserva['aid']);
						$inicio_string = $reserva['inicio'];
						$devolucao_string = $devolucao['data'];
						$data_inicio = new DateTime($reserva['inicio']);
						$data_devolucao = new DateTime($devolucao['data']);
					} // reserva ativa
				} else {
					 $inicio_string = $aluguel['inicio'];
					 $devolucao_string = $aluguel['devolucao'];
					 $data_inicio = new DateTime($aluguel['inicio']);
					 $data_devolucao = new DateTime($aluguel['devolucao']);
				} // reservadevolvida

				// DATA DA DEVOLUCAO SE JA DEVOLVEU O VEICULO
				$devolucao = new ConsultaDatabase($uid);
				$devolucao = $devolucao->Devolucao($aid);
				$devolucao_string = $devolucao['data'];
				$data_devolucao = new DateTime($devolucao['data']);

				// DATA PREVISTA
				$data_inicio_aluguel_string = $inicio_string;
				$data_inicio_aluguel = new DateTime($data_inicio_aluguel_string);
				$data_devolucao_prevista_string = $devolucao_string;
				$data_devolucao_prevista = new DateTime($data_devolucao_prevista_string);

				$totalHoras = round((strtotime($devolucao_string) - strtotime($inicio_string))/3600, 0);
				$totalDiarias = ceil($totalHoras/24);
				($totalDiarias==0) ? $totalDiarias = 1 : $totalDiarias = $totalDiarias;

				$previsao_diarias = $data_inicio_aluguel->diff($data_devolucao_prevista);
				$total_de_dias_previsao = $previsao_diarias->format('%a');

				$preco_diaria_excedente = new Conforto($uid);
				$preco_diaria_excedente = $preco_diaria_excedente->ExcedenteData($aluguel['aid']);

				$diferenca_adicionais = $totalDiarias - $total_de_dias_previsao;

				if ($totalDiarias>$total_de_dias_previsao) {
					$data_prevista = $total_de_dias_previsao." x ".Dinheiro($aluguel['diaria'])." + ".$diferenca_adicionais." x ".Dinheiro($preco_diaria_excedente);
				} else {
					$data_prevista = $totalDiarias." x ".Dinheiro($aluguel['diaria']);
				} // se tem diárias excedentes

				if ($cobranca['cortesias']>0) {
					if ($cobranca['cortesias']>$total_de_dias_previsao) {
						if ($cobranca['cortesias']>=$totalDiarias) {
							$cortesias_exibidas = $totalDiarias;
							$exibe_preco_final = $data_prevista;
						} else {
							$cortesias_exibidas = $cobranca['cortesias'];
							$esclarecimento_diarias = $total_de_dias_previsao.' x '.Dinheiro($aluguel['diaria']). ' + '.$cobranca['cortesias']-$total_de_dias_previsao.' x '.Dinheiro($preco_diaria_excedente);
							$exibe_preco_final = $esclarecimento_diarias;
						}
					} else {
						if ($cobranca['cortesias']>=$totalDiarias) {
							$cortesias_exibidas = $totalDiarias;
							$exibe_preco_final = $data_prevista;
						} else {
							$cortesias_exibidas = $cobranca['cortesias'];
							$exibe_preco_final = $cobranca['cortesias'].' x '.Dinheiro($aluguel['diaria']);
						}
					}
				} else {
					$cortesias_exibidas = 0;
					$exibe_preco_final = Dinheiro($preco_final??0);
				} // esclarecimento das cortesias

				($aluguel['particular']==1) ? $modalidade = 'Aluguel particular' : $modalidade = 'Aluguel para associado';

				$informacoes_csv[] = array(
					$data_inicio->format('d/m/Y'),
					$data_devolucao->format('d/m/Y'),
					$locatario['nome'],
					$modalidade,
					$placa_locatario,
					$cortesias_exibidas." x ".Dinheiro($aluguel['diaria']),
					$cortesias_exibidas*$aluguel['diaria'],
					$aluguel['aid']+$acrescimoaid
				);

				$informacoes_relatorio[] = array(
					'Data de início'=>$data_inicio->format('d/m/Y'),
					'Data de devolução'=>$data_devolucao->format('d/m/Y'),
					'Locatário'=>$locatario['nome'],
					'Modalidade'=>$modalidade,
					'Placa'=>$placa_locatario,
					'Diárias de cortesia/acionamento'=>$cortesias_exibidas." x ".Dinheiro($aluguel['diaria']),
					'Total (R$)'=>$cortesias_exibidas*$aluguel['diaria'],
					'Número de registro'=>$aluguel['aid']+$acrescimoaid
				);
			} // foreach cobranca

			///////////// CRIA CSV E JÁ CONVERTE PRA XLSX COM PHPOFFICE
			$file_name = 'relatorio_'.mb_strtolower(Acentuadas(strftime('%B', strtotime($inicio_relatorio_string)))).'_'.$inicio_relatorio->format('Y');
			$arquivo_csv = __DIR__.'/'.$file_name.'.csv';
			$sucesso = CSV::criarArquivo($arquivo_csv,$informacoes_csv,',');
			$spreadsheet = new Spreadsheet();
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

			/* Set CSV parsing options */

			$reader->setDelimiter(',');
			$reader->setEnclosure('"');
			$reader->setSheetIndex(0);

			/* Load a CSV file and save as a XLS */

			$spreadsheet = $reader->load($arquivo_csv);
			$writer = new Xlsx($spreadsheet);
			$arquivo_xlsx = __DIR__.'/'.$file_name.'.xlsx';
			$sheet = $spreadsheet->getActiveSheet();

			$spreadsheet->getActiveSheet()->getStyle('1:1')->getFont()->setBold(true);
			// Arruma o tamanho das colunas pra mostra tudo
			foreach ($sheet->getColumnIterator() as $column) {
				$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
			}

			$writer->save($arquivo_xlsx);

			$spreadsheet->disconnectWorksheets();
			unset($spreadsheet);

			// Faz download do xlsx e deleta o arquivo do servidor
			if (file_exists($arquivo_xlsx))
			{
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($arquivo_xlsx));
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($arquivo_xlsx));
				ob_clean();
				flush();
				readfile($arquivo_xlsx);
				unlink($arquivo_csv);
				unlink($arquivo_xlsx);
				exit;
			} // download xlsx


			//////////// PRA XLS SEM PHPOFFICE
			// // Filter Customer Data
			// function filterCustomerData(&$str) {
			// 	$str = preg_replace("/\t/", "\\t", $str);
			// 	$str = preg_replace("/\r?\n/", "\\n", $str);
			// 	if (strstr($str, '"'))
			// 	$str = '"' . str_replace('"', '""', $str) . '"';
			// }
			//
			// // File Name & Content Header For Download
			// $file_name = 'relatorio_'.mb_strtolower(Acentuadas(strftime('%B', strtotime($inicio_relatorio_string)))).'_'.$inicio_relatorio->format('Y').'.xls';
			// header("Content-Disposition: attachment; filename=\"$file_name\"");
			// header("Content-Type: application/vnd.ms-excel");
			//
			// //To define column name in first row.
			// $column_names = false;
			// // run loop through each row in $informacoes_relatorio
			// foreach ($informacoes_relatorio as $row) {
			// 	if (!$column_names) {
			// 		echo implode("\t", array_keys($row)) . "\n";
			// 		$column_names = true;
			// 	}
			// 	// The array_walk() function runs each array element in a user-defined function.
			// 	array_walk($row, 'filterCustomerData');
			// 	echo implode("\t", array_values($row)) . "\n";
			// }
		} else {
			echo "Nenhum registro encontrado para o período especificado.";
		} // coid != 0

		exit;

	} else {
		redirectToLogin();
	} // isset uid
?>
