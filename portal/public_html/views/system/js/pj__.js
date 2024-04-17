jQuery(document).ready(function () {

	jQuery(".date").datetimepicker({
		useCurrent: false,
		locale: 'pt-br',
		format: 'L'
	});

	jQuery(".select2").select2({
		selectOnClose: true,
		width: '100%'
	});

	jQuery(document).on('change', '.image-preview-input input:file', function () {
		var file = this.files[0];
		jQuery(".image-preview-filename").val(file.name);
	});

	jQuery('#cep_pj').mask('00000-000');
	jQuery('#cnpj_pj').mask('00.000.000/0000-00');

	var SPMaskBehavior = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
		spOptions = {
			onKeyPress: function (val, e, field, options) {
				field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};

	jQuery('input[type=tel]').mask(SPMaskBehavior, spOptions);
	jQuery('.data').mask('99/99/9999');

	jQuery('#cnpj_pj').on('blur', function () {
		if (document.formvalidator.validate('cnpj_pj') == true) {
			jQuery.post('views/system/includes/checkregister.php',
				{
					cnpj_pj: jQuery(this).val(),
					id_user: jQuery('input[name="id_user"]').val()
				},
				function (resp) {
					if (resp) {
						jQuery('#cnpj_pj').parent('div').addClass('has-error');
						jQuery('#cnpj_pj').next('label').remove();
						jQuery('#cnpj_pj').after('<label class="control-label">' + Joomla.JText._('INTRANET_SCRIPT_VALIDATE_ERROR_INPUT_CNPJ_REPEAT') + '</label>');
					}
					else {
						jQuery('#cnpj_pj').parent('div').removeClass('has-error');
						jQuery('#cnpj_pj').next('label').remove();
					}
				}
			)
		}
		else {
			jQuery('#cnpj_pj').parent('div').removeClass('has-error');
			jQuery('#cnpj_pj').next().next('label').remove();
		}
	});

	jQuery('#id_estado').change(function () {
		jQuery('#id_cidade').html('<option value="0">' + Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING') + '</option>');
		jQuery.post('views/system/includes/dynamicselect.php',
			{ id_estado: jQuery(this).val() },
			function (valor) {
				jQuery('#id_cidade').html(valor);
			}
		)
	});
	jQuery('#add_id_estado').change(function () {
		jQuery('#add_id_cidade').html('<option value="0">' + Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING') + '</option>');
		jQuery.post('views/system/includes/dynamicselect.php',
			{ id_estado: jQuery(this).val() },
			function (valor) {
				jQuery('#add_id_cidade').html(valor);
			}
		)
	});
	jQuery('#estado_buscacep').change(function () {
		jQuery('#cidade_buscacep').html('<option value="0">' + Joomla.JText._('INTRANET_SCRIPT_SELET_LOADING') + '</option>');
		jQuery.post('views/system/includes/dynamicselect.php',
			{ estado_buscacep: jQuery(this).val() },
			function (valor) {
				jQuery('#cidade_buscacep').html(valor);
			}
		)
	});

	jQuery('#buscacep').on('hide.bs.modal', function () {
		resetModaBuscaCep();
	});



	$('#submithsearch').on('click', function () {

		if ($("#submithsearch").hasClass('new-search')) {
			resetModaBuscaCep();
		}
		else if ($("#submithsearch").hasClass('validate')) {
			if (!validateform($(this).parents('.form-validate')))
				return false;
			else {
				$('#loading-buscacep').append(spinner.el);
				$.post('views/system/includes/buscaendereco.php',
					{
						estado_buscacep: $('#estado_buscacep').val(),
						cidade_buscacep: $('#cidade_buscacep').val(),
						logradouro_buscacep: $('#logradouro_buscacep').val()
					},
					function (valor) {
						$('#cepresultmain').html(valor);
						$('#cepbuscar').hide();
						$('#cepresult').show();
						$('#submithsearch').html('<span class="glyphicon glyphicon-search"></span>&nbsp;' + Joomla.JText._('INEJE_GLOBAL_BUTTON_NEW_SEARCH'));
						$('#submithsearch').removeClass('validate');
						$('#submithsearch').addClass('new-search');
						$('#loading-buscacep').children(spinner.el).remove();
					}
				)
			}
		}
	});

	$('#cep_pj').blur(function () {
		$('#loading').append(spinner.el);
		$.post('views/system/includes/buscaendereco.php',
			{ cep_endereco: $(this).val() },
			function (valor) {
				if (valor) {
					var obj = $.parseJSON(valor)
					$('#id_cidade').html(valor);
					$('#id_cidade').next('label').remove();
					$('#id_cidade').parent('div').removeClass('has-error');


					$('#logradouro_pj').val(obj.logradouro);
					$('#logradouro_pj').next('label').remove();
					$('#logradouro_pj').parent('div').removeClass('has-error');

					$('#bairro_pj').val(obj.bairro);
					$('#bairro_pj').next('label').remove();
					$('#bairro_pj').parent('div').removeClass('has-error');

					$('#id_estado').val(obj.id_estado);
					$('#id_estado').next('label').remove();
					$('#id_estado').parent('div').removeClass('has-error');


					$('#id_cidade').html('<option value="0">' + Joomla.JText._('INEJE_SCRIPT_SELET_LOADING') + '</option>');
					$.post('views/system/includes/dynamicselect.php',
						{ id_estado: obj.id_estado },
						function (valor) {
							$('#id_cidade').html(valor);
							$('#id_cidade').val(obj.id_cidade);
							$('#id_cidade').next('label').remove();
							$('#id_cidade').parent('div').removeClass('has-error');
							$('#loading').children(spinner.el).remove();
						}
					)
				}
				else {
					$('#loading').children(spinner.el).remove();
				}
			}
		)
	});

});


function inserirendereco(obj) {

	var id_estado = $(obj).find("input[name='id_estado_respbuscacep[]']").val();
	var id_cidade = $(obj).find("input[name='id_cidade_respbuscacep[]']").val();

	$('#cep_pj').val($(obj).find("input[name='cep_respbuscacep[]']").val());
	$('#cep_pj').next('label').remove();
	$('#cep_pj').parent('div').removeClass('has-error');


	$('#logradouro_pj').val($(obj).find("input[name='logradouro_respbuscacep[]']").val());
	$('#logradouro_pj').next('label').remove();
	$('#logradouro_pj').parent('div').removeClass('has-error');

	$('#bairro_pj').val($(obj).find("input[name='bairro_respbuscacep[]']").val());
	$('#bairro_pj').next('label').remove();
	$('#bairro_pj').parent('div').removeClass('has-error');

	$('#id_estado').val(id_estado);
	$('#id_estado').next('label').remove();
	$('#id_estado').parent('div').removeClass('has-error');

	$('#id_cidade').html('<option value="0">' + Joomla.JText._('INEJE_SCRIPT_SELET_LOADING') + '</option>');

	$.post('views/system/includes/dynamicselect.php',
		{ id_estado: id_estado },
		function (valor) {
			$('#id_cidade').html(valor);
			$('#id_cidade').val(id_cidade);
			$('#id_cidade').next('label').remove();
			$('#id_cidade').parent('div').removeClass('has-error');
			$('#buscacep').modal('hide');
			resetModaBuscaCep()
		}
	)
};

function resetModaBuscaCep(img) {
	$('#cepresult').hide();
	$('#cepresultmain').html('');
	$('#estado_buscacep').val('');
	$('#logradouro_buscacep').val('');
	$('#cidade_buscacep').html('<option disabled="" selected="" class="default" value="">' + Joomla.JText._('INEJE_GLOBAL_CIDADE_SELECT') + '</option>');
	$('#submithsearch').html('<span class="glyphicon glyphicon-search"></span>&nbsp;' + Joomla.JText._('INEJE_GLOBAL_BUTTON_SEARCH'));
	$('#submithsearch').removeClass('new-search');
	$('#submithsearch').addClass('validate');
	$('#cepbuscar').show();
}

function validateform(form) {
	var error = false;
	$(form).find('select').each(function () {
		if ($(this).prop('required')) {
			if (!$(this).val()) {
				$(this).parent('div').addClass('has-error');
				$(this).next('label').remove();
				$(this).after('<label class="control-label">' + Joomla.JText._('INEJE_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED') + '</label>');
				error = true;
			}
		}
	});

	$(form).find('input').each(function () {
		if ($(this).prop('required')) {
			if (!$(this).val()) {
				$(this).next('label').remove();
				$(this).after('<label class="control-label">' + Joomla.JText._('INEJE_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED') + '</label>');
				$(this).parent('div').addClass('has-error');
				error = true;
			}
		}
	});

	if (error)
		return false;
	else
		return true;
}