<?php
require_once __DIR__.'/setup.inc.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('RespostaRetorno')) {
	function RespostaRetorno($retorno) {
		$retornos = array(
			'acionamento'=>'Acionamento não adicionado',
			'adminencontrado'=>'Administrador não encontrado',
			'adminexistente'=>'Administrador existente',
			'adminnivel'=>'Permissão insuficiente',
			'ano'=>'Digite o ano do veículo',
			'authadmin'=>'Administrador não autorizado',
			'ativacao'=>'Ativamento não registrado',
			'caracterizado'=>'O veículo é caracterizado?',
			'categoria'=>'Defina a categoria do veículo',
			'caucao'=>'Defina o valor do caução',
			'caucaoforma'=>'Escolha a forma de pagamento do caução',
			'chassi'=>'Insira o número do chassi',
			'cnh'=>'Insira o número da CNH',
			'cnhvalidade'=>'Insira a validade da CNH',
			'cnhimagem'=>'Insira a imagem da CNH',
			'coid'=>'Escolha uma fatura a ser paga',
			'completo'=>'O veículo é completo?',
			'cor'=>'Digite a cor do veículo',
			'cpfinvalido'=>'Confira o número do CPF',
			'cpf'=>'Insira o número do CPF corretamente',
			'datadevolucao'=>'Escolha uma data de devolução',
			'datahorario'=>'Escolha um horário',
			'datanascimento'=>'Preencha a data do seu nascimento',
			'datainicio'=>'Escolha uma data de início',
			'datareal'=>'A data de devolução deve ser após a data de início',
			'descvaladicional'=>'Descreva o valor adicional',
			'email'=>'Insira o email corretamente',
			'erro'=>'Erro',
			'hojereal'=>'O aluguel precisa ser à partir de hoje',
			'horarioreal'=>'Escolha um horário à partir de agora',
			'kmigual'=>'O veículo tinha a mesma kilometragem quando foi alugado',
			'kmpermitida'=>'Insira a kilometragem permitida para o aluguel',
			'lid'=>'Escolha um locatário',
			'marca'=>'Digite a marca do veículo',
			'modelo'=>'Digite o modelo do veículo',
			'niveladmin'=>'Escolha um nível de acesso para o administrador',
			'nomecadastro'=>'Preencha seu nome',
			'nomelocatario'=>'Defina o nome do locatário',
			'novamente'=>'Tente novamente',
			'pagforma'=>'Escolha uma forma de pagamento',
			'paginicial'=>'Defina uma quantia para o pagamento inicial',
			'parcialsuperior'=>'O valor do pagamento parcial da fatura precisa ser de até preço do valor residual',
			'pid'=>'O locatário precisa de uma placa cadastrada para o acionamento',
			'placaassociado'=>'Insira uma placa para o associado',
			'placaveiculo'=>'Insira uma placa no formato válido para continuar (ABC-1234)',
			'portas'=>'Defina o número de portas do veículo',
			'potencia'=>'Defina a potência do motor',
			'regaluguel'=>'Aluguel não adicionado',
			'regassociado'=>'Status de associação não adicionado',
			'regcaucao'=>'Caução não registrado',
			'regcnhimagem'=>'Imagem da CNH não registrada',
			'regcobranca'=>'Fatura não criada',
			'regcortesias'=>'Cortesias utilizadas não registradas',
			'regdevolucao'=>'Devolução não adicionada',
			'regendereco'=>'Endereço não adicionado',
			'regguid'=>'Identificador único não adicionado',
			'reghabilitacao'=>'Habilitação não adicionada',
			'regimagemdoc'=>'Imagem do documento não adicionada',
			'regimagemfoto'=>'Foto do veículo não adicionada',
			'regkm'=>'Kilometragem não registrada',
			'reglimpeza'=>'Status de limpeza não atualizado',
			'reglocatario'=>'Locatário não adicionado',
			'reglocatarioexistente'=>'Locatário existente',
			'regmanutencao'=>'Manutenção não adicionada',
			'regmanutencaoativacao'=>'Ativação do agendamento da manutenção não registrada',
			'regplaca'=>'Placa não adicionada',
			'regplacaativacao'=>'Placa não ativada',
			'regpaginicial'=>'Pagamento inicial não registrado',
			'regparcial'=>'Pagamento parcial não registrado',
			'regreservamanutencao'=>'Agendamento do agendamento não registrado',
			'regretorno'=>'Retorno não adicionado',
			'regtransacao'=>'Pagamento não registrado',
			'regvaloradicional'=>'Valor adicional não registrado',
			'regveiculo'=>'Veículo não adicionado',
			'regvobs'=>'Características do veículo não registradas',
			'renavam'=>'Insira o número do RENAVAM',
			'reserva'=>'Reserva não registrada',
			'senha'=>'Digite sua senha',
			'senhaadmin'=>'Escolha uma senha para o administrador',
			'statusassociado'=>'Escolha o status de associação do locatário',
			'submit'=>':))',
			'sucessocadastro'=>'Cadastro realizado com sucesso. Confirme o email para utilizar sua conta.',
			'sucessoaluguel'=>'Aluguel de veículo registrado com sucesso',
			'sucessodevolucao'=>'Devolução de veículo registrada com sucesso',
			'sucessolocatario'=>'Locatário adicionado com sucesso',
			'sucessomanutencao'=>'Manutenção adicionada com sucesso',
			'sucessoparcial'=>'Pagamento parcial registrado com sucesso',
			'sucessoplacamod'=>'Placa cadastrada com sucesso',
			'sucessoretorno'=>'Retorno adicionado com sucesso',
			'sucessotransacao'=>'Fatura quitada com sucesso',
			'sucessoveiculo'=>'Veículo cadastrado com sucesso',
			'telefone'=>'Insira o número do telefone corretamente',
			'validadecnh'=>'Verifique a validade da habilitação do locatário',
			'valorretorno'=>'Defina o custo da manutenção',
			'vazio'=>'Preencha todos os campos',
			'veiculo'=>'Escolha um veículo',
			'veiculoexistente'=>'Veículo já existente',
			'vid'=>'Escolha um veículo',
		);

		echo $retornos[$retorno];
	} // RespostaRetorno
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('VamosComecar')) {
	function VamosComecar() {
		global $dominio;

		echo "
			<div style='padding:3%;'>
				<div style='display:flex;flex-direction:column;align-items:center;gap:13px;'>
					<p style='text-align: center;font-size: 18px;line-height: 23px;background-color: var(--verdedois);padding: 1% 3%;border-radius: var(--radius);'>
						Cadastre um veículo para começar
					</p>
					<!--<img style='max-width:180px;width:100%;height:auto;' src='".$dominio."/img/addveiculoicon.png'></img>-->
					<a style='padding:1.8% 5%;border-radius:var(--radius);border:3px solid var(--amarelo);' href='".$dominio."/painel/veiculos/novo'>
						<p style='text-align:center;font-size:18px;line-height:23px;'>
							Cadastrar
						</p>
					</a>
				</div>
			</div>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('NomeCliente')) {
	function NomeCliente($nome) {
                $nome_cliente = explode(' ',$nome);
        	$nome_cliente = ucfirst(mb_strtolower($nome_cliente[0]));
		return $nome_cliente;
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// criar pasta pro produto
if (!function_exists('criarPastaProduto')) {
	function criarPastaProduto($pasta,$delete=1) {
		// cria pasta
		if (!file_exists($pasta)) {
			// se não existe ainda
			mkdir($pasta, 0755, true);
		} else {
			// se já existe a pasta
			if ($delete==1) { // padrão; pra deixar sem deletar, tem que chamar criarPasta($pasta,0)
				// deleta todos os arquivos da pasta
				foreach(glob($pasta.'/*.*') as $backup_de_antes){
					unlink($backup_de_antes);
				}
			}
		}
		chmod($pasta, 0755);

		// bota uma index que puxa o arquivo de ver o produto
		$index_referencial = __DIR__.'/../loja/includes/abreverproduto.php';
		$index_nova = $pasta.'/index.php';
		if (!is_file($index_nova)) {
			copy($index_referencial, $index_nova);
		}
	} // criar pasta pro produto
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('CoresTranslator')) {
	function CoresTranslator($cor) {
		$corestranslator = array(
			'azul'=>'#40C0CB',
			'verde'=>'#CFFD90',
			'branco'=>'#FFFFFF',
			'preto'=>'#000000',
			'azul claro'=>'#BAE4E5'
		);
		echo "<p class='coresloja info' data-opcao='".$cor."' aria-label='".ucwords($cor)."' style='background-color:".$corestranslator[$cor]."'></p>";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('CoresTranslatorOpcao')) {
	function CoresTranslatorOpcao($cor) {
		$corestranslator = array(
			'azul'=>'#40C0CB',
			'verde'=>'#CFFD90',
			'branco'=>'#FFFFFF',
			'preto'=>'#000000',
			'azul claro'=>'#BAE4E5'
		);
		echo "<p class='opcoes coresloja info' data-opcao='".$cor."' aria-label='".ucwords($cor)."' style='background-color:".$corestranslator[$cor]."'></p>";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('Icone')) {
	function Icone($id,$arialabel,$arquivo) {
		global $dominio;

		echo "
			<div id='".$id."' class='icone'>
				<span class='info' aria-label='".$arialabel."'>
					<img class='icone' src='".$dominio."/img/".$arquivo.".png'></img>
				</span>
			</div>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('IconeMenu')) {
	function IconeMenu($id,$arialabel,$arquivo) {
		global $dominio;

		echo "
			<div id='".$id."' class='iconemenu atalhosuperior'>
				<!-- <span class='infomenu' aria-label='".$arialabel."'> -->
					<img class='iconemenu' src='".$dominio."/img/".$arquivo.".png'></img>
					<p class='legendaatalhosuperior'>".ucfirst($arialabel)."</p>
				<!-- </span> -->
			</div>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('NenhumRegistro')) {
	function NenhumRegistro() {
		echo "
			<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
				<!-- container -->
				<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
					<p style='background-color:var(--verdeclaro);padding:8% 5%;text-align:center;'>
						Nenhum registro para exibir
					</p>
				</div>
			</div>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('NenhumaDevolucao')) {
	function NenhumaDevolucao() {
		echo "
			<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
				<!-- container -->
				<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
					<p style='background-color:var(--verdeclaro);padding:8% 5%;text-align:center;'>
						Nenhum veículo para devolver
					</p>
				</div>
			</div>
		";
	} // Função
} // Existe
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('NenhumRetorno')) {
	function NenhumRetorno() {
		echo "
			<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
				<!-- container -->
				<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
					<p style='background-color:var(--verdeclaro);padding:8% 5%;text-align:center;'>
						Nenhum veículo para retornar da manutenção
					</p>
				</div>
			</div>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('SelectFormaPagamento')) {
	function SelectFormaPagamento($label,$id) {
		echo "
			<label>".$label."</label>
			<select id='".$id."'>
				<option value=''>-- ESCOLHA --</option>
				<option value='1'>Dinheiro</option>
				<option value='2'>Débito</option>
				<option value='3'>Cartão de crédito</option>
				<option value='4'>Pix</option>
				<option value='5'>Cortesia</option>
				<option value='6'>Cheque</option>
				<option value='7'>Promissória</option>
				<option value='8'>Outro</option>
			</select>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('Switch')) {
	function SwitchBox($id,$checked,$unchecked) {
		echo "
			<div style='margin:3px auto;'>
				<p style='vertical-align:super;margin:0px 8px;display:inline-block;font-size:13px;'>".$unchecked."</p>
				<div style='min-width:55px;max-width:55px;display:inline-block;'>
					<label class='switch'>
						<input id='".$id."' type='checkbox'>
						<span class='slider round'></span>
					</label>
				</div>
				<p style='vertical-align:super;margin:0px 8px;display:inline-block;font-size:13px;'>".$checked."</p>
			</div>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('Sanitiza')) {
	function Sanitiza($preco) {
		return str_replace(array('.',',','%'),array(',','.',''),$preco);
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('Kilometragem')) {
	function Kilometragem($km) {
		if ($km!=1) {
			return str_replace(',','.',number_format($km)).'km';
		} else {
			return 'Livre';
		}
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('Dinheiro')) {
	function Dinheiro($dinheiro) {
		return 'R$'.number_format($dinheiro, 2, ',', '.');
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('BotaoPainel')) {
	function BotaoPainel($nome,$id,$url) {
		global $dominio;

		echo "
			<p id='".$id."' class='painelbutton'>
				".$nome."
			</p>
			<script>
				$('#".$id."').on('click',function () {
					window.location.href='".$dominio."/painel/".$url."'
				});
			</script>
		";

	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('BotaoFechar')) {
	function BotaoFechar() {
		echo "
			<div id='fechar' class='fechar' style='right:0;'>
				<div class='fecharspanwrap' style='left:18%;'>
					<span style='transform:rotate(-45deg);'></span>
					<span style='transform:rotate(45deg);'></span>
				</div>
			</div>
			<script>fechaFundamental();</script>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('BotaoFecharVestimenta')) {
	function BotaoFecharVestimenta() {
		echo "
			<div id='fecharvestimenta' class='fechar' style='left:0;'>
				<div class='fecharspanwrap' style='left:36%;'>
					<span style='transform:rotate(-45deg);'></span>
					<span style='transform:rotate(45deg);'></span>
				</div>
			</div>
			<script>fechaVestimenta();</script>
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('EnviandoImg')) {
	function EnviandoImg() {
		echo "<div id='enviando'><div id='enviandospinner'></div></div>";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Função que força o redirect quando se dá o acesso de uma página protegida por login
if (!function_exists('redirectToLogin')) {
	function redirectToLogin($caminho='entrar') {
		global $dominio;

		echo "<script>window.location.href = '".$dominio."/".$caminho."';</script>";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('unlinkTemp')) {
	function unlinkTemp($path) {
		array_map('unlink', glob($path.'*.{jpg,jpeg,png,gif,pdf}', GLOB_BRACE));
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// resizes an image to fit a given width in pixels.
// works with BMP, PNG, JPEG, and GIF
// $file is overwritten
// https://stackoverflow.com/questions/7553247/php-image-resizing
if (!function_exists('fit_image_file_to_width')) {
function fit_image_file_to_width($file, $w, $mime = 'image/jpeg') {

	// Arruma orientação
	// https://stackoverflow.com/questions/12774411/php-resizing-image-on-upload-rotates-the-image-when-i-dont-want-it-to
	$exif = exif_read_data($file);
	if ($exif && isset($exif['Orientation'])) {
		$orientation = $exif['Orientation'];
		if ($orientation != 1) {
			$img = imagecreatefromjpeg($file);
			$deg = 0;
			switch ($orientation) {
				case 3:
					$deg = 180;
					break;
				case 6:
					$deg = 270;
					break;
				case 8:
					$deg = 90;
					break;
			} // switch

			if ($deg) {
				$img = imagerotate($img, $deg, 0);
			} // if

			imagejpeg($img, $file, 95);
		} // if
	} // if
	// Arruma orientação

	list($width, $height) = getimagesize($file);
	$newwidth = $w;
	$newheight = $w * $height / $width;

	switch ($mime) {
		case 'image/jpeg':
			$src = imagecreatefromjpeg($file);
			break;
		case 'image/png';
			$src = imagecreatefrompng($file);
			break;
		case 'image/bmp';
			$src = imagecreatefromwbmp($file);
			break;
		case 'image/gif';
			$src = imagecreatefromgif($file);
			break;
	} // switch

	$dst = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	switch ($mime) {
		case 'image/jpeg':
			imagejpeg($dst, $file);
			break;
		case 'image/png';
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
			imagepng($dst, $file);
			break;
		case 'image/bmp';
			imagewbmp($dst, $file);
			break;
		case 'image/gif';
			imagegif($dst, $file);
			break;
	} // switch
	imagedestroy($dst);

} // function fit_image_file_to_width
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função que generaliza inputs
if (!function_exists('InputGeral')) {
function InputGeral($inputPlaceholder, $inputName, $inputId, $inputType, $inputTamanho,$inputLabel=1) {

	global $dominio;

	// $inputPlaceholder = placeholder do input
	// $inputName = name do input
	// $inputId = ID do input
	// $inputType = email, password, text etc
	// $inputTamanho = % da largura do div que cobre o input (100 pra linha toda e 45 pra dividir a linha entre 2 etc)
	// $inputLabel = 1 (opcional) // quando é !=1 fica sem o label

	// EXEMPLO DE USO NA PÁGINA
	// echo "<div style='min-width:100%;max-width:100%;margin:0 auto;margin-bottom:13px;float:left;text-align:center;'>";
	// 		InputGeral('Produto', 'produto', 'produto', 'text', '100');
	//		InputGeral('Preço', 'preco', 'preco', 'text', '100');
	// echo "</div>";

	// input
	echo "
		<!-- input ".$inputName." -->
		<div id='input_".$inputId."_wrap' style='max-width:".$inputTamanho."%;min-width:".$inputTamanho."%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
	";

	if ($inputLabel == 1) {
		echo "
			<label>".$inputPlaceholder."</label>
		";
	}

	echo "
			<input style='max-width:".$inputTamanho."%;min-width:".$inputTamanho."%;' type='".$inputType."' placeholder='".$inputPlaceholder."' name='".$inputName."' id='".$inputId."'>
		</div>
		<!-- input -->
	";

}

// fim função que generaliza os inputs
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('InputDatas')) {
	function InputDatas() {
		global $dominio;

		echo "
			<div style='display:flex;justify-content:space-between;gap:2%;'>
				<div id='iniciowrap' style='flex:1;'>
					<label style='width:100%;'>Início</label>
					<div id='inicioinner' style='min-width:85%;max-width:85%;display:inline-block;'>
						<input type='text' id='inicio' placeholder='DD/MM/AAAA'></input>
					</div>
					<div id='calendarioinicio' class='abrecalendario'>
						<span class='info' aria-label='calendário'>
							<img style='width:100%;max-width:26px;height:auto;' src='".$dominio."/img/calendarioformicon.png'></img>
						</span>
					</div>
				</div> <!-- iniciowrap -->

				<div id='devolucaowrap' style='flex:1;'>
					<label style='width:100%;'>Devolução</label>
					<div id='devolucaoinner' style='min-width:85%;max-width:85%;display:inline-block;'>
						<input type='text' id='devolucao' placeholder='DD/MM/AAAA'></input>
					</div>
					<div id='calendariodevolucao' class='abrecalendario'>
						<span class='info' aria-label='calendário'>
							<img style='width:100%;max-width:26px;height:auto;' src='".$dominio."/img/calendarioformicon.png'></img>
						</span>
					</div>
				</div> <!-- devolucaowrap -->
			</div> <!-- datas -->
		";
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função que mostra um botão de ação
if (!function_exists('BotaoAcaoGeral')) {
function BotaoAcaoGeral($texto_botao, $tamanho_largura, $url_acao, $posicao=0) {

	global $dominio;

	// $texto_botao = texto do botão
	// $tamanho_largura = número pro tamanho da largura
	// $url_acao = href da <a> sem o $dominio e sem barra depois
	// $posicao = 0 = centro // 270 = tamanh o à esquerda

	if ($posicao != 0) {
		// pra funcionar o posicionamento à esquerda tem que sobrepor o valor que vem da chamada da função
		$tamanho_largura = 100;
		$tamanho_externo = $posicao."px;";

		$posicao_align = "padding-left:7%;";
		$posicao_display = "";
		$float_inside = "float:left;";
	} else if ($posicao == 0) {
		$tamanho_externo = "100%;";
		$posicao_align = "text-align:center;";
		$posicao_display = "display:inline-block;";
		$float_inside = "";
	}

	// botão ação
	echo "
		<!-- botão chamada ".$tamanho_largura."% -->
		<div style='min-width:".$tamanho_externo."max-width:".$tamanho_externo."margin:1% auto;float:left;".$posicao_align."'>
			<div style='min-width:".$tamanho_largura."%;max-width:".$tamanho_largura."%;margin:0 auto;".$float_inside."".$posicao_display."'>
				<p style='cursor:pointer;font-size:21px;margin:0 auto;border-radius:var(--circulo);padding:9px;padding-top:5px;background-color:#3b0055;'>
					<a href='".$dominio."/".$url_acao."/?c=c' style='color:#eeefca;text-decoration:none;'>
						".$texto_botao."
					</a>
				</p>
			</div>
		</div>
		<!-- botão chamada ".$tamanho_largura."% -->
	";

}

// fim função que mostra um botão de ação
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função que encode/decode string
if (!function_exists('Codificacao')) {
function Codificacao($atitude,$string) {

	// exemplo
	// $codigada = Codificacao('encode','amor');
	// $decodigada = Codificacao('decode',$codigada);

	$encodes = 7;

	if ($atitude == 'encode') {
		// encoding
		for ($i = 0;$i<=$encodes;$i++) {
			if ($i == 0) {
				$encoded = base64_encode($string);
			} else if ($i > 0) {
				$encoded = base64_encode($encoded);
			}
		} // for

		return $encoded;

	} else if ($atitude == 'decode') {
		// decoding
		for ($i = 0;$i<=$encodes;$i++) {
			if ($i == 0) {
				$decoded = base64_decode($string);
			} else if ($i > 0) {
				$decoded = base64_decode($decoded);
			}
		}

		return $decoded;

	} // atitude

}
// fim da função que encode/decode string
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função que tira os acentos de uma string
if (!function_exists('Acentuadas')) {
function Acentuadas($inputString) {
	$acentuadas = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y');

	$stringAtualizada = strtr($inputString, $acentuadas);

	return $stringAtualizada;
}
// fim da função que tira os acentos de uma string
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função pra onde ir depois de logar
if (!function_exists('eDepois')) {
function eDepois($next_url) {
	header("Location: ".$dominio."/entrar/?sequencia=".$next_url."");
	ob_end_flush();
	exit();
}
// fim da função pra onde ir depois de logar
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função de montar botão
if (!function_exists('MontaBotao')) {
function MontaBotao($nome,$idbotao) {
	echo "
		<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
			<p class='montabotao sombraabaixo' id='".$idbotao."'>
				".$nome."
			</p>
		</div>
	";
}
// fim da função de montar botão
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função de montar botão
if (!function_exists('MontaBotaoSecundario')) {
function MontaBotaoSecundario($nome,$idbotao) {
	echo "
		<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
			<p class='montabotaosecundario sombraabaixo' id='".$idbotao."'>
				".$nome."
			</p>
		</div>
	";
}
// fim da função de montar botão
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função de escrever título no cabeçalho da página
if (!function_exists('tituloPagina')) {
function tituloPagina($titulo) {
	echo "
		<p class='titulopagina'>
			".$titulo."
		</p>
	";
}
// fim da função de título
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função de escrever título no cabeçalho da página
if (!function_exists('tituloCarro')) {
function tituloCarro($modelo) {
	echo "
		<p class='titulocarro' style='color:var(--preto);'>
			".$modelo."
		</p>
	";
}
// fim da função de título
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função de escrever título na sessao do relatório
if (!function_exists('tituloRelatorio')) {
function tituloRelatorio($nome) {
	echo "
		<p class='titulorelatorio' style='color:var(--preto);'>
			".$nome."
		</p>
	";
}
// fim da função de título
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// função que carrega os javascripts
if (!function_exists('carregaJS')) {
function carregaJS() {
	global $dominio;

	echo '<script src="'.$dominio.'/includes/js.php" type="text/javascript"></script>';

}
// fim da função que carrega os javascripts
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('CalendarioVeiculo')) {
function CalendarioVeiculo($month,$year,$vid) {
	global $uid;
	global $agora;

	$disponibilidade_veiculo = new Conforto($uid);
	$disponibilidade_veiculo = $disponibilidade_veiculo->Possibilidade($vid);
	$disponibilidade_veiculo = $disponibilidade_veiculo['disponibilidade'];

	// https://davidwalsh.name/php-calendar
	/* draw table */
	$mes = $month;
	$ano = $year;

	$calendar = '
		<div id="mes_'.$mes.'_'.$ano.'" class="mes-wrap">
			<table cellpadding="0" cellspacing="0" class="calendar" style="background-color:var(--branco);">
	';

	/* table headings */
	$headings = array('D','S','T','Q','Q','S','S');
	$calendar.= '
		<tr class="calendar-row-headings">
			<td class="calendar-day-head">
				'.implode('</td><td class="calendar-day-head">',$headings).'
			</td>
		</tr>
	';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$semana_numero_data = new DateTime($ano.'-'.$mes.'-01');
	$semana_inicial = $semana_numero_data->format("W");
	$calendar.= '<tr id="semana_'.$semana_inicial.'" class="calendar-row">';
	/* print "blank" days until the first of the current week */
	for ($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
		if (mb_strlen($list_day) == 1) {
			$dia = '0'.$list_day;
		} else {
			$dia = $list_day;
		} // dia strlen

		if ( ($agora->format('d')==$list_day) && ($agora->format('m')==$month) && ($agora->format('Y')==$year) ) {
			$hoje = true;
		} else {
			$hoje = false;
		}

		$data_atual_calendario = $ano.'-'.$mes.'-'.$dia;

		if ($data_atual_calendario < $agora->format('Y-m-d')) {
			$antes = true;
		} else {
			$antes = false;
		}

		// feriados do ano
		$arquivo_feriados = __DIR__.'/feriados/feriados_'.$ano.'.txt';
		if (is_file($arquivo_feriados)) {
			$feriados = json_decode(file_get_contents($arquivo_feriados),true);
		} // tem o arquivo com os feriados

		if (isset($disponibilidade_veiculo[$data_atual_calendario]) ) {
			if ($disponibilidade_veiculo[$data_atual_calendario]['status']=='Disponível') {
				// se tá disponivel nesse dia
				if ($hoje) {
					$calendar.= '<td class="escolhido calendar-day-disponivel hoje">';
					$calendar .= '<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>';
				} else {
					if ($antes) {
						$calendar.= '<td class="escolhido calendar-day-antes">';
						$calendar .= '<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>';
					} else {
						$calendar.= '<td class="escolhido calendar-day-disponivel">';
						$calendar .= '<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>';
					}
				} // hoje true
			} else {
				// se tá com outro status nesse dia
				if ($disponibilidade_veiculo[$data_atual_calendario]['status']=='Devolvido') {
					if ($hoje) {
						$calendar.= '<td class="escolhido calendar-day-devolvido hoje">';
						$calendar .= '
						<span class="info" aria-label="'.$disponibilidade_veiculo[$data_atual_calendario]['status'].'">
							<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>
						</span>';
					} else {
						$calendar.= '<td class="escolhido calendar-day-disponivel">';
						$calendar .= '<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>';
					} // hoje true
				} else { // não é devolvido
					if ($hoje) {
						$calendar.= '<td class="escolhido calendar-day-ocupado hoje">';
						$calendar .= '
						<span class="info" aria-label="'.$disponibilidade_veiculo[$data_atual_calendario]['status'].'">
							<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>
						</span>';
					} else {
						$calendar.= '<td class="escolhido calendar-day-ocupado">';
						$calendar .= '
						<span class="info" aria-label="'.$disponibilidade_veiculo[$data_atual_calendario]['status'].'">
							<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>
						</span>';
					} // hoje true
				} // devolvido
			} // disponivel
		} else {
			// não tem disponibilidade definida, e tá disponível por isso

			// feriados
			$feriado = '';
			if (isset($feriados[$data_atual_calendario])) {
				if ($feriados[$data_atual_calendario]['dia']==$data_atual_calendario) {
					$feriado = 'oba';
				} // se é feriado
			} // se tem o dia como feriado

			if ($hoje) {
				$calendar.= '<td class="escolhido calendar-day-disponivel hoje">';
				if ($feriado!='') {
					$calendar .= '
						<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number feriado">
							<span class="info" aria-label="'.$feriados[$data_atual_calendario]['nome'].'">
								'.$list_day.'
							</span>
						</div>

					';
				} else {
					$calendar .= '<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>';
				}
			} else {
				if ($antes) {
					$calendar.= '<td class="escolhido calendar-day-antes">';
					if ($feriado!='') {
						$calendar .= '
							<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number feriado">
								<span class="info" aria-label="'.$feriados[$data_atual_calendario]['nome'].'">
									'.$list_day.'
								</span>
							</div>

						';
					} else {
						$calendar .= '<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>';
					}
				} else {
					$calendar.= '<td class="escolhido calendar-day-disponivel">';
					if ($feriado!='') {
						$calendar .= '
							<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number feriado">
								<span class="info" aria-label="'.$feriados[$data_atual_calendario]['nome'].'">
									'.$list_day.'
								</span>
							</div>

						';
					} else {
						$calendar .= '<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" class="day-number">'.$list_day.'</div>';
					}
				}
			} // hoje true
		} // mes

		$calendar.= '</td>';

		$semana_numero_data = new DateTime($ano.'-'.$mes.'-'.$dia);
		$semana = $semana_numero_data->format("W");
		if ($running_day == 6):
			$calendar.= '</tr>';
			if (($day_counter+1) != $days_in_month):
				$semana++;
				$calendar.= '<tr id="semana_'.$semana.'" class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if ($days_in_this_week < 8):
		for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table></div>';

	/* all done, return result */
	return $calendar;
} // draw calendar
} // Exists

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('CalendarioDisponibilidade')) {
function CalendarioDisponibilidade($month,$year,$disponibilidades) {
	global $uid;
	global $agora;

	// https://davidwalsh.name/php-calendar
	/* draw table */
	$mes = $month;
	$ano = $year;

	$calendar = '<div id="mes_'.$mes.'_'.$ano.'" class="mes-wrap">';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	$data_escolhida = $ano.'-'.$mes;
	$calendar .= "<h2 style='min-width:100%;max-width:100%;display:inline-block;margin:1.2% auto;margin-bottom:3%;vertical-align:middle;'>
		".ucfirst(strftime('%B',strtotime($data_escolhida)))." de ".$ano."
	</h2>
	";
	/* keep going with days.... */
	for ($list_day = 1; $list_day <= $days_in_month; $list_day++) {
		if (mb_strlen($list_day) == 1) {
			$dia = '0'.$list_day;
		} else {
			$dia = $list_day;
		} // dia strlen

		if ( ($agora->format('d')==$list_day) && ($agora->format('m')==$month) && ($agora->format('Y')==$year) ) {
			$hoje = true;
		} else {
			$hoje = false;
		}

		$data_atual_calendario = $ano.'-'.$mes.'-'.$dia;

		if ($data_atual_calendario < $agora->format('Y-m-d')) {
			$antes = true;
		} else {
			$antes = false;
		}

		if (isset($disponibilidades[$data_atual_calendario]) && ($disponibilidades[$data_atual_calendario]['mes']==$mes) && ($disponibilidades[$data_atual_calendario]['ano']==$ano) ) {
			$calendar .= '
			<div id="dia_'.$ano.'-'.$mes.'-'.$dia.'" style="display:inline-block;min-width:100%;max-width:100%;border:1px solid var(--cinza);background-color:var(--branco);border-bottom:0;margin:3px auto;padding:1.2%;">
				<p style="display:inline-block;text-align:left;vertical-align:top;font-size:18px;border:1px solid var(--azul);border-radius:var(--circulo);padding:5px 13px;">'.$list_day.'</p>
			';

				$veiculos = explode(',',$disponibilidades[$data_atual_calendario]['vid']);
				$veiculos = array_unique($veiculos);
				$status = explode(',',$disponibilidades[$data_atual_calendario]['status']);
				$i=0;
				foreach ($veiculos as $veiculo) {
					$informacao = new ConsultaDatabase($uid);
					$informacao = $informacao->Veiculo($veiculo);
					($status[$i]=='Disponível') ? $cor = 'var(--verde)' : $cor = 'var(--rosa)';
					$calendar .= '
						<p style="text-align:left;display:inline-block;min-width:100%;max-width:100%;margin:3px auto;">
							<span id="vid_'.$informacao['vid'].'_dia_'.$data_atual_calendario.'" style="cursor:pointer;"><span style="color:'.$cor.'">•</span> '.$informacao['modelo'] . ' - ' . $informacao['placa'] . ' - ' . $status[$i] . '</span>
						</p>
					';
					$calendar .= "
						<script>
							$('#vid_".$informacao['vid']."_dia_".$data_atual_calendario."').on('click', function() {
								vid = $(this).attr('id').split('_')[1];
								veiculoFundamental(vid);
							});
						</script>
					";
					$i++;
				} // foreach

			$calendar .= '
			</div>
			';
		} // mes
	} // for

	/* end the table */
	$calendar.= '</div>';

	/* all done, return result */
	return $calendar;
} // draw calendar
} // Exists

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//https://pt.stackoverflow.com/a/159749
if (!function_exists('extenso')) {
	function extenso($valor = 0, $maiusculas = false) {
	    if(!$maiusculas){
	        $singular = ["centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão"];
	        $plural = ["centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões"];
	        $u = ["", "um", "dois", "três", "quatro", "cinco", "seis",  "sete", "oito", "nove"];
	    }else{
	        $singular = ["CENTAVO", "REAL", "MIL", "MILHÃO", "BILHÃO", "TRILHÃO", "QUADRILHÃO"];
	        $plural = ["CENTAVOS", "REAIS", "MIL", "MILHÕES", "BILHÕES", "TRILHÕES", "QUADRILHÕES"];
	        $u = ["", "um", "dois", "TRÊS", "quatro", "cinco", "seis",  "sete", "oito", "nove"];
	    }

	    $c = ["", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];
	    $d = ["", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
	    $d10 = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"];

	    $z = 0;
	    $rt = "";

	    $valor = number_format($valor, 2, ".", ".");
	    $inteiro = explode(".", $valor);
	    for($i=0;$i<count($inteiro);$i++)
	    for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
	    $inteiro[$i] = "0".$inteiro[$i];

	    $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	    for ($i=0;$i<count($inteiro);$i++) {
	        $valor = $inteiro[$i];
	        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
	        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
	        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

	        $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
	        $ru) ? " e " : "").$ru;
	        $t = count($inteiro)-1-$i;
	        $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
	        if ($valor == "000")$z++; elseif ($z > 0) $z--;
	        if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
	        if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	    }

	    if(!$maiusculas){
	        $return = $rt ? $rt : "zero";
	    } else {
	        if ($rt) $rt = preg_replace(" E "," e ",ucwords($rt));
	            $return = ($rt) ? ($rt) : "Zero" ;
	    }

	    // if(!$maiusculas){
	    //     return preg_replace(" E "," e ",ucwords($return));
	    // }else{
	    //     return strtoupper($return);
	    // }
	     return $return;
    } // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('Guid')) {
	function Guid() { // Create GUID (Globally Unique Identifier)
		https://www.php.net/manual/en/function.com-create-guid.php#124635
	        $guid = '';
	        $namespace = rand(11111, 99999);
	        $uid = uniqid('', true);
	        $data = $namespace;
	        $data .= $_SERVER['REQUEST_TIME'];
	        $data .= $_SERVER['HTTP_USER_AGENT'];
	        $data .= $_SERVER['REMOTE_ADDR'];
	        $data .= $_SERVER['REMOTE_PORT'];
	        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	        $guid = substr($hash,  0,  8) . '-' .
	                substr($hash,  8,  4) . '-' .
	                substr($hash, 12,  4) . '-' .
	                substr($hash, 16,  4) . '-' .
	                substr($hash, 20, 12);
	        return $guid;
	} // Função
} // Existe

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
