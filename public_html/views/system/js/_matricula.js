jQuery(document).ready(function(){
/*
	jQuery('.add-user').click(function(){
		jQuery('#user_select').hide();
		jQuery('#user_id').attr("disabled", true);
		jQuery('#name').attr("disabled", false);
		jQuery('#email').attr("disabled", false);
		jQuery('#cpf_pf').attr("disabled", false);
		jQuery('#user_new').show(300);
	});	
	jQuery('.select-user').click(function(){
		
		jQuery('#user_new').hide();
		jQuery('#name').attr("disabled", true);
		jQuery('#email').attr("disabled", true);
		jQuery('#cpf_pf').attr("disabled", true);
		jQuery('#user_id').attr("disabled", false);
		jQuery('#user_select').show(300);
	});	
	
	jQuery('#cpf_pf').mask('999.999.999-99');
    jQuery('#cpf_pf').on('blur', function() {
		if (document.formvalidator.validate('cpf_pf') == true) {
			jQuery.post('views/system/includes/checkregister.php',{
					cpf_pf:jQuery(this).val()},
				function(resp){
					if(resp) {
						jQuery('#cpf_pf').parent('div').addClass('has-error');
						jQuery('#cpf_pf').next('p').remove();
						jQuery('#cpf_pf').after( '<p class="control-label">'+Joomla.JText._('FBT_SCRIPT_VALIDATE_ERROR_INPUT_CPF_REPEAT')+'</p>' );
					} 
					else {
						jQuery('#cpf_pf').parent('div').removeClass('has-error');
						jQuery('#cpf_pf').next('p').remove();
					}
				}
			)
		}
		else {
			jQuery('#cpf_pf').parent('div').removeClass('has-error');
			jQuery('#cpf_pf').next().next('label').remove();	
		}
    });
	
	
    jQuery('#email').on('blur', function() {
		if (document.formvalidator.validate('email') == true) {
			jQuery.post('views/system/includes/checkregister.php', {
					email:jQuery(this).val()},
				function(resp){
					if(resp) {
						jQuery('#email').parent('div').addClass('has-error');
						jQuery('#email').next('p').remove();
						jQuery('#email').after( '<p class="control-label">'+Joomla.JText._('FBT_SCRIPT_VALIDATE_ERROR_INPUT_EMAIL_REPEAT')+'</p>' );
					} 
					else {
						jQuery('#email').parent('div').removeClass('has-error');
						jQuery('#email').next('p').remove();
					}
				}
			)
		}
		else {
			jQuery('#email').parent('div').removeClass('has-error');
			jQuery('#email').next().next('label').remove();	
		}
    });
	*/
	
	jQuery('.date').datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
	});
	/*
	jQuery(".select2").select2({
		selectOnClose: true,
		width: '100%'
	});
	
 	jQuery('.select2').change(function () {
		var id = jQuery(this).attr('id');
		document.formvalidator.validate(id);
	});	
	*/	
	jQuery('#approval_inscricao').change(function(){
		if(jQuery(this).val() == 1) {
			jQuery('#displayPrazo').slideDown();
			jQuery('#displayMatric').slideUp();
			jQuery('#validate_approval_inscricao').addClass('required');
			jQuery('#validate_approval_inscricao').attr("disabled", false);
			jQuery('#enroll_inscricao').removeClass('required');
			jQuery('#enroll_inscricao').attr("disabled", true);
			jQuery('.matricula').removeClass('required');
		}
		else if(jQuery(this).val() == 2)  {
			jQuery('#displayPrazo').slideUp();
			jQuery('#displayMatric').slideDown();
			jQuery('#validate_approval_inscricao').removeClass('required');
			jQuery('#validate_approval_inscricao').attr("disabled", true);
			jQuery('#enroll_inscricao').addClass('required');
			jQuery('#enroll_inscricao').attr("disabled", false);
			jQuery('.matricula').addClass('required');
			jQuery('#id_polo_cruzada').removeClass('required');
			jQuery('#id_parceiro').removeClass('required');
			
		}
		else {
			jQuery('#displayPrazo').slideUp();
			jQuery('#displayMatric').slideUp();
			jQuery('#validate_approval_inscricao').removeClass('required');
			jQuery('#validate_approval_inscricao').attr("disabled", true);
			jQuery('#enroll_inscricao').removeClass('required');
			jQuery('#enroll_inscricao').attr("disabled", true);
			jQuery('.matricula').removeClass('required');
		}
	});	
	
	jQuery('#id_situation').change(function(){
		if(jQuery(this).val() != 1 && jQuery(this).val() != 5) {
			jQuery('#displayBlock').slideDown();
			jQuery('#lock_inscricao').addClass('required');
			jQuery('#lock_inscricao').attr("disabled", false);
		}
		else {
			jQuery('#displayBlock').slideUp();
			jQuery('#lock_inscricao').removeClass('required');
			jQuery('#lock_inscricao').attr("disabled", true);
		}
	});	
	
	
	
	/*
	jQuery('#descount_type').change(function(){
		var selected = jQuery(this).val();	

		if(  selected != '0') {
			switch ( selected ) {
				case '1':
				case '2':
				case '3':
					jQuery('#displayDesconto').slideDown();
					jQuery('#displayDescontoValor').slideUp();
					jQuery('#displayConvenio').slideUp();
					jQuery('#displayCupom').slideUp();
					jQuery('#descount_inscricao').addClass('required');
					jQuery('#descount_inscricao_value').removeClass('required');
					jQuery('#name_cupom').removeClass('required');
					jQuery('#id_convenio').removeClass('required');
					jQuery('#descount_inscricao').attr("disabled", false);
					jQuery('#descount_inscricao_value').attr("disabled", true);
				break; 

				case '4':
					jQuery('#displayDesconto').slideDown();
					jQuery('#displayDescontoValor').slideUp();
					jQuery('#displayConvenio').slideDown();
					jQuery('#displayCupom').slideUp();
					jQuery('#descount_inscricao').addClass('required');
					jQuery('#descount_inscricao_value').removeClass('required');
					jQuery('#name_cupom').removeClass('required');
					jQuery('#id_convenio').addClass('required');
					jQuery('#descount_inscricao').attr("disabled", false);
					jQuery('#descount_inscricao_value').attr("disabled", true);
				break; 
				case '5':
					jQuery('#displayDesconto').slideDown();
					jQuery('#displayDescontoValor').slideUp();
					jQuery('#displayConvenio').slideUp();
					jQuery('#displayCupom').slideDown();
					jQuery('#descount_inscricao').addClass('required');
					jQuery('#descount_inscricao_value').removeClass('required');
					jQuery('#name_cupom').addClass('required');
					jQuery('#id_convenio').removeClass('required');
					jQuery('#descount_inscricao').attr("disabled", false);
					jQuery('#descount_inscricao_value').attr("disabled", true);
				break;
				case '6':
					jQuery('#displayDesconto').slideUp();
					jQuery('#displayDescontoValor').slideDown();
					jQuery('#displayConvenio').slideUp();
					jQuery('#displayCupom').slideDown();
					jQuery('#descount_inscricao').removeClass('required');
					jQuery('#descount_inscricao_value').addClass('required');
					jQuery('#name_cupom').addClass('required');
					jQuery('#id_convenio').removeClass('required');
					jQuery('#descount_inscricao').attr("disabled", true);
					jQuery('#descount_inscricao_value').attr("disabled", false);
				break; 
			}
		}
		else {
			jQuery('#displayDesconto').slideUp();
			jQuery('#displayDescontoValor').slideUp();
			jQuery('#displayConvenio').slideUp();
			jQuery('#displayCupom').slideUp();
			jQuery('#descount_inscricao').removeClass('required');
			jQuery('#descount_inscricao_value').removeClass('required');
			jQuery('#descount_inscricao').attr("disabled", false);
			jQuery('#descount_inscricao_value').attr("disabled", true);
			//jQuery('#name_cupom').removeClass('required');
			//jQuery('#id_convenio').removeClass('required');
		
		}
	});
	
	jQuery('#id_evento').change(function(){
		var id_evento_var = jQuery(this).val();
		jQuery('#id_turma').html('<option value="0">'+Joomla.JText._('FBT_SCRIPT_SELET_LOADING')+'</option>');
		jQuery.post('views/system/includes/dynamicselect.php', 
			{id_evento:id_evento_var},
			function(valor){
				jQuery('#id_turma').html(valor);
				
				//alert(id_evento_var);
				jQuery.post('views/system/includes/dynamicselect.php', 
					{id_evento_moeda:id_evento_var},
					function(valor1){
						//alert(valor1);
						jQuery('input[name="moeda_evento"]').val(valor1);
						moedas();
					}
 				)
			}
		)
	});	
	*/
	
	function moedas()
	{
		jQuery('.value-money').each(function(){
			switch(jQuery('input[name="moeda_evento"]').val()) {
				case 'BRL':
					var maskPrefix = 'R$ ';
					var maskSuffix = '';
					var maskThousands = '.';
					var maskDecimal = ',';
				break;
				case 'EUR':
					var maskPrefix = '';
					var maskSuffix = ' €';
					var maskThousands = '';
					var maskDecimal = '.';
				break;	
				case 'USD':
					var maskPrefix = 'US$ ';
					var maskSuffix = '';
					var maskThousands = ',';
					var maskDecimal = '.';
				break;	
				default:
					return false;
				break;			
			}
			
			if(jQuery(this).val())						
				jQuery(this).maskMoney({prefix:maskPrefix, suffix:maskSuffix, thousands:maskThousands, decimal:maskDecimal, allowEmpty: true}).trigger('mask.maskMoney');	
			else
				jQuery(this).maskMoney({prefix:maskPrefix, suffix:maskSuffix, thousands:maskThousands, decimal:maskDecimal, allowEmpty: true});
		});
	}
	moedas();

});