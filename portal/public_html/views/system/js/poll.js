$(document).ready(function(){

	jQuery('.select2').select2({
		selectOnClose: true,
		width: '100%'
	});
    
    jQuery(".select2m").select2({
        width: '100%',
        multiple: true,
        placeholder: "  - Selecione ou escreva -",
        tags: true
    });
/*
    jQuery(".select2mp").select2({
        width: '100%',
        multiple: true,
        placeholder: "  - Selecione ou escreva -"
    });
*/

	
});