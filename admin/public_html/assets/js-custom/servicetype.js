jQuery(document).ready(function(){


	jQuery('.select2').select2({
		selectOnClose: true,
		width: '100%',
	});
	
	jQuery('[name="automatic_service_type"]').on('click', function() {	
		if(jQuery(this).is(':checked')){
			jQuery('#id_documento').attr('disabled', false);
			jQuery('.control-document').slideDown();
		}
		else{
			jQuery('#id_documento').attr('disabled', true);
			jQuery('.control-document').slideUp();
		}
			
	});

	/*
	jQuery(".select2").select2({
		selectOnClose: true,
		width: '100%'
	});

	jQuery(".select2").select2({
		ajax: {
		  url: 'https://api.github.com/search/repositories',
		  dataType: 'json'
		  // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
		}
		selectOnClose: true,
		width: '100%'
	  });

*/


	
});
