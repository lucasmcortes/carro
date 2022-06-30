<?php

	class UpdateRow extends Conexao {

		public function UpdateTeste($cid,$tid,$info) {
			try {
				$stmt = "UPDATE testando SET teste_data=?  WHERE teste_id=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$cid,$tid]);
				$stmt = "UPDATE testando SET teste_info=?  WHERE teste_id=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$info,$tid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateTeste

		public function UpdateSenha($senha,$email) {
			try {
				$stmt = "UPDATE cadastros SET senha=?  WHERE email=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$senha,$email]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateSenha

		public function UpdateNivel($nivel,$uid) {
			try {
				$stmt = "UPDATE cadastro_nivel SET nivel=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$nivel,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateNivel

		public function RemoveEndereco($eid) {
			try {
				$stmt = "UPDATE endereco SET ativo=0 WHERE eid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$eid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // RemoveEndereco

		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////
		// PAGAMENTO
		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////

		public function CancelaPlano($licid) {
			try {
				$stmt = "UPDATE licencas SET status='Cancelada' WHERE licid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$licid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CancelaPlano

		public function CancelarBoleto($pagbid) {
			try {
				$stmt = "UPDATE pagamento_boleto_pagseguro SET status='CANCELADO' WHERE pagbid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$pagbid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CancelarBoleto

		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////
		// ALUGUEL
		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////

		public function UpdateUserCPF($cpf,$uid) {
			try {
				$stmt = "UPDATE cadastros SET cpf=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$cpf,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateUserCPF

		public function UpdateUserEmail($email,$uid) {
			try {
				$stmt = "UPDATE cadastros SET email=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$email,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateUserEmail

		public function UpdateUserTelefone($telefone,$uid) {
			try {
				$stmt = "UPDATE cadastros SET telefone=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$telefone,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateUserTelefone

		public function UpdateGuid($guid,$aid) {
			try {
				$stmt = "UPDATE aluguel_guid SET guid=?  WHERE aid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$guid,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateGuid

		public function UpdateLocatarioNome($nome,$lid) {
			try {
				$stmt = "UPDATE locatario SET nome=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$nome,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateLocatarioNome

		public function UpdateHabilitacao($cnh,$lid) {
			try {
				$stmt = "UPDATE habilitacao SET numero=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$cnh,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateHabilitacao

		public function UpdateValidade($validade,$numero) {
			try {
				$stmt = "UPDATE habilitacao SET validade=?  WHERE numero=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$validade,$numero]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateValidade

		public function UpdateLocatarioEmail($email,$lid) {
			try {
				$stmt = "UPDATE locatario SET email=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$email,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateLocatarioEmail

		public function UpdateLocatarioTelefone($telefone,$lid) {
			try {
				$stmt = "UPDATE locatario SET telefone=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$telefone,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateLocatarioTelefone

		public function UpdateReservaConfirmacao($confirmada,$aid) {
			try {
				$stmt = "UPDATE reserva SET confirmada=?  WHERE aid=?";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$confirmada,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateReservaConfirmacao

		public function UpdateReservaManutencaoConfirmacao($confirmada,$mid) {
			try {
				$stmt = "UPDATE manutencao_reserva SET confirmada=?  WHERE mid=?";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$confirmada,$mid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateReservaManutencaoConfirmacao

		public function UpdateVeiculoDoAluguel($vid,$aid) {
			try {
				$stmt = "UPDATE aluguel SET vid=?  WHERE aid=?";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$vid,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoDoAluguel

		public function UpdateVeiculoRenavam($renavam,$vid) {
			try {
				$stmt = "UPDATE veiculo SET renavam=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$renavam,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoRenavam

		public function UpdateVeiculoChassi($chassi,$vid) {
			try {
				$stmt = "UPDATE veiculo SET chassi=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$chassi,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoChassi

		public function UpdateVeiculoPlaca($placa,$vid) {
			try {
				$stmt = "UPDATE veiculo SET placa=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$placa,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoPlaca

		public function UpdateVeiculoAno($ano,$vid) {
			try {
				$stmt = "UPDATE veiculo SET ano=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$ano,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoAno

		public function UpdateVeiculoCor($cor,$vid) {
			try {
				$stmt = "UPDATE veiculo SET cor=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$cor,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoCor

		public function UpdateVeiculoCategoria($categoria,$vid) {
			try {
				$stmt = "UPDATE veiculo SET categoria=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$categoria,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoCategoria

		public function UpdateVeiculoPotencia($potencia,$vid) {
			try {
				$stmt = "UPDATE veiculo SET potencia=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$potencia,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoPotencia

		public function UpdateVeiculoMarca($marca,$vid) {
			try {
				$stmt = "UPDATE veiculo SET marca=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$marca,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoMarca

		public function UpdateVeiculoModelo($modelo,$vid) {
			try {
				$stmt = "UPDATE veiculo SET modelo=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$modelo,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoModelo

		public function UpdateVeiculoAtivo($ativo,$vid) {
			try {
				$stmt = "UPDATE veiculo SET ativo=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$ativo,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateVeiculoAtivo

	} // class updaterow

?>
