jQuery(document).ready(function(){

  var origin = '*';

  jQuery(".fancybox").fancybox({
        openEffect: "none",
        closeEffect: "none"
    });
	
	jQuery(".new_folder_btn").click(function() {
		
		if(!jQuery('#new_folder').val()){
			jQuery('#new_folder').next('p').remove();
			jQuery('#new_folder').parent('div').parent('div').addClass('has-error');
			jQuery('#new_folder').parent('div').after( '<p class="error">Este campo é obrigatório</p>' );
			return false;
		}
		else{
			jQuery('input[name="task"]').val('newfolder')
			jQuery('form[name="adminForm"]' ).submit();
		}
	});	
    

  jQuery(document).on('click', 'input[name="image[]"]', function(){
      thisRadio = jQuery(this);
      if (thisRadio.hasClass("imChecked")) {
          thisRadio.removeClass("imChecked");
          thisRadio.prop('checked', false);
          jQuery('.btn-file').hide();
      } else { 
          thisRadio.prop('checked', true);
          thisRadio.addClass("imChecked");
          jQuery('.btn-file').show();
          jQuery('#image-gallery-image').attr('src','/midias/assets/img/store/' + jQuery(this).val()); 
          
          window.parent.postMessage({
            mceAction: 'execCommand',
            cmd: 'imgmanagerCommand',
            value: '/midias/assets/img/store/' + jQuery(this).val()
          }, origin);
      };
  })


  jQuery('.image-preview-clear').click(function(){
    jQuery('.image-preview-filename').val("");
    jQuery('.image-preview-clear').hide();
    jQuery('.image-upload-file').hide();
    jQuery('.image-preview-input input:file').val("");
    jQuery('.image-preview-input').show();
  }); 

  jQuery(".image-preview-input input:file").change(function (){     
    jQuery('.image-preview-input').hide();
    jQuery(".image-preview-clear").show();
    jQuery('.image-upload-file').show();
  });  
 
});

