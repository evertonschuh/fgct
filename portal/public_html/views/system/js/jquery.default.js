jQuery(document).ready(function(){
    if (typeof jQuery().datetimepicker === "function") {

		jQuery(".date").datetimepicker({
			useCurrent: false ,
			locale: 'pt-br',
			format: 'L'
		});	
        
    }

    if (typeof jQuery().select2 === "function") {
		jQuery(".select2").select2({
			selectOnClose: true,
			width: '100%'
		});
	}

    if (typeof jQuery().mask === "function") {

		jQuery('.cep').mask('00000-000');	
		jQuery('.validate-cpf').mask('000.000.000-00');
		jQuery('.validate-cnpj').mask('00.000.000/0000-00');

		var SPMaskBehavior = function (val) {
			return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
			onKeyPress: function(val, e, field, options) {
				field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};
	
		jQuery('input[type=tel]').mask(SPMaskBehavior, spOptions);
	}
	

	jQuery('.modal').on('hide.bs.modal', function () {		
		jQuery(this).find('input').attr('disabled', true);
    });	

	jQuery('.modal').on('show.bs.modal', function () {		
		jQuery(this).find('input').attr('disabled', false);
    });	
	
});

function SomenteNumero(e)
{
	var tecla=(window.event)?event.keyCode:e.which;
	if((tecla > 47 && tecla < 58)) return true;
	else
	{
		if (tecla != 8) return false;
	else return true;
	}
}