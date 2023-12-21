jQuery(document).ready(function(){
	jQuery('.select2').select2({
		selectOnClose: true,
		width: '100%',
	});

	jQuery('#id_corredor_categoria').change(function(){
		jQuery('#mes_treino').html('<option value="0">'+Joomla.JText._('EASISTEMAS_SCRIPT_SELET_LOADING')+'</option>');	
		jQuery.post('/dynamic/select.php', 
			{id_corredor_categoria:jQuery(this).val()},
			function(valor){
				jQuery('#mes_treino').html(valor);
			}
		)
	})
});