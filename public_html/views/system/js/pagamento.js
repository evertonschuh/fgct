
$(document).ready(function(){
	
	jQuery("#dataDesconto").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	jQuery("#dataVencimento").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	jQuery("#dataPagamento").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	jQuery('.value-money').maskMoney({prefix:'R$ ', thousands:'.', decimal:','});	
	
	
	
	$('#id_pagamento_produto_tipo').change(function(){
		$('#user_id').html('<option value="0">'+Joomla.JText._('FBT_SCRIPT_SELET_LOADING')+'</option>');
		$('#id_pagamento_produto').html('<option value="0">'+Joomla.JText._('FBT_SCRIPT_SELET_LOADING')+'</option>');
		$.post('views/system/includes/dynamicselect.php', 
			  {id_pagamento_produto_tipo:$(this).val()},
			  function(valor){
				 $('#user_id').html(valor);
				 $('#id_pagamento_produto').html('<option disabled selected class="default" value="">Selecione o Cliente</option>');
			  }
		)
	});	
	
	$('#user_id').change(function(){
		$('#id_pagamento_produto').html('<option value="0">'+Joomla.JText._('FBT_SCRIPT_SELET_LOADING')+'</option>');
		$.post('views/system/includes/dynamicselect.php', 
			  {user_id:$(this).val(), id_pagamento_produto_tipo:$('#id_pagamento_produto_tipo').val()},
			  function(valor){
				 $('#id_pagamento_produto').html(valor);
			  }
		)
	});
	
	jQuery(".chosen").select2({
		selectOnClose: true,
		width: '100%'
	});
	
	jQuery('[name="type_pagamento"]').on('click', function() {	
	
	
		//jQuery('#off_cupom').slideUp();
		if(jQuery(this).val()==1) {
			jQuery('#id_produto').attr('disabled', false);
			jQuery('#anuidade').slideUp();
			jQuery('#produto').slideDown();
			jQuery('#id_anuidade').attr('disabled', true);
		}
		else {
			jQuery('#id_anuidade').attr('disabled', false);
			jQuery('#produto').slideUp();
			jQuery('#anuidade').slideDown();
			jQuery('#id_produto').attr('disabled', true);
		}
	});
	
	
	
	jQuery('#processar').on('click',function() {
		if($(this).is(':checked')) {
			jQuery('#valorpago_pagamento').addClass('required');
			jQuery('#confirmado_pagamento').addClass('required');
		}
		else {
			jQuery('#valorpago_pagamento').removeClass('required');
			jQuery('#confirmado_pagamento').removeClass('required');
		}
	});
	
	jQuery('#send_mail').on('click',function() {
		if($(this).is(':checked')) {
			jQuery('#valorpago_pagamento').addClass('required');
			jQuery('#confirmado_pagamento').addClass('required');
		}
		else {
			jQuery('#valorpago_pagamento').removeClass('required');
			jQuery('#confirmado_pagamento').removeClass('required');
		}
	});
	
	jQuery('[name="atualizar_pagamento"]').on('click', function() {	
		if(jQuery(this).val()==1) {
			jQuery('#foraPrazo').slideDown();
			jQuery('#valor_atualizar_pagamento').addClass('required');
			jQuery('#vencimento_atualizar_pagamento').addClass('required');
		}
		else{
			jQuery('#foraPrazo').slideUp();
			jQuery('#valor_atualizar_pagamento').removeClass('required');
			jQuery('#vencimento_atualizar_pagamento').removeClass('required');
		}
	});
	
	jQuery('#calcJuros').on('click', function() {	
		var dataOriginal = jQuery('#dataVencimento').data("DateTimePicker").date();
		var dataNova = jQuery('#dataVencimentoAtualizar').data("DateTimePicker").date();
		var valorOriginal = jQuery('#valor_pagamento').val();
		if (jQuery("#remove_image_evento").length && jQuery("#remove_image_evento").is(':checked')){
			valorOriginal = jQuery('#nonDiscountedValue').val();
		}
		
		if(dataOriginal != '' && dataOriginal != '' && dataOriginal < dataNova) {
			var date1 = new Date(dataOriginal);
			var date2 = new Date(dataNova);
			var timeDiff = Math.abs(date2.getTime() - date1.getTime());
			var diffDays = parseInt( Math.ceil(timeDiff / (1000 * 3600 * 24))); 
			var valorJuros = diffDays * (30/100);
			valorOriginal = parseFloat(valorOriginal.replace(".", "").replace(",", ".").replace("R$ ", ""));
			valorOriginal += 1 + valorJuros + valorOriginal*0.02;
			jQuery('#valor_atualizar_pagamento').val( 'R$ '+jQuery.number( valorOriginal, 2, ',', '.' ) );
			jQuery('#valor_atualizar_pagamento').parent().parent().removeClass('has-error')
			jQuery('#valor_atualizar_pagamento').parent().next('p').remove();
		}
		else {
			alert('Impossível calcular. Datas inconsistentes');
		}
	});
	
	
	
	
	
});

