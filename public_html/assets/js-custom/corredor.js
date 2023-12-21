jQuery(document).ready(function(){

	jQuery('input:file').change(function (){     
		var file = this.files[0];
		var reader = new FileReader();
		reader.onload = function(e) {
		   jQuery('.user-corredor').attr('src', e.target.result).show();
		   jQuery('.upload-image').hide();
		   jQuery('.skip-upload').show(); 
		}; 
		reader.readAsDataURL(file);
	}); 

    jQuery('.skip-upload').click(function(){
	    jQuery('.user-corredor').attr('src', jQuery('#imgSRC').val()).hide();
		jQuery('.upload-image').show();
		jQuery('.skip-upload').hide();  
		jQuery('input:file').val('');
	 }); 



	 jQuery('.date').datepicker({
		format: 'dd/mm/yyyy',
		language: 'pt-BR',
		autoclose: true,
		todayHighlight: true,
	});
/*
	 jQuery('.date').datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	*/
	jQuery('.select2').select2({
		selectOnClose: true,
		width: '100%',
	});
	
	jQuery('#cep_corredor').mask('00000-000');	
	jQuery('.validate-cpf').mask('000.000.000-00');
	
	jQuery('#id_estado').change(function(){
		jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('/dynamic/select.php', 
			{id_estado:jQuery(this).val()},
			function(valor){
				jQuery('#id_cidade').html(valor);
			}
		)
	});	

	
	jQuery('#cpf_corredor').on('blur', function() {
		validateCPF(jQuery(this));
    });


	function validateCPF(element){
		if (document.formvalidator.validate('cpf_corredor') == true) {
			var data = {
				cpf_pf: element.val(),
				id_associado: jQuery('#cid').val(),
			}
			jQuery.post('/dynamic/check.php', 
				{value:JSON.stringify(data),
					execute:'check-cpf'},
				function(valor){
					if(valor === 'error'){
						jQuery('#doc').parent('div').addClass('has-error');
						if(jQuery('#doc').next('p.error').length)
							jQuery('#doc').next('p.error').html(Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_CPF_REPEAT'));
						else
							jQuery('#doc').after( '<p class="error">'+Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_CPF_REPEAT')+'</p>' );
					}
				}
			);
		}
	}

	jQuery('#complete').click(function(){
		var CEP = jQuery('#cep_corredor').val();
		var Control = jQuery(this);

		Control.attr('disabled',true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
		
		jQuery.post('/dynamic/completCep.php', 
			  {cep_endereco:CEP},
			  function(valor){
				 if(valor){
  					var obj = jQuery.parseJSON( valor )
					jQuery('#logradouro_corredor').val(obj.logradouro );	
					jQuery('#bairro_corredor').val(obj.bairro);	
					jQuery('#id_estado').val(obj.id_estado);
					jQuery('#id_estado').select2();
					jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING')+'</option>');					
					jQuery.post('/dynamic/select.php', 
						{id_estado:obj.id_estado},
						function(valor){
							jQuery('#id_cidade').html(valor);
							jQuery('#id_cidade').val(obj.id_cidade);
							jQuery('#id_cidade').select2();
							Control.attr('disabled',false).html('<i class="bx bx-search"></i>');
						}
					)
				}
				else {
					Control.attr('disabled',false).html('<i class="bx bx-search"></i>');
			  	}	
			}
		)
	});
});