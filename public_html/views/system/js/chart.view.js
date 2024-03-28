jQuery(document).ready(function(){

	jQuery('select[name="type_data_chart"]').change(function(){
		jQuery('select[name="data_chart"]').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {type_data_chart:jQuery(this).val()},
			  function(valor){
				 jQuery('select[name="data_chart"]').html(valor);
			  }
		)
	});	
	

	/*
	jQuery('.date').datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	jQuery('.datatime').datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
	});
	
	/*
	jQuery('.select2').select2({
		selectOnClose: true,
		width: '100%'
	});
	*//*
	jQuery('#workload_evento').maskMoney({thousands:'', decimal:':'});
	
	jQuery('#moeda_evento').change(function(){
 		moedas()
	});	
	
	function moedas()
	{
		jQuery('.value-money').each(function(){
			switch(jQuery('#moeda_evento').val()) {
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

	/*
	jQuery('input[name="modality_evento"]').change(function(){
		
		if(jQuery(this).val() == 1)
		{
			jQuery('#id_evento_type').addClass('required');			
			jQuery('#id_evento_type').attr('disabled',false);				
			jQuery('.local-evento').hide();
			jQuery('.type-evento').show();
			jQuery('#id_estado').removeClass('required');
			jQuery('#id_cidade').removeClass('required');
			jQuery('#id_estado').attr('disabled',true);
			jQuery('#id_cidade').attr('disabled',true);			
			
		}
		else
		{
			jQuery('#id_estado').addClass('required');
			jQuery('#id_cidade').addClass('required');	
			jQuery('#id_estado').attr('disabled',false);
			jQuery('#id_cidade').attr('disabled',false);				
			jQuery('.type-evento').hide();
			jQuery('.local-evento').show();	
			jQuery('#id_evento_type').removeClass('required');			
			jQuery('#id_evento_type').attr('disabled',true)
			
		}
	});	
	
	jQuery('input[name="continuous_evento"]').change(function(){
		if(jQuery(this).val() == 1)
		{
			jQuery('.no-continuous').hide();
			jQuery('.continuous').show();
			jQuery('.no-continuous').find('input').removeClass('required');
			jQuery('.continuous').find('input').addClass('required');
		}
		else
		{
			jQuery('.continuous').hide();
			jQuery('.no-continuous').show();	
			jQuery('.continuous').find('input').removeClass('required');
			jQuery('.no-continuous').find('input').addClass('required');
		}
	});	
	
	jQuery("input[name='discount_evento']").TouchSpin({
		min: null,
		max: 100,
		step: 1,
		decimals: 0,
		boostat: 5,
		maxboostedstep: 10,
		postfix: '%'
	});
	
	jQuery('#id_conteudo_type').change(function(){
		jQuery('#id_conteudo').html('<option value="0">'+Joomla.JText._('FBT_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {id_conteudo_type:jQuery(this).val()},
			  function(valor){
				 jQuery('#id_conteudo').html(valor);
			  }
		)
	});	
	
	jQuery('#id_estado').change(function(){
		jQuery('#id_cidade').html('<option value="0">'+Joomla.JText._('FBT_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('views/system/includes/dynamicselect.php', 
			  {id_estado:jQuery(this).val()},
			  function(valor){
				 jQuery('#id_cidade').html(valor+'<option value="0">Nenhum</option>');
			  }
		)
	});	
	
	jQuery("#plotsfirst").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	jQuery("#startenroll").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br'
	});
	
	jQuery("#closeenroll").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br'
	});

	jQuery("#start").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	jQuery("#close").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});

	
	
	jQuery(document).on('click', '.open-midia-mananger', function(){
		buttonImg = this;
		jQuery('#includeItem').hide();
		jQuery('#loadImg').show();
		jQuery('#includeItem').attr("src","index.php?view=imgmanager&layout=modal&tmpl=modal");
		jQuery('#midiaModal').modal({show:true})
		jQuery('#includeItem').on('load', function() {
			jQuery('#includeItem').show();
			jQuery('#includeItem').height( jQuery('#includeItem').contents().find("body").height()+ 30);
			jQuery('#loadImg').hide();
		});
	});
	
	jQuery('#insertImg').on('click', function() {
		var img = jQuery('#includeItem').contents().find('.imgLayerSelect:checked').val();
		if(img)	{
			jQuery('#midiaModal').modal('toggle');
			var item = jQuery('input[name="image_evento"]');
			item.val(img);
			item.parent().find('.img-thumbnail').attr('src', '/media/assets/img/store/'+img);
		}
		else
		{
			alert( 'É necessário selecionar alguma imgem para inserir');
		}
	});
	
	

	jQuery('textarea[name="description_evento"]').keyup(function() {
		var tamanho = 140 - (jQuery(this).val().length);
		jQuery('#contador_char').html(tamanho);
	});
*/
});


/*
function SomenteNumero(e)
{
	var tecla=(window.event)?event.keyCode:e.which;
	if((tecla > 47 && tecla < 58)) return true;
	else
	{
		if (tecla != 8) return false;
	else return true;
	}
}
*/
/*
function mostrarResultado(box,num_max,campospan){
	var contagem_carac = box.length;
	if (contagem_carac != 0){
		document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
		if (contagem_carac == 1){
			document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
		}
		if (contagem_carac >= num_max){
			document.getElementById(campospan).innerHTML = "Limite de caracteres excedido!";
		}
	}else{
		document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado..";
	}
}

function contarCaracteres(box,valor,campospan){
	var conta = valor - box.length;
	document.getElementById(campospan).innerHTML = "Você ainda pode digitar " + conta + " caracteres";
	if(box.length >= valor){
		document.getElementById(campospan).innerHTML = "Opss.. você não pode mais digitar..";
		document.getElementById("campo").value = document.getElementById("campo").value.substr(0,valor);
	}	
}
*/
