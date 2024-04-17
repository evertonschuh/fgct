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
	
	
	
    jQuery(document).on('change','.image-preview-input input:file', function (){          
        var file = this.files[0];
            jQuery(".image-preview-filename").val(file.name);            
    });  

	
	jQuery('#cep_pf').mask('00000-000');	
	jQuery('#cpf_pf').mask('000.000.000-00');

    var SPMaskBehavior = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	  },
	  spOptions = {
		onKeyPress: function(val, e, field, options) {
			field.mask(SPMaskBehavior.apply({}, arguments), options);
		  }
	  };
  
	jQuery('input[type=tel]').mask(SPMaskBehavior, spOptions);
	
	//jQuery('#cpf_pf').mask('999.999.999-99');
	//jQuery('#cep_pf').mask('99999-999');	
	//jQuery('input[type=tel]').mask("(99) 99999-999?9")
	//jQuery('.date').mask('99/99/9999');

    jQuery('#cpf_pf').on('blur', function() {
		if (document.formvalidator.validate('cpf_pf') == true) {
			jQuery.post('views/system/includes/checkregister.php', 
				{
					cpf_pf:jQuery(this).val(),
					id_user:jQuery('input[name="id_user"]').val()
				},
				function(resp){
					if(resp) {
						jQuery('#cpf_pf').parent('div').addClass('has-error');
						jQuery('#cpf_pf').next('label').remove();
						jQuery('#cpf_pf').after( '<label class="control-label">'+Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_CPF_REPEAT')+'</label>' );
					} 
					else {
						jQuery('#cpf_pf').parent('div').removeClass('has-error');
						jQuery('#cpf_pf').next('label').remove();
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
			jQuery.post('views/system/includes/checkregister.php', 
				{
					email:jQuery(this).val(),
					id_user:jQuery('input[name="id_user"]').val()
				},
				function(resp){
					if(resp) {
						jQuery('#email').parent('div').addClass('has-error');
						jQuery('#email').next('label').remove();
						jQuery('#email').after( '<label class="control-label">'+Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_EMAIL_REPEAT')+'</label>' );
					} 
					else {
						jQuery('#email').parent('div').removeClass('has-error');
						jQuery('#email').next('label').remove();
					}
				}
			)
		}
		else {
			jQuery('#email').parent('div').removeClass('has-error');
			jQuery('#email').next().next('label').remove();	
		}
    });

	jQuery('#naturalidade_uf_pf').change(function(){
		jQuery('#naturalidade_pf').html('<option value="0">'+Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {id_estado:jQuery(this).val()},
			  function(valor){
				 jQuery('#naturalidade_pf').html(valor);
			  }
		)
	});	
	
	jQuery('#id_estado').change(function(){
		jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {id_estado:jQuery(this).val()},
			  function(valor){
				 jQuery('#id_cidade').html(valor);
			  }
		)
	});	
	jQuery('#add_id_estado').change(function(){
		jQuery('#add_id_cidade').html('<option value="0">'+Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {id_estado:jQuery(this).val()},
			  function(valor){
				 jQuery('#add_id_cidade').html(valor);
			  }
		)
	});	
	
	jQuery('#estado_buscacep').change(function(){
		jQuery('#cidade_buscacep').html('<option value="0">'+Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {estado_buscacep:jQuery(this).val()},
			  function(valor){
				 jQuery('#cidade_buscacep').html(valor);
			  }
		)
	});	
	
	jQuery('#buscacep').on('hide.bs.modal', function () {		
		resetModaBuscaCep();
    });	
	

	
	jQuery('#submithsearch').on('click', function() {	
	
		if ( jQuery("#submithsearch").hasClass('new-search') ) {
			resetModaBuscaCep();
		}
		else if ( jQuery("#submithsearch").hasClass('validate') ) {	
				if (!validateform(jQuery(this).parents('.form-validate')))
					return false;
				else {
					jQuery('#loading-buscacep').append(spinner.el);
					jQuery.post('views/system/includes/buscaendereco.php', 
						  {estado_buscacep:jQuery('#estado_buscacep').val(),
						   cidade_buscacep:jQuery('#cidade_buscacep').val(),
						   logradouro_buscacep:jQuery('#logradouro_buscacep').val()},
						  function(valor){
							 jQuery('#cepresultmain').html(valor);
							 jQuery('#cepbuscar').hide();
							 jQuery('#cepresult').show();
							 jQuery('#submithsearch').html('<span class="glyphicon glyphicon-search"></span>&nbsp;'+Joomla.JText._('INTRANET_GLOBAL_BUTTON_NEW_SEARCH'));
							 jQuery('#submithsearch').removeClass('validate');
							 jQuery('#submithsearch').addClass('new-search');
							 jQuery('#loading-buscacep').children(spinner.el).remove();
						  }
					)
				}	
			}
	});
	
	jQuery('#cep_pf').blur(function(){
		jQuery('#loading').append(spinner.el);
		jQuery.post('views/system/includes/buscaendereco.php', 
			  {cep_endereco:jQuery(this).val()},
			  function(valor){
				 if(valor){
  					var obj = jQuery.parseJSON( valor )
					jQuery('#id_cidade').html(valor);
					jQuery('#id_cidade').next('label').remove();
					jQuery('#id_cidade').parent('div').removeClass('has-error');
					
					jQuery('#logradouro_pf').val(obj.logradouro );	
					jQuery('#logradouro_pf').next('label').remove();
					jQuery('#logradouro_pf').parent('div').removeClass('has-error');
					
					jQuery('#bairro_pf').val(obj.bairro);	
					jQuery('#bairro_pf').next('label').remove();
					jQuery('#bairro_pf').parent('div').removeClass('has-error');
					
					jQuery('#id_estado').val(obj.id_estado);
					jQuery('#id_estado').next('label').remove();
					jQuery('#id_estado').parent('div').removeClass('has-error');
					
					jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING')+'</option>');					
					jQuery.post('views/system/includes/dynamicselect.php', 
						{id_estado:obj.id_estado},
						function(valor){
							jQuery('#id_cidade').html(valor);
							jQuery('#id_cidade').val(obj.id_cidade);
							jQuery('#id_cidade').next('label').remove();
							jQuery('#id_cidade').parent('div').removeClass('has-error');
							
							jQuery('#loading').children(spinner.el).remove();
						}
					)
				}
				else {
			  		jQuery('#loading').children(spinner.el).remove();
			  	}	
			}
		)
	});
	
	
	jQuery('#add_cep_pf').blur(function(){
		jQuery('#add_loading').append(spinner.el);
		jQuery.post('views/system/includes/buscaendereco.php', 
			  {cep_endereco:jQuery(this).val()},
			  function(valor){
				 if(valor){
  					var obj = jQuery.parseJSON( valor )
					jQuery('#add_id_cidade').html(valor);
					jQuery('#add_id_cidade').next('label').remove();
					jQuery('#add_id_cidade').parent('div').removeClass('has-error');
					
					
					jQuery('#add_logradouro_pf').val(obj.logradouro );	
					jQuery('#add_logradouro_pf').next('label').remove();
					jQuery('#add_logradouro_pf').parent('div').removeClass('has-error');
					
					jQuery('#add_bairro_pf').val(obj.bairro);	
					jQuery('#add_bairro_pf').next('label').remove();
					jQuery('#add_bairro_pf').parent('div').removeClass('has-error');
					jQuery('#add_id_estado').val(obj.id_estado);
					jQuery('#add_id_estado').next('label').remove();
					jQuery('#add_id_estado').parent('div').removeClass('has-error');
					
					jQuery('#add_id_cidade').html('<option value="0">'+Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING')+'</option>');					
					jQuery.post('views/system/includes/dynamicselect.php', 
						{id_estado:obj.id_estado},
						function(valor){
							jQuery('#add_id_cidade').html(valor);
							jQuery('#add_id_cidade').val(obj.id_cidade);
							jQuery('#add_id_cidade').next('label').remove();
							jQuery('#add_id_cidade').parent('div').removeClass('has-error');
							jQuery('#add_loading').children(spinner.el).remove();
						}
					)
				}
				else {
					
			  		jQuery('#add_loading').children(spinner.el).remove();
					alert('CEP não encontrado');
			  	}	
			}
		)
	});
	
	
    jQuery('.image-preview-clear').click(function(){
		// $('.image-preview').attr("data-content","").popover('hide');
	    jQuery('.img-avatar-preview').attr('src', '').hide();
		jQuery('.img-avatar').show();

		jQuery('.image-preview-filename').val('');
		jQuery('.image-preview-filename').hide();  
		jQuery('.image-preview-clear').hide();
		jQuery('.image-preview-input input:file').val('');
		jQuery('.image-preview-input').show();
	 }); 
	 // Create the preview image
	 $(".image-preview-input input:file").change(function (){     

	 var file = this.files[0];

	 var reader = new FileReader();

	 reader.onload = function(e) {
	    jQuery('.img-avatar-preview').attr('src', e.target.result).show();
		jQuery('.img-avatar').hide();
		//jQuery('.img-avatar-preview').show();

		jQuery('.image-preview-input').hide();
		jQuery(".image-preview-filename").val(file.name);    
		jQuery(".image-preview-clear").show();
		jQuery('.image-preview-filename').show();  
	 }; 
  
		reader.readAsDataURL(file);
	 }); 
	
	
	
});

function inserirendereco(obj) {
	var id_estado = jQuery(obj).find("input[name='id_estado_respbuscacep[]']").val();
	var id_cidade = jQuery(obj).find("input[name='id_cidade_respbuscacep[]']").val();		
	
	jQuery('#cep_pf').val(jQuery(obj).find("input[name='cep_respbuscacep[]']").val());
	jQuery('#cep_pf').next('label').remove();
	jQuery('#cep_pf').parent('div').removeClass('has-error');
	
	
	jQuery('#logradouro_pf').val(jQuery(obj).find("input[name='logradouro_respbuscacep[]']").val());
	jQuery('#logradouro_pf').next('label').remove();
	jQuery('#logradouro_pf').parent('div').removeClass('has-error');
	
	jQuery('#bairro_pf').val(jQuery(obj).find("input[name='bairro_respbuscacep[]']").val());
	jQuery('#bairro_pf').next('label').remove();
	jQuery('#bairro_pf').parent('div').removeClass('has-error');
	
	jQuery('#id_estado').val(id_estado);
	jQuery('#id_estado').next('label').remove();
	jQuery('#id_estado').parent('div').removeClass('has-error');
	
	jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING')+'</option>');					
	
	jQuery.post('views/system/includes/dynamicselect.php', 
		{id_estado:id_estado},
		function(valor){
			jQuery('#id_cidade').html(valor);
			jQuery('#id_cidade').val(id_cidade);
			jQuery('#id_cidade').next('label').remove();
			jQuery('#id_cidade').parent('div').removeClass('has-error');
			jQuery('#buscacep').modal('hide');	
			resetModaBuscaCep()
		  }
	)		
};
function resetModaBuscaCep(img){
	jQuery('#cepresult').hide();
	jQuery('#cepresultmain').html('');
	jQuery('#estado_buscacep').val('');
	jQuery('#logradouro_buscacep').val('');
	jQuery('#cidade_buscacep').html('<option disabled="" selected="" class="default" value="">'+Joomla.JText._('INTRANET_GLOBAL_CIDADE_SELECT')+'</option>');
	jQuery('#submithsearch').html('<span class="glyphicon glyphicon-search"></span>&nbsp;'+Joomla.JText._('INTRANET_GLOBAL_BUTTON_SEARCH'));
	jQuery('#submithsearch').removeClass('new-search');
	jQuery('#submithsearch').addClass('validate');
	jQuery('#cepbuscar').show();
}

function validateform( form ){
	var error = false;
	jQuery(form).find('select').each(function(){
		if(jQuery(this).prop('required')){
			if(!jQuery(this).val()){
				jQuery(this).parent('div').addClass('has-error');
				jQuery(this).next('label').remove();
				jQuery(this).after( '<label class="control-label">'+Joomla.JText._('INTRANET_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED')+'</label>' );
				error = true;
			}
		} 	
	});	

	jQuery(form).find('input').each(function(){
		if(jQuery(this).prop('required')){
			if(!jQuery(this).val()){
				jQuery(this).next('label').remove();
				jQuery(this).after( '<label class="control-label">'+Joomla.JText._('INTRANET_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED')+'</label>' );
				jQuery(this).parent('div').addClass('has-error');
				error = true;
			}
		} 
	});	
		
	if (error)
		return false;
	else 
		return true;
}