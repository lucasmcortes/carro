<?php

	class setRow extends Conexao {

		public function Teste($data,$info,$dado) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO testando (teste_data, teste_info, teste_dado) VALUES(?, ?, ?);");
				$stmt->execute([$data, $info, $dado]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Teste

		public function errorLog($data,$erro,$pagina) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO errorlog (data, erro, pagina) VALUES(?, ?, ?);");
				$stmt->execute([$data, $erro, $pagina]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // errorLog

		public function Cadastro($nome,$nascimento,$cpf,$telefone,$email,$senha,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cadastros (nome, nascimento, cpf, telefone, email, senha, data_cadastro) VALUES(?, ?, ?, ?, ?, ?, ?);");
				$stmt->execute([$nome,$nascimento,$cpf,$telefone,$email,$senha,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Cadastro

		public function CadastroCartao($uid,$nome,$cpf,$numero,$expiracao,$cvc,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cadastros_cartao (uid, nome, cpf, numero, dataexp, cvc, data_cadastro) VALUES(?, ?, ?, ?, ?, ?, ?);");
				$stmt->execute([$uid,$nome,$cpf,$numero,$expiracao,$cvc,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CadastroCartao

		public function CadastroConfirmado($uid,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cadastro_confirmado (uid, data) VALUES(?, ?);");
				$stmt->execute([$uid,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CadastroConfirmado

		public function CadastroNivel($uid,$nivel,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cadastro_nivel (uid, nivel, data_mod) VALUES(?, ?, ?);");
				$stmt->execute([$uid,$nivel,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CadastroNivel

		public function Endereco($uid,$lid,$cep,$rua,$numero,$bairro,$cidade,$estado,$complemento,$ativo,$data_cadastro) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO endereco (uid, lid, cep, rua, numero, bairro, cidade, estado, complemento, ativo, data_cadastro) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$lid,$cep,$rua,$numero,$bairro,$cidade,$estado,$complemento,$ativo,$data_cadastro]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Endereco

		public function EmailsEnviados($uid,$data,$email,$conteudo,$assunto,$mensagem,$rmtaddr) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO emails_enviados (uid, envio_data, envio_email, envio_conteudo, envio_assunto, envio_mensagem, envio_rmt_addr) VALUES(?, ?, ?, ?, ?, ?, ?);");
				$stmt->execute([$uid,$data,$email,$conteudo,$assunto,$mensagem,$rmtaddr]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // EmailsEnviados

		public function Login($uid,$sid,$chave,$rmt_addr,$rmt_host,$user_agent,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO login (uid, sid, chave, rmt_addr, rmt_host, user_agent, data) VALUES(?, ?, ?, ?, ?, ?, ?);");
				$stmt->execute([$uid,$sid,$chave,$rmt_addr,$rmt_host,$user_agent,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Login

		public function Recuperar($email,$chave,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO recuperar (email, chave, data) VALUES (?, ?, ?);");
				$stmt->execute([$email,$chave,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Recuperar

		public function Visitas($uid,$link,$rmt_addr,$user_agent,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO visitas (uid, link, rmt_addr, user_agent, data) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$link,$rmt_addr,$user_agent,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Visitas

		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////
		// PAGAMENTO
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////

		public function PagamentoCartaoPagSeguro(
			$uid,
			$id,
			$licencaTipo,
			$reference_id,
			$status,
			$created_at,
			$paid_at,
			$description,
			$amountvalue,
			$amountcurrency,
			$amountsummarytotal,
			$amountsummarypaid,
			$amountsummaryrefunded,
			$paymentresponsecode,
			$paymentresponsemessage,
			$paymentresponsereference,
			$paymentmethodtype,
			$paymentmethodinstallments,
			$paymentmethodcapture,
			$paymentmethodcardbrand,
			$paymentmethodcardfirstdigits,
			$paymentmethodcardlastdigits,
			$paymentmethodcardexpmonth,
			$paymentmethodcardexpyear,
			$paymentmethodcardholdername,
			$paymentmethodsoftdescriptor,
			$recurringtype,
			$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO pagamento_cartao_pagseguro (
					uid,
					id,
					licencaTipo,
					reference_id,
					status,
					created_at,
					paid_at,
					description,
					amountvalue,
					amountcurrency,
					amountsummarytotal,
					amountsummarypaid,
					amountsummaryrefunded,
					paymentresponsecode,
					paymentresponsemessage,
					paymentresponsereference,
					paymentmethodtype,
					paymentmethodinstallments,
					paymentmethodcapture,
					paymentmethodcardbrand,
					paymentmethodcardfirstdigits,
					paymentmethodcardlastdigits,
					paymentmethodcardexpmonth,
					paymentmethodcardexpyear,
					paymentmethodcardholdername,
					paymentmethodsoftdescriptor,
					recurringtype,
					data
				) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([
					$uid,
					$id,
					$licencaTipo,
					$reference_id,
					$status,
					$created_at,
					$paid_at,
					$description,
					$amountvalue,
					$amountcurrency,
					$amountsummarytotal,
					$amountsummarypaid,
					$amountsummaryrefunded,
					$paymentresponsecode,
					$paymentresponsemessage,
					$paymentresponsereference,
					$paymentmethodtype,
					$paymentmethodinstallments,
					$paymentmethodcapture,
					$paymentmethodcardbrand,
					$paymentmethodcardfirstdigits,
					$paymentmethodcardlastdigits,
					$paymentmethodcardexpmonth,
					$paymentmethodcardexpyear,
					$paymentmethodcardholdername,
					$paymentmethodsoftdescriptor,
					$recurringtype,
					$data
				]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PagamentoCartaoPagSeguro

		public function PagamentoBoletoPagSeguro(
			$uid,
			$id,
			$licencaTipo,
			$reference_id,
			$status,
			$created_at,
			$paid_at,
			$description,
			$amounttotal,
			$amountpaid,
			$amountrefunded,
			$amountcurrency,
			$paymentresponsecode,
			$paymentresponsemessage,
			$paymentresponsereference,
			$paymentmethodtype,
			$paymentmethodtypeboletoid,
			$paymentmethodtypeboletobarcode,
			$paymentmethodtypeboletoformattedbarcode,
			$paymentmethodtypeboletoduedate,
			$paymentmethodtypeboletoinstructionlinesline1,
			$paymentmethodtypeboletoinstructionlinesline2,
			$paymentmethodtypeboletoholdername,
			$paymentmethodtypeboletoholdertaxid,
			$paymentmethodtypeboletoholderemail,
			$paymentmethodtypeboletoholderaddresscountry,
			$paymentmethodtypeboletoholderaddressregioncode,
			$paymentmethodtypeboletoholderaddresscity,
			$paymentmethodtypeboletoholderaddresspostalcode,
			$paymentmethodtypeboletoholderaddressstreet,
			$paymentmethodtypeboletoholderaddressnumber,
			$paymentmethodtypeboletoholderaddresslocality,
			$linkshref,
			$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO pagamento_boleto_pagseguro (
					uid,
					id,
					licencaTipo,
					reference_id,
					status,
					created_at,
					paid_at,
					description,
					amounttotal,
					amountpaid,
					amountrefunded,
					amountcurrency,
					paymentresponsecode,
					paymentresponsemessage,
					paymentresponsereference,
					paymentmethodtype,
					paymentmethodtypeboletoid,
					paymentmethodtypeboletobarcode,
					paymentmethodtypeboletoformattedbarcode,
					paymentmethodtypeboletoduedate,
					paymentmethodtypeboletoinstructionlinesline1,
					paymentmethodtypeboletoinstructionlinesline2,
					paymentmethodtypeboletoholdername,
					paymentmethodtypeboletoholdertaxid,
					paymentmethodtypeboletoholderemail,
					paymentmethodtypeboletoholderaddresscountry,
					paymentmethodtypeboletoholderaddressregioncode,
					paymentmethodtypeboletoholderaddresscity,
					paymentmethodtypeboletoholderaddresspostalcode,
					paymentmethodtypeboletoholderaddressstreet,
					paymentmethodtypeboletoholderaddressnumber,
					paymentmethodtypeboletoholderaddresslocality,
					linkshref,
					data
				) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([
					$uid,
					$id,
					$licencaTipo,
					$reference_id,
					$status,
					$created_at,
					$paid_at,
					$description,
					$amounttotal,
					$amountpaid,
					$amountrefunded,
					$amountcurrency,
					$paymentresponsecode,
					$paymentresponsemessage,
					$paymentresponsereference,
					$paymentmethodtype,
					$paymentmethodtypeboletoid,
					$paymentmethodtypeboletobarcode,
					$paymentmethodtypeboletoformattedbarcode,
					$paymentmethodtypeboletoduedate,
					$paymentmethodtypeboletoinstructionlinesline1,
					$paymentmethodtypeboletoinstructionlinesline2,
					$paymentmethodtypeboletoholdername,
					$paymentmethodtypeboletoholdertaxid,
					$paymentmethodtypeboletoholderemail,
					$paymentmethodtypeboletoholderaddresscountry,
					$paymentmethodtypeboletoholderaddressregioncode,
					$paymentmethodtypeboletoholderaddresscity,
					$paymentmethodtypeboletoholderaddresspostalcode,
					$paymentmethodtypeboletoholderaddressstreet,
					$paymentmethodtypeboletoholderaddressnumber,
					$paymentmethodtypeboletoholderaddresslocality,
					$linkshref,
					$data
				]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PagamentoBoletoPagSeguro

		public function Licenca($uid,$idpagamento,$status,$modalidade,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO licencas (uid, idpagamento, status, modalidade, data) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$idpagamento,$status,$modalidade,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Licenca

		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////
		// ALUGUEL
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////

		public function Aluguel($uid,$lid,$vid,$diaria,$km,$inicio,$devolucao,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO aluguel (uid, lid, vid, diaria, km, inicio, devolucao, data) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");
				$stmt->execute([$uid,$lid,$vid,$diaria,$km,$inicio,$devolucao,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Aluguel

		public function AluguelCaucao($uid,$aid,$valor,$forma,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO aluguel_caucao (uid, aid, valor, forma, data) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$aid,$valor,$forma,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // AluguelCaucao

		public function AluguelGuid($aid,$guid,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO aluguel_guid (aid, guid, data) VALUES(?, ?, ?);");
				$stmt->execute([$aid,$guid,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // AluguelGuid

		public function Associado($uid,$lid,$associado,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO associado (uid, lid, associado, data) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$lid,$associado,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Associado

		public function Ativacao($uid,$reid,$ativa,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO ativacao (uid, reid, ativa, data) VALUES(?, ?, ?, ?);");
				$stmt->execute([$uid,$reid,$ativa,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Ativacao

		public function Cobranca($uid,$deid,$valor,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cobranca (uid, deid, valor, data) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$deid,$valor,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Cobranca

		public function CobrancaParcial($uid,$coid,$valor,$forma,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cobranca_parcial (uid, coid, valor, forma, data) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$coid,$valor,$forma,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CobrancaParcial

		public function Cortesia($uid,$pid,$utilizadas,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cortesia (uid, pid, utilizadas, data) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$pid,$utilizadas,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Cortesia

		public function Devolucao($uid,$aid,$limpeza,$cortesias,$kilometragem,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO devolucao (uid, aid, limpeza, cortesias, kilometragem, data) VALUES(?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$aid,$limpeza,$cortesias,$kilometragem,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Devolucao

		public function Habilitacao($uid,$lid,$validade,$numero,$data_cadastro) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO habilitacao (uid, lid, validade, numero, data_cadastro) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$lid,$validade,$numero,$data_cadastro]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Habilitacao

		public function Kilometragem($uid,$vid,$km,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO kilometragem (uid, vid, km, data) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$vid,$km,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Kilometragem

		public function Lembrete($uid,$aid,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO lembrete (uid, aid, data) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$aid,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Lembrete

		public function Limpeza($uid,$vid,$status,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO limpeza (uid, vid, status, data) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$vid,$status,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Limpeza

		public function Lobs($uid,$lid,$observacao,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO lobs (uid, lid, observacao, data_mod) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$lid,$observacao,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Lobs

		public function Locatario($uid,$nome,$documento,$telefone,$email,$nascimento,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO locatario (uid, nome, documento, telefone, email, nascimento, data_cadastro) VALUES(?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$nome,$documento,$telefone,$email,$nascimento,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Locatario

		public function Manutencao($uid,$vid,$estabelecimento,$motivo,$inicio,$devolucao,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO manutencao (uid, vid, estabelecimento, motivo, inicio, devolucao, data) VALUES(?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$vid,$estabelecimento,$motivo,$inicio,$devolucao,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Manutencao

		public function ManutencaoReserva($uid,$mid,$confirmada,$inicio,$devolucao,$data_mod) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO manutencao_reserva (uid, mid, confirmada, inicio, devolucao, data_mod) VALUES(?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$mid,$confirmada,$inicio,$devolucao,$data_mod]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // ManutencaoReserva

		public function ManutencaoAtivacao($uid,$mreid,$ativa,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO manutencao_ativacao (uid, mreid, ativa, data) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$mreid,$ativa,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // ManutencaoAtivacao

		public function PagamentoInicial($uid,$aid,$valor,$forma,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO pagamento_inicial (uid, aid, valor, forma, data) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$aid,$valor,$forma,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PagamentoInicial

		public function PagamentoParcial($uid,$aid,$valor,$forma,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO pagamento_parcial (uid, aid, valor, forma, data) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$aid,$valor,$forma,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PagamentoParcial

		public function Placa($uid,$lid,$asid,$placa,$data_cadastro) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO placa (uid, lid, asid, placa, data_cadastro) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$lid,$asid,$placa,$data_cadastro]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Placa

		public function PlacaAtivacao($uid,$pid,$ativa,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO placa_ativacao (uid, pid, ativa, data_mod) VALUES(?, ?, ?, ?)");
				$stmt->execute([$uid,$pid,$ativa,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PlacaAtivacao

		public function Reserva($uid,$aid,$confirmada,$inicio,$devolucao,$data_mod) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO reserva (uid, aid, confirmada, inicio, devolucao, data_mod) VALUES(?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$aid,$confirmada,$inicio,$devolucao,$data_mod]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Reserva

		public function Retorno($uid,$mid,$vid,$valor,$observacao,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO retorno (uid, mid, vid, valor, observacao, data) VALUES(?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$mid,$vid,$valor,$observacao,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Retorno

		public function Transacao($uid,$coid,$valor,$desconto,$forma,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO transacao (uid, coid, valor, desconto, forma, data) VALUES(?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$coid,$valor,$desconto,$forma,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Transacao

		public function ValorAdicional($uid,$deid,$valor,$descricao,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO valor_adicional (uid, deid, valor, descricao, data) VALUES(?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$deid,$valor,$descricao,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // ValorAdicional

		public function Veiculo($uid,$categoria,$marca,$modelo,$potencia,$placa,$chassi,$renavam,$ano,$cor,$ativo,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO veiculo (uid, categoria, marca, modelo, potencia, placa, chassi, renavam, ano, cor, ativo, data_cadastro) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$categoria,$marca,$modelo,$potencia,$placa,$chassi,$renavam,$ano,$cor,$ativo,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Veiculo

		public function Vobs($uid,$vid,$portas,$completo,$revisao,$observacao,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO vobs (uid, vid, portas, completo, revisao, observacao, data_mod) VALUES(?, ?, ?, ?, ?, ?, ?)");
				$stmt->execute([$uid,$vid,$portas,$completo,$revisao,$observacao,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Vobs

		////////////////////////////////////

		public function CaucaoPadrao($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_caucao (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CaucaoPadrao

		public function DiariaAssociado($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_diaria_as (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiariaAssociado

		public function DiariaExcedenteCarro($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_diaria_exc_carro (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiariaExcedenteCarro

		public function DiariaExcedenteUtilitario($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_diaria_exc_uti (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiariaExcedenteUtilitario

		public function DiariaExcedenteMoto($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_diaria_exc_moto (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiariaExcedenteMoto

		public function DiariaAssociadoMoto($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_diaria_as_moto (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiariaAssociadoMoto

		public function DiariaAssociadoUtilitario($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_diaria_as_utilitario (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiariaAssociadoUtilitario

		public function DiasAcionamento($uid,$dias,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_dias_ac (uid, dias, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$dias,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiasAcionamento

		public function DiasCortesiaAno($uid,$dias,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_dias_pla (uid, dias, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$dias,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiasCortesiaAno

		public function DiasCortesiaMes($uid,$dias,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_dias_pla_mes (uid, dias, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$dias,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DiasCortesiaMes

		public function PrecoKM($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_preco_km (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PrecoKM

		public function PrecoLimpezaExecutiva($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_preco_le (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PrecoLimpezaExecutiva

		public function PrecoLimpezaCompleta($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_preco_lc (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PrecoLimpezaCompleta

		public function PrecoLimpezaMotor($uid,$preco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_preco_lm (uid, preco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$preco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // PrecoLimpezaMotor

		public function MinutosTolerancia($uid,$minutos,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_tol_dev (uid, minutos, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$minutos,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // MinutosTolerancia

		public function NotificacaoRevisar($uid,$ativa,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_rev (uid, ativa, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$ativa,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // NotificacaoRevisar

		public function KMRevisaoAposLimiar($uid,$km,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_rev_car_apos (uid, km, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$km,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // KMRevisaoAposLimiar

		public function KMRevisaoPrevLimiar($uid,$km,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_rev_car_prev (uid, km, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$km,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // KMRevisaoPrevLimiar

		public function KMRevisaoLimiar($uid,$km,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_rev_car_limiar (uid, km, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$km,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // KMRevisaoLimiar

		public function KMRevisaoMoto($uid,$km,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_rev_moto (uid, km, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$km,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // KMRevisaoMoto

		public function ConfiguracaoRSocial($uid,$rsocial,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_rsocial (uid, razao_social, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$rsocial,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // ConfiguracaoRSocial

		public function ConfiguracaoCNPJ($uid,$cnpj,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_cnpj (uid, cnpj, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$cnpj,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // ConfiguracaoCNPJ

		public function ConfiguracaoEndereco($uid,$endereco,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_endereco (uid, endereco, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$endereco,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // ConfiguracaoEndereco

		public function ConfiguracaoCidadePromissoria($uid,$cidade,$data) {
			try {
				$stmt = $this->conectar()->prepare("INSERT INTO cfig_cidade_promissoria (uid, cidade, data_mod) VALUES(?, ?, ?)");
				$stmt->execute([$uid,$cidade,$data]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // ConfiguracaoCidadePromissoria


	} // class setrow

?>
