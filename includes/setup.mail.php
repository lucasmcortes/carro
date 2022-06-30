<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// classe pra enviar email
if (!class_exists('Cartinha')) {
	class Cartinha {

		public $mail;

		public $ConteudoDaCartinha;
		public $DestinatarioDaCartinha;
		public $AssuntoDaCartinha;
		public $MensagemDaCartinha;

		public $resposta;

		////////////////////////////////////////////////////////////////

		// FAZER FUNÇÕES DE CADA PARTE DO EMAIL (linha 1[, 2, 3], img etc.)
		// PRA PODER VARIAR NA HORA DE CRIAR
		// EM CADA PÁGINA DO SITE USANDO A MESMA CLASSE

		////////////////////////////////////////////////////////////////

		public function enviarCartinha($ConteudoDaCartinha,$DestinatarioDaCartinha) {

			global $dominio;
			global $uid;
			global $data;
			global $conn;
			global $cores_root;

			$this->ConteudoDaCartinha = $ConteudoDaCartinha;
			$this->DestinatarioDaCartinha = $DestinatarioDaCartinha;
			$_SESSION['destinatario'] = $this->DestinatarioDaCartinha;

			//pega mensagem e assunto da cartinha
			if ($this->ConteudoDaCartinha == 'cadastro') {
				include __DIR__.'/cartinhas/cartinha-cadastro.php';
			} else if ($this->ConteudoDaCartinha == 'recuperar') {
				include __DIR__.'/cartinhas/cartinha-recuperar.php';
			} else if ($this->ConteudoDaCartinha == 'senha-recuperada') {
				include __DIR__.'/cartinhas/cartinha-senha-recuperada.php';
			} else if ($this->ConteudoDaCartinha == 'contato') {
				include __DIR__.'/cartinhas/cartinha-contato.php';
			} else if ($this->ConteudoDaCartinha == 'suporte') {
				include __DIR__.'/cartinhas/cartinha-suporte.php';
			} else if ($this->ConteudoDaCartinha == 'drive') {
				include __DIR__.'/cartinhas/cartinha-testedrive.php';
			} else if ($this->ConteudoDaCartinha == 'boleto') {
				include __DIR__.'/cartinhas/cartinha-boleto.php';
			} else if ($this->ConteudoDaCartinha == 'cartao') {
				include __DIR__.'/cartinhas/cartinha-cartao.php';
			} else if ($this->ConteudoDaCartinha == '') {
				// include_once '';
			}

			// as cores pro $mail->Body
			// (quando mudar no root.inc.php já muda nos emails também [quando os emails estiverem usando as var(--cor)])
			$creme = $cores_root['creme']['cor'];
			$roxo = $cores_root['roxo']['cor'];
			$rosa = $cores_root['rosa']['cor'];
			$cores_var = array($cores_root['creme']['root'],$cores_root['roxo']['root'],$cores_root['rosa']['root']);
			$cores_extenso = array($creme,$roxo,$rosa);
			$MensagemDaCartinha = str_replace($cores_var,$cores_extenso,$MensagemDaCartinha);

			$this->AssuntoDaCartinha = $AssuntoDaCartinha;
			$this->MensagemDaCartinha = $MensagemDaCartinha;

			$mail = new PHPMailer(true);                              // Passing `true` enables
			$mail->CharSet = 'UTF-8';

			try {
				//Server settings

				// Enable verbose debug output
				//Enable SMTP debugging
				//SMTP::DEBUG_OFF = off (for production use)
				//SMTP::DEBUG_CLIENT = client messages
				//SMTP::DEBUG_SERVER = client and server messages
				// pra production é = 0
				//$mail->SMTPDebug = 0;
				//$mail->SMTPDebug = 1;
				//$mail->SMTPDebug = 2;
				//$mail->SMTPDebug = 3;
				//$mail->SMTPDebug = SMTP::DEBUG_SERVER;

				//Tell PHPMailer to use SMTP
				$mail->isSMTP();

				// Specify main and backup SMTP servers
				//$mail->Host = 'smtp.gmail.com';
				$mail->Host = 'smtp.titan.email';

				// Enable SMTP authentication
				$mail->SMTPAuth = true;

				// SMTP username
				$mail->Username = 'inicio@ophanim.com.br';
				// SMTP password
				$mail->Password = 'hbh777#@!';
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;

				//Recipients
				$mail->setFrom('inicio@ophanim.com.br', 'Ophanim.com.br');
				// Add a recipient
				$mail->addAddress($this->DestinatarioDaCartinha);

				//Content
				// Set email format to HTML
				$mail->isHTML(true);
				$mail->Subject = $this->AssuntoDaCartinha;

				//construção da estrutura inicial
				$mail->Body = "
					<!-- outer wrapper -->
					<div style='max-width:100%;min-width:100%;float:left;text-align:center;'>
						<!-- conteudo -->
						<div id='conteudo' class='conteudo' style='min-width:64%;max-width:64%;display:inline-block;'>
							<!-- email -->
							<div style='padding:1% 0px;padding-bottom:0;display:inline-block;'>
				";

				//logo
				$mail->Body .= "
					<!-- img -->
					<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;'>
						<div style='min-width:100%;max-width:100%;margin:0 auto;'>
							<img style='max-width:90px;width:100%;height:auto;padding:1% 0px;' src='".$dominio."/img/logo.png'></img>
						</div>
					</div>
					<!-- img -->
				";

				//construção da estrutura interna
				$mail->Body .= "
					<!-- dentro -->
					<div style='width:auto;max-width:720px;margin:0 auto;display:inline-block;'>
						<!-- miolo -->
						<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
				";

				$mail->Body .= $this->MensagemDaCartinha;

				//disclaimer
				$mail->Body .= "
					<!-- 3 linha -->
					<div style='min-width:100%;max-width:100%;margin:0 auto;padding:3% 0px;display:inline-block;color:".$roxo.";''>
						<!-- disclaimer -->
						<div style='min-width:100%;max-width:100%;margin:0 auto;float:left;text-align:center;'>
							<p style='min-width:77%;max-width:77%;font-size:13px;font-weight:400;margin:0 auto;display:inline-block;'>
								© ".date("Y")." Ophanim Desenvolvimento
							</p>
						</div>
						<!-- disclaimer -->
					</div>
					<!-- 3 linha -->
				";

				//conclusão da estrutura interna
				$mail->Body .= "
						</div>
						<!-- miolo -->
					</div>
					<!-- dentro -->
				";

				//conclusão da estrutura inicial
				$mail->Body .= "
							</div>
							<!-- email -->
						</div>
						<!-- conteudo -->
					</div>
					<!-- outer wrapper -->
				";

				$mail->send();

				if (!$mail->send()) {
					return $this->resposta = array(
						'resposta'=>'Erro no envio: '. $mail->ErrorInfo,
						'addemail'=>0,
						'envio_id'=>0
					);
				} else {
					$addemail = new setRow();
					$addemail = $addemail->EmailsEnviados($uid,$data,$this->DestinatarioDaCartinha,$this->ConteudoDaCartinha,$this->AssuntoDaCartinha,$mail->Body,$_SERVER['REMOTE_ADDR']?:0);
					if ($addemail===true) {
						$enviadorecente = new ConsultaDatabase($uid);
						$enviadorecente = $enviadorecente->EnviadoRecente($this->DestinatarioDaCartinha,$data);

						// limpa pra poder enviar de novo
						$mail->clearAddresses();

						return $this->resposta = array(
							'resposta'=>'Enviado com sucesso para <b>'.$this->DestinatarioDaCartinha.'</b>',
							'addemail'=>$addemail,
							'envio_id'=>$enviadorecente['envio_id']
						);
					} else {

						// limpa pra poder enviar de novo
						$mail->clearAddresses();

						return $this->resposta = array(
							'resposta'=>'Enviado e não registrado',
							'addemail'=>$addemail,
							'envio_id'=>0
						);
					} // addemail true
				} // enviado

			} catch (Exception $e) {
				exit();
			} // try

		} // function enviarCartinha

	} // classe Cartinha
} // Existe

?>
