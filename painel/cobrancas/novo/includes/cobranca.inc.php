<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitpagamento'])) {
	$cadastrando = '';

	$coid = $_POST['coid'];
	if (empty($coid)) {
		RespostaRetorno('coid');
		return;
	} // coid
	$preco = new ConsultaDatabase($uid);
	$preco = $preco->Cobranca($coid);

	$somaparciais = new Conforto($uid);
	$somaparciais = $somaparciais->SomaParciais($coid);

	$residual = new Conforto($uid);
	$residual = $residual->Residual($coid);

	$desconto = Sanitiza($_POST['desconto']);
	if (empty($desconto)) {
		$desconto = 0;
		$valor = $residual['residual'];
	} else {
		$valor = $residual['residual'];
		$valor = $valor - ($valor * ($desconto/100));
	} // desconto

	$parcial = Sanitiza($_POST['parcial'])?:0;
	$valorresidual = $valor-$parcial;

	if (sprintf('%.0f', $valorresidual)<0) {
		RespostaRetorno('parcialsuperior');
		return;
	}

	$forma = $_POST['forma'];
	if (empty($forma)) {
		RespostaRetorno('pagforma');
		return;
	} else {
		$formapagamento = new Conforto($uid);
		$formapagamento = $formapagamento->SwitchForma($forma);
		$forma = $formapagamento;
	}// forma

	// $pwd = $_POST['pwd'];
	// if (empty($pwd)) {
	// 	RespostaRetorno('senha');
	// 	return;
	// } // pwd

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
				if ($parcial>0) {
					if ($valorresidual==0) {
						$addtransacao = new setRow();
						$addtransacao = $addtransacao->Transacao($uid,$coid,$valor,$desconto,$forma,$data);
						if ($addtransacao===true) {
							RespostaRetorno('sucessotransacao');
							return;
						} else {
							RespostaRetorno('regtranscao');
							return;
						} // addtransacao true
					} else {
						$addparcial = new setRow();
						$addparcial = $addparcial->CobrancaParcial($uid,$coid,$parcial,$forma,$data);
						if ($addparcial===true) {
							RespostaRetorno('sucessoparcial');
							return;
						} else {
							RespostaRetorno('regparcial');
							return;
						} // addparcial true
					} // se já deu de parcial do valor total
				} else {
					$addtransacao = new setRow();
					$addtransacao = $addtransacao->Transacao($uid,$coid,$valor,$desconto,$forma,$data);
					if ($addtransacao===true) {
						RespostaRetorno('sucessotransacao');
						return;
					} else {
						RespostaRetorno('regtranscao');
						return;
					} // addtransacao true
				} // parcial > 0
			} // autorizacao
		} else {
			RespostaRetorno('adminnivel');
			return;
		} // nivel
	} else {
		RespostaRetorno('adminencontrado');
		return;
	} // encontrado
} else {
	$cadastrando = ':((';
} // isset post submit

echo $cadastrando;

?>
