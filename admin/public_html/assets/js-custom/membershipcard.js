jQuery(document).ready(function(){

	jQuery('input:file').change(function (){   
		var file = this.files[0];   
		var controller = jQuery(this).data('controller');
		var reader = new FileReader();
		reader.onload = function(e) {
		   jQuery('.' + controller).attr('src', e.target.result).show();
		   jQuery('.upload-image-' + controller).hide();
		   jQuery('.skip-upload-' + controller).show(); 
		}; 
		reader.readAsDataURL(file);
	}); 

    jQuery('.skip-upload').click(function(){
		var controller = jQuery(this).data('controller');
	    jQuery('.' + controller).attr('src', jQuery('#imgSRC' + controller).val()).hide();
		jQuery('.upload-image-' + controller).show();
		jQuery('.skip-upload-' + controller).hide();  
		jQuery('input[data-controller="' + controller + '"]').val('');
	 }); 

	 jQuery('.color-picker').spectrum({
		type: "component",
		showAlpha: false,
		showButtons: false,
		move: function(color) {
			var controller = jQuery(this).data('controller')
			jQuery('.card-img-overlay-' + controller + ' span').css('color', color)
		}
	});

	jQuery(".select2").select2({
		selectOnClose: true,
		width: '100%',
	});
	
});
