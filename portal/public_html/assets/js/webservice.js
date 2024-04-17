
jQuery.fn.extend({ChatSocket: function(opciones) {
		

		var ChatSocket=this;
		var idChat=jQuery(ChatSocket).attr('id');
		defaults = {
			ws,
			Room:"reserve-id_event",
			pass:"1234",
			etapa:"",
			clube:"",
			user:"",
			name:"Anónimo"
		}
		
		var var_json;
		var refreshIntervalId;
		var opctiones = jQuery.extend({}, defaults, opciones);
		var ws; 

		var Room=opciones.Room;
		var pass=opciones.pass;
		var etapa=opciones.etapa;
		var clube=opciones.clube;
		//var prova=opciones.prova;
		//var data=opciones.data;
		//var turma=opciones.turma; 
		//var posto=opciones.posto;
		var user=opciones.user;
		var name=opciones.name;

    	
		function StartConection()
		{

			LoadAgendamentos();
			conex='{"setID":"'+Room+'","passwd":"'+pass+'"}';

			//ws= new WebSocket("wss://ws.fgct.com.br");
			ws= new WebSocket("wss://ws.easistemas.com.br");
			ws.onopen= function(){ ws.send(conex); }
			ws.onmessage= function(Mensajes)
			{
				var MensajesObtenidos=Mensajes.data;
				var obj = jQuery.parseJSON(MensajesObtenidos);
				AgregarItem(obj);
			}
			ws.onclose= function(){
				OfflineMensaje();
				ws.close();
				ws=false;
			}
						
		}
		StartConection();
		   
		function LoadFunction()
		{
			jQuery(document).on('change', '.agenda-posto', function(){
				EnviarMensaje(jQuery(this));
			});
			
			jQuery(document).on('change', '.agenda-turma', function(){
				TrocaPostoMensaje(jQuery(this));
			});				
			
			jQuery(document).on('change', '.agenda-bateria', function(){
				TrocaPostoMensaje(jQuery(this));
			});		
				
			jQuery(document).on('change', '.agenda-data', function(){
				TrocaPostoMensaje(jQuery(this));
			});	
			

		}
		LoadFunction()
      
        function EnviarMensaje(Obj)
		{
			var id = Obj.data('id');
			var event = jQuery('input[name="id_event"]').val();
			var number = Obj.data('numero');


			var date = 0;
			var drums = 0;
			var squad = 0;


			if(jQuery('#date_'+number).length)
				date = jQuery('#date_'+number).find('input[name="date['+number+']"]:checked').val();
			else
				date = jQuery('input[name="date['+number+']"]').val();

			if(jQuery('#drums_'+number).length)
				drums = jQuery('#drums_'+number).find('input[name="drums['+number+']"]:checked').val();
			else
				drums = jQuery('input[name="drums['+number+']"]').val();

			if(jQuery('#squad_'+number).length)
				squad = jQuery('#squad_'+number).find('input[name="squad['+number+']"]:checked').val();
			else
				squad = jQuery('input[name="squad['+number+']"]').val();
			
			//if(jQuery('#position_'+number).length)
			//	Info.posto = jQuery('#position_'+number).find('input[name="position['+number+']"]:checked').val();
			//else
			//	Info.posto = jQuery('input[name="position['+number+']"]').val();				

			var position = Obj.val();	
			
			if(!ws)
			{
				LoadFunction()
			}
			var_json = '{"to":"'+Room+'","tipo":"1","name":"'+name+'","user":"'+user+'","event":"'+event+'","id":"'+id+'","number":"'+number+'","date":"'+date+'","drums":"'+drums+'","squad":"'+squad+'","position":"'+position+'"}';
			jQuery.post('/views/system/includes/webservice.php', {var_json_post:var_json} );
			ws.send(var_json);

			//else
			//	alert('Você deve se conectar para enviar mensagens!');
        }
		/*
		data_x = jQuery('#date_'+(numero-1)).find('input[name="date['+(numero-1)+']"]:checked').val();
		bateria_x = jQuery('#drums_'+(numero-1)).find('input[name="drums['+(numero-1)+']"]:checked').val();
		turma_x = jQuery('#squad_'+(numero-1)).find('input[name="squad['+(numero-1)+']"]:checked').val();
		posto_x = jQuery('#position_'+(numero-1)).find('input[name="position['+(numero-1)+']"]:checked').val();*/

	  
        function TrocaPostoMensaje(Obj)
		{
			var event = jQuery('input[name="id_event"]').val();
			var number = Obj.data('numero');
			if(!ws)
			{
				LoadFunction()
			}

			var_json = '{"to":"'+Room+'","tipo":"0","name":"'+name+'","user":"'+user+'","event":"'+event+'","number":"'+number+'"}';
			jQuery.post('/views/system/includes/webservice.php',
						{var_json_remove:var_json},
						function(data){
							ws.send(data);
						});
			
			//else
			//	alert('Você deve se conectar para enviar mensagens!');
        }
		
        function OfflineMensaje()
		{
			/*
			$("#online").removeClass('active');
			$("#offline").addClass('active');
			$( ".chatpluginchat" ).html( '<div class="item disconect"><a>Desconectado</a><p>Você está desconectado da sala de bate papo</p></div>' ).fadeIn("slow");
			clearInterval(refreshIntervalId);
			*/
		}
		
        function LoadAgendamentos()
		{
			
			//$( ".disconect" ).remove();
			//prependToSlow = false;
			/*
			var_json = '{"to":"'+Room+'","name":"'+name+'","user":"'+user+'","prova":"'+prova+'","etapa":"'+etapa+'","id":"'+id+'","data":"'+date+'","turma":"'+turma+'","posto":"'+posto+'" }';
			jQuery.post('/views/system/includes/webservice.php', 
						{var_json_get:var_json}, 
						function(data) {
  
							jQuery.each(obj, function(i, item) {
								AgregarItem( item );
							});
							//prependToSlow = true;
  
  
				}, "json");*/
			//jQuery.getJSON("views/system/includes/chatdirectp.php?room_messages_chat="+Room+"&user_id="+UserId+"&local_id="+LocalId, function(obj){
			//	$.each(obj, function(i, item) {
			//		AgregarItem( item );
			//	});
			//	prependToSlow = true;
			//});
			
		}
		/*
        function UsuarioOnline()
		{
        	ws.send('{"to":"'+Room+'","name":"'+UserName+'","image_pf":"'+UserImage+'"}');
        }
		*/
        function AgregarItem(Obj){
            
			
			
			//jQuery('[data-prova="'+Obj.prova+'"]').each(function() { 
			//	if(jQuery(this).data('numero') == Obj.numero && jQuery(this).attr('id') != Obj.id && jQuery(this).is(':enabled'))
			//		jQuery('label[for="'+Obj.attr('id')+'"]').html('Disponível');
			//});			
			//jQuery('label[for="'+id+'"]').html(jQuery('#name_user').val());

			//alert(Obj.id);
			//alert(Obj.user);
			//alert(Obj.event);
			//alert(Obj.date);
			//alert(Obj.drums);
			//alert(Obj.squad);
			//alert(Obj.position);
            if (typeof Obj.name !== 'undefined')
			{
				
				//var prova = jQuery('input[data-id="'+Obj.id+'"]').parent().parent().parent().parent().parent().find('.agenda-data').data('prova');
				//var data = jQuery('input[data-id="'+Obj.id+'"]').parent().parent().parent().parent().parent().find('.agenda-data').val();
				//var turma = jQuery('input[data-id="'+Obj.id+'"]').parent().parent().parent().parent().parent().find('.agenda-turma').val();
				//var bateria = jQuery('input[data-id="'+Obj.id+'"]').parent().parent().parent().parent().parent().find('.agenda-bateria').val();

				//var event = jQuery('input[name="id_event"]').val();
				//var number = Obj.data('numero');
				//var date = jQuery('#date_'+number).find('input[name="date['+number+']"]:checked').val();
				//var drums = jQuery('#drums_'+number).find('input[name="drums['+number+']"]:checked').val()
				//var squad = jQuery('#squad_'+number).find('input[name="squad['+number+']"]:checked').val()	
				//var position = jQuery('#position_'+number).find('input[name="squad['+number+']"]:checked').val()		
				
				if(Obj.tipo == '1' && Obj.user != user){
					jQuery('input[data-id="'+Obj.id+'"]').closest('.d-flex').find('input').each(function() { 
						if(jQuery(this).attr('data-reserved') == Obj.user && jQuery(this).attr('data-id') != Obj.id){
							jQuery('label[for="'+jQuery(this).attr('id')+'"] span.name').html('Disponível');
							jQuery(this).attr('data-reserved',null).attr('disabled', false).removeClass('reserved',true);
						}
					});	

					jQuery('input[data-id="'+Obj.id+'"]').next('span').find('span.name').html(Obj.name);
					jQuery('input[data-id="'+Obj.id+'"]').addClass('reserved',true);
					jQuery('input[data-id="'+Obj.id+'"]').attr('disabled',true);
					jQuery('input[data-id="'+Obj.id+'"]').attr('data-reserved',Obj.user);
				}
				else {
					
					jQuery.each( Obj.ids, function(i, list) {
						//if(Object.keys(list).length>0) {
						
						jQuery.each( list, function(y, val2) {
							//alert(val2);
							jQuery('input[data-id="'+val2+'"]').closest('.d-flex').find('input').each(function() { 
								if(jQuery(this).attr('data-reserved') == Obj.user && jQuery(this).attr('data-id') == val2){
									jQuery('label[for="'+jQuery(this).attr('id')+'"] span.name').html('Disponível');
									jQuery(this).attr('data-reserved',null).attr('disabled', false).removeClass('reserved',true);
								}
							});	
						});
						//}
					});
				}



				//$("ul[data-slide='" + current +"']");
				//var prova = jQuery('label[for="'+Obj.id+'"]').parent().parent().parent().parent().parent().find('.agenda-data').data('prova');
				//var data = jQuery('label[for="'+Obj.id+'"]').parent().parent().parent().parent().parent().find('.agenda-data').val();
				//var turma = jQuery('label[for="'+Obj.id+'"]').parent().parent().parent().parent().parent().find('.agenda-turma').val();
				
				/*
				if(Obj.tipo == '1' && Obj.user != user && Obj.squad == squad && Obj.bateria == bateria && Obj.data == data && Obj.prova == prova){
					jQuery('input[data-id="'+Obj.id+'"]').parent().parent().find('[data-prova="'+Obj.prova+'"]').each(function() { 
						if(jQuery(this).data('reserved') == Obj.user && jQuery(this).data('id') != Obj.id){
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').html('Disponível');
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().removeClass('funkyradio-reserved').addClass('funkyradio-success');
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().find('.agenda-posto').data('reserved',null);
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().find('.agenda-posto').attr('disabled', false);
						}
					});		
					jQuery('input[data-id="'+Obj.id+'"]').attr('disabled', true);
					jQuery('label[for="'+id+'"]').html(Obj.name);
					jQuery('label[for="'+id+'"]').parent().removeClass('funkyradio-success').addClass('funkyradio-reserved')
					jQuery('input[data-id="'+Obj.id+'"]').data('reserved', Obj.user);
				}
				else if(Obj.tipo === '0') {
					jQuery.each( Obj.ids, function(i, list) {
						//if(Object.keys(list).length>0) {
							alert(Obj.name);
						jQuery.each( list, function(y, val2) {
							jQuery('input[data-id="'+val2+'"]').parent().parent().find('[data-prova="'+Obj.prova+'"]').each(function() { 
								if(jQuery(this).data('reserved') == Obj.user && jQuery(this).data('id') == val2){
									jQuery('label[for="'+jQuery(this).attr('id')+'"]').html('Disponível');
									jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().removeClass('funkyradio-reserved').addClass('funkyradio-success');
									jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().find('.agenda-posto').data('reserved',null);
									jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().find('.agenda-posto').attr('disabled', false);
								}
							});	
						});
						//}
					});
					*/
					//for (var i = 0; i < Obj.length; i++) { 
    				//	alert(Obj[i].id);
					//}
					
				//jQuery.each( Obj, function(i, val) {
 					 //$( "#" + i ).append( document.createTextNode( " - " + val ) );
				//	 alert(Obj[i]);
				//});
					/**/
					/*
					jQuery('input[data-id="'+Obj.id+'"]').parent().parent().find('[data-prova="'+Obj.prova+'"]').each(function() { 
						if(jQuery(this).data('reserved') == Obj.user ){
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').html('Disponível');
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().removeClass('funkyradio-reserved').addClass('funkyradio-success');
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().find('.agenda-posto').data('reserved',null);
							jQuery('label[for="'+jQuery(this).attr('id')+'"]').parent().find('.agenda-posto').attr('disabled', false);
						}
					});	
					*/
				///}
			}

        }
		
		function getOnline() {
			//refreshIntervalId = setInterval(UsuarioOnline, 3000);;
		}
	}
});