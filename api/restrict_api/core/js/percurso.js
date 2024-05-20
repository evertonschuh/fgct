
var execute_function = null

jQuery(function () {

	function loadAAgenda (sel, prova){
		if(jQuery('#agenda_prova' + prova).val()!='')
		{
			var nrEtapa = parseInt(jQuery('#nr_etapa_prova' + prova).val());
			var additional = 0
			if ( jQuery('#additional_agenda').length ) 
				additional = parseInt(jQuery('#additional_agenda').val());
			var total = additional + nrEtapa;
			
			for(i=1;i<=total;i++)
			{
				if(i<=sel){
					jQuery('#date_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
					jQuery('#bateria_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
					jQuery('#turma_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
					
					jQuery('#date_inscricao_agenda'+prova+'_'+i).addClass('required');
					jQuery('#bateria_inscricao_agenda'+prova+'_'+i).addClass('required');
					jQuery('#turma_inscricao_agenda'+prova+'_'+i).addClass('required');
					jQuery('#posto_inscricao_agenda'+prova+'_'+i).addClass( 'validate-posto');
					
					jQuery('#agenda_' + i + '_container' + prova).slideDown();
					jQuery('#participacoes_' + i + '_' + prova).slideDown();
				}
				else{
					jQuery('#date_inscricao_agenda'+prova+'_'+i).attr( 'disabled', true);
					jQuery('#bateria_inscricao_agenda'+prova+'_'+i).attr( 'disabled', true);
					jQuery('#turma_inscricao_agenda'+prova+'_'+i).attr( 'disabled', true);
					
					jQuery('#date_inscricao_agenda'+prova+'_'+i).removeClass('required invalid');
					jQuery('#bateria_inscricao_agenda'+prova+'_'+i).removeClass('required invalid');
					jQuery('#turma_inscricao_agenda'+prova+'_'+i).removeClass('required invalid');
					jQuery('#posto_inscricao_agenda'+prova+'_'+i).removeClass( 'validate-posto');
					
					jQuery('#date_inscricao_agenda'+prova+'_'+i).val('');	
					jQuery('#bateria_inscricao_agenda'+prova+'_'+i).val('');	
					jQuery('#turma_inscricao_agenda'+prova+'_'+i).val('');	
					jQuery('#posto_inscricao_agenda'+prova+'_'+i).html('<p class="help-block">-Selecione a Turma -</p>');	
					
					jQuery('#agenda_' + i + '_container' + prova).slideUp();
					jQuery('#participacoes_' + i + '_' + prova).slideUp();
				}
			}
		}
	}

	execute_function = loadAAgenda;
	
	/*
    jQuery('.check-part').on('click', function(){

		var prova = jQuery(this).data('prova');
		
		if( (jQuery('#cbte100_'+prova).is(':checked') && jQuery('#cbte200_'+prova).is(':checked') ) || (jQuery('#liga100_'+prova).is(':checked') && jQuery('#liga200_'+prova).is(':checked'))){
			jQuery('#date_inscricao_agenda'+prova+'_3').attr( 'disabled', false);
			jQuery('#bateria_inscricao_agenda'+prova+'_3').attr( 'disabled', false);
			jQuery('#turma_inscricao_agenda'+prova+'_3').attr( 'disabled', false);
			jQuery('#posto_inscricao_agenda'+prova+'_3').addClass( 'validate-posto');
			jQuery('#date_inscricao_agenda'+prova+'_3').addClass('required');
			jQuery('#bateria_inscricao_agenda'+prova+'_3').addClass('required');
			jQuery('#turma_inscricao_agenda'+prova+'_3').addClass('required');
			jQuery('#agenda_3_container' + prova).slideDown();
		}
		else{
			jQuery('#date_inscricao_agenda'+prova+'_3').attr( 'disabled', true);
			jQuery('#bateria_inscricao_agenda'+prova+'_3').attr( 'disabled', true);
			jQuery('#turma_inscricao_agenda'+prova+'_3').attr( 'disabled', true);
			jQuery('#posto_inscricao_agenda'+prova+'_3').removeClass( 'validate-posto');
			jQuery('#date_inscricao_agenda'+prova+'_3').removeClass('required invalid');
			jQuery('#bateria_inscricao_agenda'+prova+'_3').removeClass('required invalid');
			jQuery('#turma_inscricao_agenda'+prova+'_3').removeClass('required invalid');
			jQuery('#date_inscricao_agenda'+prova+'_3').val('');	
			jQuery('#bateria_inscricao_agenda'+prova+'_3').val('');		
			jQuery('#turma_inscricao_agenda'+prova+'_3').val('');	
			jQuery('#posto_inscricao_agenda'+prova+'_3').html('<p class="help-block">-Selecione a Turma -</p>');	
			jQuery('#agenda_3_container' + prova).slideUp();
		}
	});
	*/
	
    jQuery('.button-inscri-ative span').on('click', function(){
        var sel = jQuery(this).data('value');
        var tog = jQuery(this).data('toggle');
		var prova = jQuery(this).data('provai');
		if(jQuery('#enabled_inscricao_etapa'+prova).val()==0)
		{
			jQuery('#'+tog).val(sel);
			if(sel>0) {

				var nrEtapa = parseInt(jQuery('#nr_etapa_prova' + prova).val());
				var additional = 0
				if ( jQuery('#additional_agenda').length ) 
					additional = parseInt(jQuery('#additional_agenda').val());
				var total = additional + nrEtapa;
				
				
				for(i=1;i<=total;i++)
				{
					if(i==1){
						jQuery('#date_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
						jQuery('#bateria_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
						jQuery('#turma_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
						jQuery('#posto_inscricao_agenda'+prova+'_'+i).addClass( 'validate-posto');
					}
					else if(jQuery('#participacoes_' + i + '_' + prova).is(":visible")){
						jQuery('#date_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
						jQuery('#bateria_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
						jQuery('#turma_inscricao_agenda'+prova+'_'+i).attr( 'disabled', false);
						jQuery('#posto_inscricao_agenda'+prova+'_'+i).addClass( 'validate-posto');
					}
					else{
						jQuery('#date_inscricao_agenda'+prova+'_'+i).attr( 'disabled', true);
						jQuery('#bateria_inscricao_agenda'+prova+'_'+i).attr( 'disabled', true);
						jQuery('#turma_inscricao_agenda'+prova+'_'+i).attr( 'disabled', true);
						
						jQuery('#date_inscricao_agenda'+prova+'_'+i).addClass( 'notExecute');
						jQuery('#bateria_inscricao_agenda'+prova+'_'+i).addClass( 'notExecute');
						jQuery('#turma_inscricao_agenda'+prova+'_'+i).addClass( 'notExecute');
						
						jQuery('#date_inscricao_agenda'+prova+'_'+i).removeClass('required invalid');
						jQuery('#bateria_inscricao_agenda'+prova+'_'+i).removeClass('required invalid');
						jQuery('#turma_inscricao_agenda'+prova+'_'+i).removeClass('required invalid');
						jQuery('#posto_inscricao_agenda'+prova+'_'+i).removeClass( 'validate-posto');
					}
				}
			}
		}
    });
	
	
	jQuery('.agenda-data').change(function() {
		
		var tlang = jQuery('#tag_language').val();
		var prova = jQuery(this).data('prova');
		var etapa = jQuery('#id_etapa').val();
		var clube = jQuery('#id_clube').val();
		var numero = jQuery(this).data('numero');
		
		var data = jQuery(this).val();
		
		var data_x = 0;
		var bateria_x = 0;
		var turma_x = 0;
		var posto_x = 0;
		
		if(numero>1){	
			data_x = jQuery('#date_inscricao_agenda'+prova+'_'+(numero-1)).val();
			bateria_x = jQuery('#bateria_inscricao_agenda'+prova+'_'+(numero-1)).val();
			turma_x = jQuery('#turma_inscricao_agenda'+prova+'_'+(numero-1)).val();
			posto_x = jQuery('#posto_inscricao_agenda'+prova+'_'+(numero-1)).val();
		}
		
		if(jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
			jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</option>');	
		if(jQuery('#turma_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
			jQuery('#turma_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</option>');	
		if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
			jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');		
		
		getBaterias(tlang, prova, etapa, clube, numero, data, data_x, bateria_x, turma_x, posto_x)	
	});		

	jQuery('.agenda-bateria').change(function() {
		
		var tlang = jQuery('#tag_language').val();
		var prova = jQuery(this).data('prova');
		var etapa = jQuery('#id_etapa').val();
		var clube = jQuery('#id_clube').val();
		var numero = jQuery(this).data('numero');
		var data = jQuery('#date_inscricao_agenda'+prova+'_'+numero).val();
		var bateria = jQuery(this).val();
		
		var data_x = 0;
		var bateria_x = 0;
		var turma_x = 0;
		var posto_x = 0;
		
		if(numero>1){	
			data_x = jQuery('#date_inscricao_agenda'+prova+'_'+(numero-1)).val();
			bateria_x = jQuery('#bateria_inscricao_agenda'+prova+'_'+(numero-1)).val();
			turma_x = jQuery('#turma_inscricao_agenda'+prova+'_'+(numero-1)).val();
			posto_x = jQuery('#posto_inscricao_agenda'+prova+'_'+(numero-1)).val();
		}
		
		if(jQuery('#turma_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
			jQuery('#turma_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</option>');	
		if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
			jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');	

		getTurmas(tlang, prova, etapa, clube, numero, data, bateria, data_x, bateria_x, turma_x, posto_x)	

	});		

	jQuery('.agenda-turma').change(function() {
		
		var tlang = jQuery('#tag_language').val();
		var prova = jQuery(this).data('prova');
		var etapa = jQuery('#id_etapa').val();
		var clube = jQuery('#id_clube').val();
		var numero = jQuery(this).data('numero');
		var data = jQuery('#date_inscricao_agenda'+prova+'_'+numero).val();
		var bateria = jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).val();
		var turma = jQuery(this).val();
		
		var data_x = 0;
		var bateria_x = 0;
		var turma_x = 0;
		var posto_x = 0;
		
		if(numero>1){	
			data_x = jQuery('#date_inscricao_agenda'+prova+'_'+(numero-1)).val();
			bateria_x = jQuery('#bateria_inscricao_agenda'+prova+'_'+(numero-1)).val();
			turma_x = jQuery('#turma_inscricao_agenda'+prova+'_'+(numero-1)).val();
			posto_x = jQuery('#posto_inscricao_agenda'+prova+'_'+(numero-1)).val();
		}
		
		if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
			jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');	
			
		 getPostos(tlang, prova, etapa, clube, numero, data, bateria, turma, data_x, bateria_x, turma_x, posto_x)	
				
	});	
	
	jQuery(document).on('change', '.agenda-posto', function(){
	
		var id = jQuery(this).attr('id');
		var prova = jQuery(this).data('prova');
		var numero = jQuery(this).data('numero');
		
		if( jQuery(this).is(':checked') ){
			
			jQuery('[data-prova="'+prova+'"]').each(function() { 
				if(jQuery(this).data('numero') == numero && jQuery(this).attr('id') != id && jQuery(this).is(':enabled'))
					jQuery('label[for="'+jQuery(this).attr('id')+'"]').html('Disponível');
			});			
			jQuery('label[for="'+id+'"]').html(jQuery('#name_user').val());
		}


	});	
	
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
	
	function completControlData(tlang, prova, numero, valor)
	{
		if(jQuery('#date_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
			if(valor != null)
				jQuery('#date_inscricao_agenda'+prova+'_'+numero).html(valor);
			else
				jQuery('#date_inscricao_agenda'+prova+'_'+numero).html('<option value="0">TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'+tlang+'DATA_SELECT</option>');
		else
			if(valor != null)
				jQuery('#date_inscricao_agenda'+prova+'_'+numero).val(valor);
			else
				jQuery('#date_inscricao_agenda'+prova+'_'+numero).val('');
		
		valor = null;
		completControlBateria(tlang, prova, numero, valor);
	}
	
	function completControlBateria(tlang, prova, numero, valor)
	{
		if(jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
			if(valor != null)
				jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).html(valor);
			else
				jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'+tlang+'BATERIA_SELECT')+'</option>');
		else
			if(valor != null)
				jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).val(valor);
			else
				jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).val('');
		
		valor = null;
		completControlTurma(tlang, prova, numero, valor);
	}
	
	function completControlTurma(tlang, prova, numero, valor)
	{		
		if(jQuery('#turma_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
			if(valor != null)
				jQuery('#turma_inscricao_agenda'+prova+'_'+numero).html(valor);
			else
				jQuery('#turma_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'+tlang+'TURMA_SELECT')+'</option>');	
		
		else
			if(valor != null)
				jQuery('#turma_inscricao_agenda'+prova+'_'+numero).val(valor);
			else
				jQuery('#turma_inscricao_agenda'+prova+'_'+numero).val('');
			
		valor = null;
		completControlPosto(tlang, prova, numero, valor);	
	}
	
	function completControlPosto(tlang, prova, numero, valor)
	{	
			
		if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
			if(valor != null)
				jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html(valor);
			else
				jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'+tlang+'POSTO_SELECT')+'</p>');		
		else
			if(valor != null)
				jQuery('#posto_inscricao_agenda'+prova+'_'+numero).val(valor);
			else
				jQuery('#posto_inscricao_agenda'+prova+'_'+numero).val('');
	}
	
	
	function completControlsNext(tlang, prova, etapa, clube, numero)
	{
		var nrEtapa = parseInt(jQuery('#nr_etapa_prova' + prova).val());
		var additional = 0
		if ( jQuery('#additional_agenda').length ) 
			additional = parseInt(jQuery('#additional_agenda').val());
		
		var total = additional + nrEtapa;
		
		if(total > 1 && total > numero) {

			var data_x = jQuery('#date_inscricao_agenda'+prova+'_'+numero).val();
			var bateria_x = jQuery('#bateria_inscricao_agenda'+prova+'_'+numero).val();
			var turma_x = jQuery('#turma_inscricao_agenda'+prova+'_'+numero).val();
			var posto_x = jQuery('#posto_inscricao_agenda'+prova+'_'+numero).val();

			numero=(numero+1);
			
			if(jQuery('#date_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
				jQuery('#date_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</option>');	
			if(jQuery('#turma_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
				jQuery('#turma_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</option>');	
			if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
				jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');	

			getDatas(tlang, prova, etapa, clube, numero, data_x, bateria_x, turma_x, posto_x);
		}
	}
	
	function getDatas(tlang, prova, etapa, clube, numero, data_x, bateria_x, turma_x, posto_x)
	{
		jQuery.post('/administrator/components/com_torneios/includes/dynamicselect.php', 
			{
				tag_language:tlang,
				numero_agenda_etapa:numero,
				prova_agenda_etapa:prova,
				local_agenda_etapa:clube,
				etapa_agenda_etapa:etapa,
				date_inscricao_agenda_x:data_x,
				bateria_inscricao_agenda_x:bateria_x,
				turma_inscricao_agenda_x:turma_x,
				posto_inscricao_agenda_x:posto_x,
			},
			function(valor) {

				completControlData(tlang, prova, numero, valor)
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
					if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
						jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');	
			
					getBaterias(tlang, prova, etapa, clube, numero, data, data_x, bateria_x, turma_x, posto_x)
				}
			}

		)
	}	
	
	
	
	function getBaterias(tlang, prova, etapa, clube, numero, data, data_x, bateria_x, turma_x, posto_x)
	{

		jQuery.post('/administrator/components/com_torneios/includes/dynamicselect.php', 
			{
				tag_language:tlang,
				prova_agenda_etapa:prova,
				etapa_agenda_etapa:etapa,
				local_agenda_etapa:clube,
				numero_agenda_etapa:numero,
				date_inscricao_agenda:data,
				date_inscricao_agenda_x:data_x,
				bateria_inscricao_agenda_x:bateria_x,
				turma_inscricao_agenda_x:turma_x,
				posto_inscricao_agenda_x:posto_x,
			},
			function(valor) {
				completControlBateria(tlang, prova, numero, valor)
				if(jQuery.isNumeric(valor))
				{
					
					var  bateria = valor;
					var  turma_x = 0;
					
					if(numero>1){	
						var turma_x = jQuery('#turma_inscricao_agenda'+prova+'_'+(numero-1)).val();
					}
					
					if(jQuery('#turma_inscricao_agenda'+prova+'_'+numero).prop('type')=='select-one')
						jQuery('#turma_inscricao_agenda'+prova+'_'+numero).html('<option value="0">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</option>');	
					if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
						jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');	
			
					getTurmas(tlang, prova, etapa, clube, numero, data, bateria, data_x, bateria_x, turma_x)
				}

				completControlsNext(tlang, prova, etapa, clube, numero);
			}
		)
	}	
	
	function getTurmas(tlang, prova, etapa, clube, numero, data, bateria, data_x, bateria_x, turma_x)
	{
		
		jQuery.post('/administrator/components/com_torneios/includes/dynamicselect.php', 
			{
				tag_language:tlang,
				prova_agenda_etapa:prova,
				etapa_agenda_etapa:etapa,
				local_agenda_etapa:clube,
				numero_agenda_etapa:numero,
				date_inscricao_agenda:data,
				bateria_inscricao_agenda:bateria,
				date_inscricao_agenda_x:data_x,
				bateria_inscricao_agenda_x:bateria_x,
				turma_inscricao_agenda_x:turma_x,
			},
			function(valor) {
				completControlTurma(tlang, prova, numero, valor);

				if(jQuery.isNumeric(valor))
				{
					var turma = valor;
					var data_x = 0;
					var bateria_x = 0;
					var turma_x = 0;
					var posto_x = 0;
					
					if(numero>1){	
						var data_x = jQuery('#date_inscricao_agenda'+prova+'_'+(numero-1)).val();
						var bateria_x = jQuery('#bateria_inscricao_agenda'+prova+'_'+(numero-1)).val();
						var turma_x = jQuery('#turma_inscricao_agenda'+prova+'_'+(numero-1)).val();
						var posto_x = jQuery('#posto_inscricao_agenda'+prova+'_'+(numero-1)).val();
					}
		
					if(jQuery('#posto_inscricao_agenda'+prova+'_'+numero).is('fieldset'))
						jQuery('#posto_inscricao_agenda'+prova+'_'+numero).html('<p class="help-block">'+Joomla.JText._('TORNEIOS_SCRIPT_SELET_LOADING')+'</p>');	
			
		 			getPostos(tlang, prova, etapa, clube, numero, data, bateria, turma, data_x, bateria_x, turma_x, posto_x)	
				}
						
				completControlsNext(tlang, prova, etapa, clube, numero);			

			}
		)
	}
	
	
	function getPostos(tlang, prova, etapa, clube, numero, data, bateria, turma, data_x, bateria_x, turma_x, posto_x)
	{
		jQuery.post('/administrator/components/com_torneios/includes/dynamicselect.php', 
			{
				tag_language:tlang,
				prova_agenda_etapa:prova,
				etapa_agenda_etapa:etapa,
				local_agenda_etapa:clube,
				numero_agenda_etapa:numero,
				date_inscricao_agenda:data,
				bateria_inscricao_agenda:bateria,
				turma_inscricao_agenda:turma,
				date_inscricao_agenda_x:data_x,
				bateria_inscricao_agenda_x:turma_x,
				turma_inscricao_agenda_x:turma_x,
				posto_inscricao_agenda_x:posto_x,
			},
			function(valor) {
				
				completControlPosto(tlang, prova, numero, valor);
								
				completControlsNext(tlang, prova, etapa, clube, numero);
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
	
	
	
	

