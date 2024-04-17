jQuery(document).ready(function(){

	jQuery('#cpf_pf').mask('999.999.999-99');
	jQuery('#cep_pf').mask('99999-999');	
	jQuery('input[type=tel]').mask("(99) 9999-9999?9")
	jQuery('#data_nascimento_pf').mask('99/99/9999');
	jQuery('#data_expedicao_pf').mask('99/99/9999');
	
	jQuery('#id_estado').change(function(){
		jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {id_estado:jQuery(this).val()},
			  function(valor){
				 jQuery('#id_cidade').html(valor);
			  }
		)
	});	
	
	jQuery('#estado_buscacep').change(function(){
		jQuery('#cidade_buscacep').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');	
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
	
	jQuery('.date').datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
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
							 jQuery('#submithsearch').html('<span class="glyphicon glyphicon-search"></span>&nbsp;'+Joomla.JText._('EASISTEMAS_GLOBAL_BUTTON_NEW_SEARCH'));
							 jQuery('#submithsearch').removeClass('validate');
							 jQuery('#submithsearch').addClass('new-search');
							 jQuery('#loading-buscacep').children(spinner.el).remove();
						  }
					)
				}	
			}
	});
	
	jQuery('#cep_pf').blur(function(){
		if(jQuery('#cep_pf').val()=='')
			return;
		jQuery('#loading').append(spinner.el);
		jQuery.post('views/system/includes/buscaendereco.php', 
			  {cep_endereco:jQuery(this).val()},
			  function(valor){
				 if(valor){
  					var obj = jQuery.parseJSON( valor )
					jQuery('#id_cidade').html(valor);
					jQuery('#id_cidade').next('p').remove();
					jQuery('#id_cidade').parent('div').removeClass('has-error');
					
					
					jQuery('#logradouro_pf').val(obj.logradouro );	
					jQuery('#logradouro_pf').next('p').remove();
					jQuery('#logradouro_pf').parent('div').removeClass('has-error');
					
					jQuery('#bairro_pf').val(obj.bairro);	
					jQuery('#bairro_pf').next('p').remove();
					jQuery('#bairro_pf').parent('div').removeClass('has-error');
					jQuery('#id_estado').val(obj.id_estado);
					jQuery('#id_estado').next('p').remove();
					jQuery('#id_estado').parent('div').removeClass('has-error');
					
					jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');					
					jQuery.post('views/system/includes/dynamicselect.php', 
						{id_estado:obj.id_estado},
						function(valor){
							jQuery('#id_cidade').html(valor);
							jQuery('#id_cidade').val(obj.id_cidade);
							jQuery('#id_cidade').next('p').remove();
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


	jQuery('input[type="file"]').change(function(){
        var filename = jQuery(this).val();
        var extension = filename.replace(/^.*\./, '');
	    var file = this.files[0];
		var data = new FormData();

		jQuery('.image-preview-input').hide();
		jQuery(".image-preview-clear").show();
		jQuery(".image-preview-filename").val(file.name);  

		//jQuery.each(jQuery('#file_return')[0].files, function(i, file) {
		data.append('filename', file);
		//})

		//data.append('file', file);
        if (extension == filename) {
            extension = '';
        } else {
            extension = extension.toLowerCase();
        }
        switch (extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
				jQuery(' .imgareaselect-selection').remove(); 
				jQuery(' .imgareaselect-outer').remove(); 
				jQuery('#loading_img').append(spinner.el);
				jQuery.ajax({
					url: "views/system/includes/uploadaction.php", // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(data)   // A function to be called if request succeeds
					{
						jQuery('#loading_img').children(spinner.el).remove();
						jQuery('#submithimage').attr('disabled',false);
						jQuery('#pathimage').val(data);
						jQuery('#image').attr('src',data);
						jQuery('#image').attr('alt','Imagem Carregada');
						jQuery('#image').cropper(options);
							
					}
				});	
			break;
            
			default:
               alert('Tipo de Arquivo incompatível');
 			break;
        }	
	});
	
    jQuery('.image-preview-clear').click(function(){	
		resetModalImage();

		
    }); 
	
	jQuery('#avatar-modal').on('hide.bs.modal', function () {		
		resetModalImage();
    });	
	

	
	
	/*
    // Create the preview image
    jQuery(" #file_return").change(function (){     
	 	jQuery('#loading').append(spinner.el);	
	    var file = this.files[0];
		var data = new FormData();

		jQuery('.image-preview-input').hide();
		jQuery(".image-preview-clear").show();
		jQuery(".image-preview-filename").val(file.name);  

		jQuery.each(jQuery('#file_return')[0].files, function(i, file) {
			data.append('file'+i, file);
		})
		
		jQuery.ajax({
			url: "views/system/includes/retorno.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				jQuery('#loading').children(spinner.el).remove();
				jQuery("#load_return").html(data);
				if ( jQuery('input[name="cid[]"]' ).length )
					jQuery("#import").attr('disabled', false);
				if ( jQuery('td' ).length ) {
					jQuery("#print").attr('disabled', false);	
					jQuery("#print").attr('href', 'index.php?view=finretorno&layout=report&tmpl=print&session=' + jQuery("#sessionid").val() + '&file=' + jQuery("#filename").val());
				}
					
			}
		});
    });  
*/

  var $dataX = jQuery('#dataX');
  var $dataY = jQuery('#dataY');
  var $dataHeight = jQuery('#dataHeight');
  var $dataWidth = jQuery('#dataWidth');
  var $dataRotate = jQuery('#dataRotate');
  var $dataScaleX = jQuery('#dataScaleX');
  var $dataScaleY = jQuery('#dataScaleY');

	var options = {
        aspectRatio: 1,
        preview: '.preview',
        crop: function (e) {
    	$dataX.val(Math.round(event.detail.x));
 		$dataY.val(Math.round(event.detail.y));
    	$dataWidth.val(Math.round(event.detail.width));
		$dataHeight.val(Math.round(event.detail.height));
     	$dataRotate.val(event.detail.rotate);
     	$dataScaleX.val(event.detail.scaleX);
    	$dataScaleY.val(event.detail.scaleY);			
        }
      };


});
function resetModalImage(){
	jQuery('#image').cropper("destroy");
	jQuery('#image').attr('src','/cache/tmp_no_image_avatar.png');
	jQuery('#submithimage').attr('disabled',true);
	jQuery('#pathimage').val('');
	jQuery('.image-preview-filename').val("");
	jQuery('.image-preview-clear').hide();
	jQuery('.image-preview-input input:file').val("");
	jQuery('.image-preview-input').show();
}


function imageload(img){
	//jQuery('#save_image').attr('disabled', false );
	jQuery('#div_mesg_carrega').hide();
	jQuery('#image_path').val('cache/com_torneios/'+img);
	jQuery('#thumbnail').attr('src', 'cache/com_torneios/'+img );
	jQuery('#thumbnail_pev').attr('src', 'cache/com_torneios/'+img );
	
	
	jQuery('#loading').children(spinner.el).remove();

	jQuery('#thumbnail').imgAreaSelect({ x1:0, y1:0, x2: 118, y2: 118 });
}
	
	
function inserirendereco(obj) {
	var id_estado = jQuery(obj).find("input[name='id_estado_respbuscacep[]']").val();
	var id_cidade = jQuery(obj).find("input[name='id_cidade_respbuscacep[]']").val();		
	
	jQuery('#cep_pf').val(jQuery(obj).find("input[name='cep_respbuscacep[]']").val());
	jQuery('#cep_pf').next('p').remove();
	jQuery('#cep_pf').parent('div').removeClass('has-error');
	
	
	jQuery('#logradouro_pf').val(jQuery(obj).find("input[name='logradouro_respbuscacep[]']").val());
	jQuery('#logradouro_pf').next('p').remove();
	jQuery('#logradouro_pf').parent('div').removeClass('has-error');
	
	jQuery('#bairro_pf').val(jQuery(obj).find("input[name='bairro_respbuscacep[]']").val());
	jQuery('#bairro_pf').next('p').remove();
	jQuery('#bairro_pf').parent('div').removeClass('has-error');
	
	jQuery('#id_estado').val(id_estado);
	jQuery('#id_estado').next('p').remove();
	jQuery('#id_estado').parent('div').removeClass('has-error');
	
	jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');					
	
	jQuery.post('views/system/includes/dynamicselect.php', 
		{id_estado:id_estado},
		function(valor){
			jQuery('#id_cidade').html(valor);
			jQuery('#id_cidade').val(id_cidade);
			jQuery('#id_cidade').next('p').remove();
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
	jQuery('#cidade_buscacep').html('<option disabled="" selected="" class="default" value="">'+Joomla.JText._('EASISTEMAS_GLOBAL_CIDADE_SELECT')+'</option>');
	jQuery('#submithsearch').html('<span class="glyphicon glyphicon-search"></span>&nbsp;'+Joomla.JText._('EASISTEMAS_GLOBAL_BUTTON_SEARCH'));
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
				jQuery(this).next('p').remove();
				jQuery(this).after( '<p class="control-label">'+Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED')+'</p>' );
				error = true;
			}
		} 	
	});	

	jQuery(form).find('input').each(function(){
		if(jQuery(this).prop('required')){
			if(!jQuery(this).val()){
				jQuery(this).next('p').remove();
				jQuery(this).after( '<p class="control-label">'+Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED')+'</p>' );
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