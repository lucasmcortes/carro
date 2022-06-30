<?php
	require_once __DIR__.'/cabecalho.php';
?>

<corpo>

	<!-- conteudo -->
	<div id='conteudo' class='conteudo' style='padding-top:3%;'>

		<div class='topouterwrap'>
			<div class='landingtopouterwrap'>
				<div class='landingtopwrap'>
					<div class='landingtoppwrap'>
						<p class='landingtopp'>
							Quer seus aluguéis<br>bem organizados?
						</p>
						<p class='descindexmenor' style='max-width:72%;'>
							Tenha todas as suas movimentações em relatórios disponíveis pra você.
						</p>
					</div>
				</div>
			</div>
		</div>

		<div style='margin:5% auto;'> <!-- linhas wrap -->
			<div class='linhaflex'>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/0201221553.png'></img>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Concentração
						</p>
						<p class='descindexmenor'>
							Cadastre seus veículos e tenha informações e relatórios sobre todas as transações
						</p>
					</div>
				</div>
			</div>

			<div class='linhaflex'>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Organização
						</p>
						<p class='descindexmenor'>
							Registre aluguéis, reservas, manutenções, despesas e recebimentos
						</p>
					</div>
				</div>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/0301221226.png'></img>
			</div>

			<div class='linhaflex'>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/0301221324.png'></img>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Tempo
						</p>
						<p class='descindexmenor'>
							Emita recibos, cadastre locatários, envie lembretes de reserva, imprima checklists personalizadas e notas promissórias
						</p>
					</div>
				</div>
			</div>

			<div class='linhaflex'>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Sob medida
						</p>
						<p class='descindexmenor'>
							Configure tolerância para a devolução, valores para diárias, kilometro excedente, caução padrão, limpeza e notificações para revisão dos veículos cadastrados
						</p>
					</div>
				</div>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/0301221402.png'></img>
			</div>
		</div> <!-- linhaswrap -->

		<div style='max-width:80%;display:inline-block;margin:5% auto;margin-top:0;'>
			<p class='descindex' style='text-align:center;'>
				Integração
			</p>
			<div class='landingiconwrap'>
				<div>
					<img class='landingiconimg' style='max-width:48px;' src='<?php echo $dominio ?>/img/calendarioformicon.png'></img>
					<p class='landingiconp'>Verificação de disponibilidade para o período desejado</p>
				</div>
				<div>
					<img class='landingiconimg' src='<?php echo $dominio ?>/img/buscaaluguelicon.png'></img>
					<p class='landingiconp'>Encontre aluguéis correntes, devolvidos, reservas futuras, anteriores e canceladas</p>
				</div>
				<div>
					<img class='landingiconimg' src='<?php echo $dominio ?>/img/verfotoicon.png'></img>
					<p class='landingiconp'>Armazene documentação e fotos dos seus veículos</p>
				</div>
				<div>
					<img class='landingiconimg' src='<?php echo $dominio ?>/img/relatoriogeralicon.png'></img>
					<p class='landingiconp'>Informações detalhadas e relatórios</p>
				</div>
			</div>
		</div>

		<!-- preco card landing
		<div style='max-width:80%;margin:0 auto;'>
			<div class='precocardlandinginnerwrap'>
				<div style='display: flex;flex-direction: column;flex: 1;background-image: url(img/0401221408.png);background-size: contain;background-repeat: no-repeat;background-position: center;'>
					<div>
						<div class='individualinnercabecalho'>
			                                <p class='cardcabecalhonome'>
			                                        Licença anual
			                                </p>
			                        </div>
					</div>
					<div class='individualinnercabecalhovalorwrap'>
						<div class='individualinnercabecalhovalor'>
							<p class='cardcabecalhovalor'>
								R$<?php // echo $preco_anual_vista; ?><span style='font-size:13px;'>/ano</span>
							</p>
						</div>
					</div>
				</div>
				<div style='flex:1;'>
					<div class='individualinnerwrap'>
						<div class='caracteristicaswrap'>
							<p class='individuallista'>
								Pagamento anual
							</p>
							<p class='individuallista'>
								Licença para uso de todos os recursos
							</p>
							<p class='individuallista'>
								Registro de veículos, locatários, aluguéis, reservas e manutenções
							</p>
							<p class='individuallista'>
								Registro de recebíveis antes, durante e depois da devolução do veículo
							</p>
							<p class='individuallista'>
								Configurações personalizadas
							</p>
							<p class='individuallista'>
								Todas as informações do seus veículos disponíveis online 24h por dia
							</p>
							<p class='individuallista'>
								Relatórios e estatísicas da suas operações para consulta online e exportação em arquivos XLS
							</p>
							<p class='individuallista'>
								Checklist, contratos e promissórias geradas automaticamente para imprimir ou salvar em PDF
							</p>
						</div>
						<a class='individualcta' href='<?php //echo $dominio ?>/cadastro/'>
							Começar
						</a>
						<p style='text-align:center;background-color:var(--verde);font-size:12px;padding:3%;'>
							Cadastre-se grátis, comece a usar agora e pague só se quiser continuar usando após 30 dias.
						</p>
					</div>
				</div>
			</div>
		</div>
		preco card landing -->

		<!-- <div style='display:inline-block;margin:3%;'></div> -->

		<!-- preco card landing
		<div style='max-width:80%;margin:0 auto;'>
			<div class='precocardlandinginnerwrap'>
				<div style='display: flex;flex-direction: column;flex: 1;background-image: url(img/0401221408.png);background-size: contain;background-repeat: no-repeat;background-position: center;'>
					<div>
						<div class='individualinnercabecalho'>
			                                <p class='cardcabecalhonome'>
			                                        Licença Vitalícia
			                                </p>
			                        </div>
					</div>
					<div class='individualinnercabecalhovalorwrap'>
						<div class='individualinnercabecalhovalor'>
							<p class='cardcabecalhovalor'>
								R$<?php //echo $preco_vital_vista; ?><span class='especificacaoperiodo'>licença vitalícia</span>
							</p>
						</div>
					</div>
				</div>
				<div style='flex:1;'>
					<div class='individualinnerwrap'>
						<div class='caracteristicaswrap'>
							<p class='individuallista'>
								Pagamento único
							</p>
							<p class='individuallista'>
								Licença para uso de todos os recursos
							</p>
							<p class='individuallista'>
								Registro de veículos, locatários, aluguéis, reservas e manutenções
							</p>
							<p class='individuallista'>
								Registro de recebíveis antes, durante e depois da devolução do veículo
							</p>
							<p class='individuallista'>
								Configurações personalizadas
							</p>
							<p class='individuallista'>
								Todas as informações do seus veículos disponíveis online 24h por dia
							</p>
							<p class='individuallista'>
								Relatórios e estatísicas da suas operações para consulta online e exportação em arquivos XLS
							</p>
							<p class='individuallista'>
								Checklist, contratos e promissórias geradas automaticamente para imprimir ou salvar em PDF
							</p>
						</div>
						<a class='individualcta' href='<?php //echo $dominio ?>/cadastro/'>
							Começar
						</a>
						<p style='text-align:center;background-color:var(--verde);font-size:12px;padding:3%;'>
							Cadastre-se grátis, comece a usar agora e pague só se quiser continuar usando após 30 dias.
						</p>
					</div>
				</div>
			</div>
		</div>
		preco card landing -->

		<!-- <div style='max-width:80%;margin:5% auto;'>
			<div id='testedriveinnerwrap' style='display:flex;flex-direction:column;background-color:var(--creme);border-radius:var(--radius);border:1px solid var(--roxo);padding:5% 8%;padding-top:3%;'>
				<p id='testedrivetitle' class='cardcabecalhonome'>
					Faça um teste drive
				</p>
				<div id='chamadatestedrive' style='flex:1;'>
					<p class='retorno'>Preencha seu nome e e-mail para fazer um teste grátis</p>
				</div>
				<div id='formulariotestedrive' style='flex:1;'>
					<div style='display:flex;flex-direction:column;gap:2%;background-color:var(--verde);border-radius:var(--radius);padding:5%;padding-top:3.6%;'>
						<div style='flex:1;'>
							<label>Seu nome completo</label>
							<input type='text' placeholder='Seu nome' name='nome' id='nomedrive'>
						</div>
						<div style='flex:1;'>
							<label>Seu melhor e-mail</label>
							<input type='email' placeholder='E-mail' name='email' id='emaildrive'>
						</div>
					</div>
				</div>
				<p id='testedrive' class='individualcta' style='margin:revert;text-align:center;cursor:pointer;'>Receber</p>
			</div>
		</div> -->

		<div class='bottomouterwrap'>
			<div class='landingtopouterwrap'>
				<div class='landingtopwrap'>
					<div class='landingbottompwrap'>
						<p class='landingbottomp'>
							Quer saber mais?
						</p>
						<a class='individualcta' href='<?php echo $dominio ?>/contato/'>
							Fale com a gente
						</a>
					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- conteudo -->

	<script>
		$('#testedrive').on('click',function() {
			nome = $('#nomedrive').val()||0;
			email = $('#emaildrive').val()||0;
			$.ajax({
				type: 'POST',
				dataType: 'json',
				async: true,
				url: '<?php echo $dominio ?>/includes/testedrive.inc.php',
				data: {
					nomedrive: nome,
					emaildrive: email
				},
				success: function(testedrive) {
					if (testedrive['titulo']!='') {
						$('#testedrivetitle').html(testedrive['titulo'])
						$('.retorno').html(testedrive['descricao']);
						$('#formulariotestedrive').css('display','none');
						$('#testedrive').css('display','none');
						$('#testedriveinnerwrap').css('background-color','var(--verde)');
					} else {
						$('.retorno').html('Preencha os campos corretamente');
					}
				}
			});
		});
	</script>

<?php
	require_once __DIR__.'/rodape.php';
?>
