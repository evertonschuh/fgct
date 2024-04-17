jQuery(document).ready(function(){

	jQuery('#openUsers').click(function(){
		jQuery('#includeItem').attr("src","index.php?view=documento&layout=modalm&cid[]=" + $('#cid').val() + "&tmpl=modal");
		jQuery('#internalModal').modal({show:true});
	});
	
	jQuery("#includeItem").load(function() {
		jQuery(this).height( $(this).contents().find("body").height()+ 30);
	});
	
	jQuery("#internalModal").on('hidden.bs.modal', function ()  {
        window.location.reload();
    });
	
	jQuery('[name="public_documento"]').on('click', function() {	
		if(jQuery(this).val()==2) 
			jQuery('.block-add-users').slideDown();
		else
			jQuery('.block-add-users').slideUp();
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
