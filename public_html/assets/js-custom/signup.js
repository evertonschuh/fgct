jQuery(function () {

	jQuery('.save-reserve').on('click', function(){
		var amount = jQuery('input[name="method"]:checked').data('amount');
		var complement = '';
		if(amount>1)
			complement = ' Você optou por realizar '+amount+ ' reservas. Confira se todas as reservas estão totalmente preencidas e tente confirmar novamente.';
		for(i=1;i<=amount;i++){

			if(!jQuery('#position_'+i).find('input[name="position['+i+']"]:checked').length)
			{

				Swal.fire({
					icon: 'warning',
					title: 'Ops!',
					text: 'Sua Reserva nâo está completa e por este motivo nâo é possível cadastra-lá.'+complement,
					confirmButtonText: 'Entendi',
				   //timer: 8000
				  })
				return false;
			}
		}
		Joomla.submitform();

	});
	jQuery('input[name="method"]').click(function() {
		var amount = jQuery(this).data('amount');
		
		jQuery('#tabAmounts .nav-item a').each(function() { 
			if(jQuery(this).data('numero') <= amount)
				jQuery(this).removeClass('disabled').parent().removeClass('disabled');
			else {
				jQuery(this).addClass('disabled').parent().addClass('disabled');
				if(jQuery(this).hasClass('active')) {
					for(i=jQuery(this).data('numero'); i>=1;i--) {
						if(!jQuery('#reserve-tab' + i).hasClass('disabled'))
							jQuery('#reserve-tab' + i).tab('show');
							//jQuery('#reserve-tab' + i).prop( "active", true );
					}
				}
			}
				
		});	

	});		

	jQuery(document).on('click', '.agenda-data', function() {
		
		var evento = jQuery('input[name="id_event"]').val();
		var numero = jQuery(this).data('numero');
		var data = jQuery(this).val();
		var InfoX = getInfoX(numero);

		jQuery('#drums_'+numero).html(insertLoading());
		getBaterias( evento, numero, data, InfoX.data_x, InfoX.bateria_x, InfoX.turma_x, InfoX.posto_x)	
	});	
	

	jQuery(document).on('click', '.agenda-bateria', function() {
		
		var evento = jQuery('input[name="id_event"]').val();
		var numero = jQuery(this).data('numero');
		var bateria = jQuery(this).val();
		
		var Info = getInfo(numero);
		var InfoX = getInfoX(numero);


		jQuery('#squad_'+numero).html(insertLoading());
		getTurmas(evento, numero, Info.data, bateria, InfoX.data_x, InfoX.bateria_x, InfoX.turma_x, InfoX.posto_x)

	});		

	jQuery(document).on('click', '.agenda-turma', function(){
		
		var evento = jQuery('input[name="id_event"]').val();
		var numero = jQuery(this).data('numero');
		var turma = jQuery(this).val();
		
		var Info = getInfo(numero);
		var InfoX = getInfoX(numero);


		jQuery('#position_'+numero).html(insertLoading());	
		getPostos(evento, numero, Info.data, Info.bateria, turma, InfoX.data_x, InfoX.bateria_x, InfoX.turma_x, InfoX.posto_x)	
				
	});	
	
	jQuery(document).on('click', '.agenda-posto', function(){
	
		var id = jQuery(this).attr('id');
		var evento = jQuery('input[name="id_event"]').val();
		var numero = jQuery(this).data('numero');
		
		if( jQuery(this).is(':checked') ){
			
			jQuery('#position_'+numero + ' input').each(function() { 
				if(jQuery(this).data('numero') == numero && jQuery(this).attr('id') != id && jQuery(this).is(':enabled'))
					jQuery('label[for="'+jQuery(this).attr('id')+'"] span.name').html('Disponível');
			});			
			//alert(jQuery('#name_user').val());
			jQuery('label[for="'+id+'"] span.name').html(jQuery('#name_user').val());
		}

		completControlsNext(evento, numero)


	});	

	function getInfo(numero){
		var Info = {};

		Info.data = 0;
		Info.bateria = 0;
		Info.turma= 0;
		Info.posto = 50;

		if(jQuery('#date_'+numero).length)
			Info.data = jQuery('#date_'+numero).find('input[name="date['+numero+']"]:checked').val();
		else
			Info.data = jQuery('input[name="date['+numero+']"]').val();

		if(jQuery('#drums_'+numero).length)
			Info.bateria = jQuery('#drums_'+numero).find('input[name="drums['+numero+']"]:checked').val();
		else
			Info.bateria = jQuery('input[name="drums['+numero+']"]').val();

		if(jQuery('#squad_'+numero).length)
			Info.turma = jQuery('#squad_'+numero).find('input[name="squad['+numero+']"]:checked').val();
		else
			Info.turma = jQuery('input[name="squad['+numero+']"]').val();
		
		if(jQuery('#position_'+numero).length)
			Info.posto = jQuery('#position_'+numero).find('input[name="position['+numero+']"]:checked').val();
		else
			Info.posto = jQuery('input[name="position['+numero+']"]').val();				


		return Info;

	}

	
	function getInfoX(numero){
		var InfoX = {};

		InfoX.data_x = 0;
		InfoX.bateria_x = 0;
		InfoX.turma_x = 0;
		InfoX.posto_x = 50;
		
		if(numero>1){	

			if(jQuery('#date_'+(numero-1)).length)
				InfoX.data_x = jQuery('#date_'+(numero-1)).find('input[name="date['+(numero-1)+']"]:checked').val();
			else
				InfoX.data_x = jQuery('input[name="date['+(numero-1)+']"]').val();

			if(jQuery('#drums_'+(numero-1)).length)
				InfoX.bateria_x = jQuery('#drums_'+(numero-1)).find('input[name="drums['+(numero-1)+']"]:checked').val();
			else
				InfoX.bateria_x = jQuery('input[name="drums['+(numero-1)+']"]').val();

			if(jQuery('#squad_'+(numero-1)).length)
				InfoX.turma_x = jQuery('#squad_'+(numero-1)).find('input[name="squad['+(numero-1)+']"]:checked').val();
			else
				InfoX.turma_x = jQuery('input[name="squad['+(numero-1)+']"]').val();
			
			if(jQuery('#position_'+(numero-1)).length)
				InfoX.posto_x = jQuery('#position_'+(numero-1)).find('input[name="position['+(numero-1)+']"]:checked').val();
			else
				InfoX.posto_x = jQuery('input[name="position['+(numero-1)+']"]').val();				

		}

		return InfoX;

	}


	function insertLoading(){
		return '<div id="bars3"><span></span><span></span><span></span><span></span><span></span></div>';
	}


    jQuery('.validate').on('click', function(){
		var testValue = false;
		var returnValue = true;
		jQuery( '.validate-posto' ).each(function( index ) {
			testValue = false;
			jQuery(this).find('.agenda-posto').each(function(){
				if(jQuery(this).is(':checked')) {
					testValue = true;
				}
			});
			if( testValue == false){
				returnValue = false;	
				jQuery(this).addClass('invalid');
				return false;
			}				
		});
		if( returnValue == false)
			return false;
		
	});
	
	function completControlData(numero, obj)
	{
	
		if(jQuery('#date_'+numero + 'input[name="date"]').prop('type')=='hidden')
			if(obj.response != 'success')
				jQuery('#date_'+numero + 'input[name="date"]').val('');
			else
				jQuery('#date_'+numero + 'input[name="date"]').val(obj.value);
		else
		if(obj.response != 'success')
			jQuery('#date_'+numero).html('Aguardando Definir Etapas Anteriores.');
		else
			jQuery('#date_'+numero).html(obj.html);
				
		obj.response = null;
		completControlBateria(numero, obj);
	}
	
	function completControlBateria(numero, obj)
	{
		if(jQuery('#drums_'+numero + 'input[name="drums['+numero + ']"]').prop('type')=='hidden')
			if(obj.response != 'success')
				jQuery('#drums_'+numero + 'input[name="drums['+numero + ']"]').val('');
			else
				jQuery('#drums_'+numero + 'input[name="drums['+numero + ']"]').val(obj.value);
		else
		if(obj.response != 'success')
			jQuery('#drums_'+numero).html('Aguardando Definir Etapas Anteriores.');
		else
			jQuery('#drums_'+numero).html(obj.html);
				
		obj.response = null;
		completControlTurma(numero, obj);
	}
	
	function completControlTurma( numero, obj)
	{				

		if(jQuery('#squad_'+numero + 'input[name="squad"]').prop('type')=='hidden')
			if(obj.response != 'success')
				jQuery('#squad_'+numero + 'input[name="squad"]').val('');
			else
				jQuery('#squad_'+numero + 'input[name="squad"]').val(obj.value);
		else
			if(obj.response != 'success')
				jQuery('#squad_'+numero).html('Aguardando Definir Etapas Anteriores.');
			else
				jQuery('#squad_'+numero).html(obj.html);

		obj.response = null;
		completControlPosto(numero, obj);	
	}
	
	function completControlPosto(numero, obj)
	{	
		if(jQuery('#position_'+numero + 'input[name="position"]').prop('type')=='hidden')
			if(obj.response != 'success')
				jQuery('#position_'+numero + 'input[name="position"]').val('');
			else
				jQuery('#position_'+numero + 'input[name="position"]').val(obj.value);
		else
			if(obj.response != 'success')
				jQuery('#position_'+numero).html('Aguardando Definir Etapas Anteriores.');
			else
				jQuery('#position_'+numero).html(obj.html);

	}
	
	
	function completControlsNext(evento, numero)
	{
		
		var max_methods = jQuery('#max_methods').val();
		
		if(max_methods > numero) {
			var obj = new Object();
			obj.response = null;

			if(jQuery('#date_'+numero).length){
				var InfoX = getInfoX((numero+1));
				getDatas(evento, (numero+1), InfoX.data_x, InfoX.bateria_x, InfoX.turma_x, InfoX.posto_x);
			}	
			else
				completControlData((numero+1),obj);		
		}
	}
	
	function getDatas(evento, numero, data_x, bateria_x, turma_x, posto_x)
	{
	//	alert(' => getDatas');
		jQuery.post('/views/system/includes/signup.php', 
			{
				evento:evento,
				numero:numero,
				data_x:data_x,
				bateria_x:bateria_x,
				turma_x:turma_x,
				posto_x:posto_x,
				execute:'getDatas',
			},
			function(obj) {
			//	alert(obj.response + ' => getDatas');
				completControlData(numero, obj)
				completControlsNext(evento, numero);
				/*
				if(isValidDate(valor))
				{
					if(numero>1){	
						var data_x = jQuery('#date_inscricao_agenda'+prova+'_'+(numero-1)).val();
						var bateria_x = jQuery('#bateria_inscricao_agenda'+prova+'_'+(numero-1)).val();
						var turma_x = jQuery('#turma_inscricao_agenda'+prova+'_'+(numero-1)).val();
						var posto_x = jQuery('#posto_inscricao_agenda'+prova+'_'+(numero-1)).val();
					}
					
					if(jQuery('#turma_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
						jQuery('#turma_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</option>');	
					if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).prop('type') == 'fieldset')
						jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');	
			
					getBaterias(tlang, prova, etapa, clube, numero, data, data_x, bateria_x, turma_x, posto_x)
				}*/
			}

		)
	}	
	
	
	
	function getBaterias( evento, numero, data, data_x, bateria_x, turma_x, posto_x)
	{

		jQuery.post('/views/system/includes/signup.php', 
			{
				evento:evento,
				numero:numero,
				data:data,
				data_x:data_x,
				bateria_x:bateria_x,
				turma_x:turma_x,
				posto_x:posto_x,
				execute:'getBaterias',
			},
			function(obj) {
				
				completControlBateria(numero, obj);
				completControlsNext(evento, numero);
				if(obj.value == 1)
				{
					var  bateria = obj.value;
					var InfoX = getInfoX(numero);
			
					jQuery('#drums_'+numero).html(insertLoading());		
					getTurmas(evento, numero, data, bateria, InfoX.data_x, InfoX.bateria_x, InfoX.turma_x, InfoX.posto_x);
				}

			}
		)
	}	
	
	function getTurmas(evento, numero, data, bateria, data_x, bateria_x, turma_x, posto_x)
	{
		jQuery.post('/views/system/includes/signup.php', 
			{
				evento:evento,
				numero:numero,
				data:data,
				bateria:bateria,
				data_x:data_x,
				bateria_x:bateria_x,
				turma_x:turma_x,
				posto_x:posto_x,
				execute:'getTurmas',
			},
			function(obj) {
				completControlTurma(numero, obj);
				completControlsNext(evento, numero);
			}
		)
	}
	
	
	function getPostos(evento, numero, data, bateria, turma, data_x, bateria_x, turma_x, posto_x)
	{
		jQuery.post('/views/system/includes/signup.php', 
			{
				evento:evento,
				numero:numero,
				data:data,
				bateria:bateria,
				turma:turma,
				data_x:data_x,
				bateria_x:bateria_x,
				turma_x:turma_x,
				posto_x:posto_x,
				execute:'getPostos',
			},
			function(obj) {
				
				completControlPosto(numero, obj);
				completControlsNext(evento, numero);
			}
		)		
	}
	
	function isValidDate(dateString) 
	{
		var regEx = /^\d{4}-\d{2}-\d{2}$/;
		if(!dateString.match(regEx)) return false;  // Invalid format
		var d = new Date(dateString);
		var dNum = d.getTime();
		if(!dNum && dNum !== 0) return false; // NaN value, Invalid date
		return d.toISOString().slice(0,10) === dateString;
	}
	
});
	

