jQuery(document).ready(function(){

	jQuery(".date").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	jQuery(".select2").select2({
		selectOnClose: true,
		width: '100%'
	});
	
	jQuery("input[name='ator_movimentacao']").mask('000.000.000-00');

	jQuery("#entrada").on('show.bs.modal', function(){
		jQuery("#adminFormEntrada input").prop("disabled", false);
		jQuery("#adminFormEntrada select").prop("disabled", false);
	});
	jQuery("#entrada").on('hidden.bs.modal', function(){
		jQuery("#adminFormEntrada input").prop("disabled", true);
		jQuery("#adminFormEntrada select").prop("disabled", true);
	});

	jQuery("#saida").on('show.bs.modal', function(){
		jQuery("#adminFormSaida input").prop("disabled", false);
		jQuery("#adminFormSaida select").prop("disabled", false);
	});
	jQuery("#saida").on('hidden.bs.modal', function(){
		jQuery("#adminFormSaida input").prop("disabled", true);
		jQuery("#adminFormSaida select").prop("disabled", true);
		jQuery("#adminFormSaida input").parent('div').removeClass('has-error');
		jQuery("#adminFormSaida select").parent('div').removeClass('has-error');
		jQuery("#adminFormSaida input").next('p').remove();
		jQuery("#adminFormSaida select").next('p').remove();
	});

	jQuery("#transferencia").on('show.bs.modal', function(){
		jQuery("#adminFormTrasnferencia input").prop("disabled", false);
		jQuery("#adminFormTrasnferencia select").prop("disabled", false);
	});
	jQuery("#transferencia").on('hidden.bs.modal', function(){
		jQuery("#adminFormTrasnferencia input").prop("disabled", true);
		jQuery("#adminFormTrasnferencia select").prop("disabled", true);
	});



});
