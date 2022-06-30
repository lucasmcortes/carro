<div style='min-width:100%;max-width:100%;display:inline-block;margin:0 auto;'>
	<?php
		Icone('addaluguel','adicionar aluguel','addaluguelicon');
		Icone('addlocatario','adicionar locatário','addlocatarioicon');
		Icone('addveiculo','adicionar veículo','addveiculoicon');
		Icone('addmanutencao','adicionar manutenção','addmanutencaoicon');
	?>
	<script>
		$('#addaluguel').on('click',function () {
			calendarioPop(3,'fundamental',0);
		});
		$('#addlocatario').on('click',function () {
			window.location.href='<?php echo $dominio ?>/painel/locatarios/novo';
		});
		$('#addveiculo').on('click',function () {
			window.location.href='<?php echo $dominio ?>/painel/veiculos/novo';
		});
		$('#addmanutencao').on('click',function () {
			window.location.href='<?php echo $dominio ?>/painel/manutencoes/novo';
		});
	</script>
</div>
