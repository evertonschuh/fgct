$(document).ready(function(){

	jQuery('.select2').select2({
		selectOnClose: true,
		width: '100%'
	});
    
    jQuery(".select2m").select2({
        width: '100%',
        multiple: true,
        placeholder: '  Selecione ou Escreva',
       // tags: true,

    });

    jQuery('#resetFilters').click(function(){

        jQuery('.select2m').val('').trigger('change');
        jQuery('.filter-input').val('');
        jQuery('input[name="genre[]"]').attr('checked',false);
        Joomla.submitform('apply_filter');
    });

    jQuery('.modal').on('show.bs.modal', function(){
        jQuery(this).find('input').attr('disabled', false);
    });
    
    jQuery('.modal').on('hidden.bs.modal', function(){
        jQuery(this).find('input').attr('disabled', true);
    });





    /*
   // "  - Selecione ou escreva -",
    jQuery(".select2m").on('change.select2', function (e) {
        if(jQuery(this).val()===null)
            Joomla.submitform('remove_' + jQuery(this).data('name'));
        else
            this.form.submit();
    });
*/
/*
    jQuery(".select2mp").select2({
        width: '100%',
        multiple: true,
        placeholder: "  - Selecione ou escreva -"
    });
*/

	
});