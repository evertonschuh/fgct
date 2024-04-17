/**
1
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
var errorText;
var tela=0;

Object.append(Browser.Features, {
	inputemail: (function() {
		var i = document.createElement("input");
		i.setAttribute("type", "email");
		return i.type !== "text";
	})()
});

/**
 * Unobtrusive Form Validation library
 *
 * Inspired by: Chris Campbell <www.particletree.com>
 *
 * @package		Joomla.Framework
 * @subpackage	Forms
 * @since		1.5
 */
var JFormValidator = new Class({
	initialize: function()
	{
		// Initialize variables
		this.handlers	= Object();
		this.custom		= Object();

		// Default handlers
		this.setHandler('username',
			function (value) {
				regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
				return !regex.test(value);
			}
		);

		this.setHandler('password',
			function (value) {
				//regex=/^\S[\S ]{2,98}\S$/;
				regex=/^(?=.*[a-zA-Z0-9]).{6,}$/;
				return regex.test(value);
			}
		);

		this.setHandler('numeric',
			function (value) {
				regex=/^(\d|-)?(\d|,)*\.?\d*$/;
				return regex.test(value);
			}
		);

		this.setHandler('email',
			function (value) {
				regex=/^[a-zA-Z0-9._-]+(\+[a-zA-Z0-9._-]+)*@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
				return regex.test(value);
			}
		);
		
		this.setHandler('equalspass', 
			function (value) {	
				if(document.id("password").value == value )
					return true
				else
					return false;	
   			}
		);
		
		this.setHandler('cpf', 
			function (value) {
				if (value!='')
					if (value.length == 14)
						if (validateCPF(value))
							return true;			
						else
							return false;
					else
						return false;	
				else
					return false;	
			}
		);

		this.setHandler('cnpj', 
			function (value) {
				if (value!='')
					if (value.length == 18)
						if (validateCNPJ(value))
							return true;			
						else
							return false;
					else
						return false;	
				else
					return false;	
			}
		);	
		
		this.setHandler('pis', 
			function (value) {
				if (value!='')
					if (value.length == 14)
						if (validatePIS(value))
							return true;			
						else
							return false;
					else
						return false;	
				else
					return false;	
			}
		);	
		
		this.setHandler('date', 
			function (value) {
				if (value!='')
					if (value.length == 10)
						if (validateDate(value))
							return true;			
						else
							return false;
					else
						return false;	
				else
					return false;	
			}
		);	
		
		this.setHandler('idade', 
			function (value) {
				if (value!='')
					if (value.length == 10)
						if (validateIdade(value))
							return true;			
						else
							return false;
					else
						return false;	
				else
					return false;	
			}
		);			
		
		this.setHandler('hoje', 
			function (value) {
				if (value!='')
					if (value.length == 10)
						if (validateHoje(value))
							return true;			
						else
							return false;
					else
						return false;	
				else
					return false;	
			}
		);
		
		this.setHandler('maxdate', 
			function (value) {
				if (value!='')
					if (value.length == 10)
						if (validateMaxDate(value, document.id( 'max_date').value ))
							return true;			
						else
							return false;
					else
						return false;	
				else
					return false;	
			}
		);
			
		this.setHandler('hour',
			function (value) {
				regex=/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;
				return regex.test(value);
			}
		);

		
		// Attach to forms with class 'form-validate'
		var forms = $$('form.form-validate');
		forms.each(function(form){ this.attachToForm(form); }, this);
	},

	setHandler: function(name, fn, en)
	{
		en = (en == '') ? true : en;
		this.handlers[name] = { enabled: en, exec: fn };
	},

	attachToForm: function(form)
	{
		// Iterate through the form object and attach the validate method to all input fields.
		form.getElements('input,textarea,select,button').each(function(el){
			if (el.hasClass('required')) {
				el.set('aria-required', 'true');
				el.set('required', 'required');
			}
			if ((document.id(el).get('tag') == 'input' || document.id(el).get('tag') == 'button') && document.id(el).get('type') == 'submit') {
				if (el.hasClass('validate')) {
					el.onclick = function(){return document.formvalidator.isValid(this.form);};
				}
			} else {
				el.addEvent('blur', function(){return document.formvalidator.validate(this);});
				//el.addEvent('click', function(){return document.formvalidator.validate(this);});
				if (el.hasClass('validate-email') && Browser.Features.inputemail) {
					el.type = 'email';
				}
			}
		});
	},

	validate: function(el)
	{
		el = document.id(el);

		// Ignore the element if its currently disabled, because are not submitted for the http-request. For those case return always true.
		if(el.get('disabled')) {
			this.handleResponse(true, el);
			return true;
		}

		// If the field is required make sure it has a value
		if (el.hasClass('required')) {
			if ( ( (el.get('tag')=='input' && el.get('type')=='checkbox' ) || (el.get('tag')=='input' && el.get('type')=='radio' ) ) &&  
			     ( el.hasClass('group-radio') || el.hasClass('group-checkboxes') ) 
			   ) {
			//if (el.get('tag')=='fieldset' && (el.hasClass('group-radio') || el.hasClass('group-checkboxes'))) {
				var idElemnets = $(el).parent('label').parent('fieldset').attr('for');
				var checkedElement = false;
				
				for(var i=0;;i++) {
					if (document.id(idElemnets+i)) {
						if (document.id(idElemnets+i).checked) {
							checkedElement = true;
							break;
						}
					}
					else
						break;
				}	
				if (!checkedElement) {
						errorText = Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_GROUP_CHECK_OR_RADIO_REQUIRED');						
						this.handleResponse(false, el);
						return false;
				}
			} 
			else if (el.get('tag')=='input' && el.get('type')=='checkbox' ) {
				if (!el.checked)	{	
				errorText = Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_CHECKBOX_REQUIRED');
				this.handleResponse(false, el);
				return false;
				}
			}
			else if (!(el.get('value'))) {
				errorText = Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_REQUIRED');
				this.handleResponse(false, el);
				return false;
			}
		}

		// Only validate the field if the validate class is set
		var handler = (el.className && el.className.search(/validate-([a-zA-Z0-9\_\-]+)/) != -1) ? el.className.match(/validate-([a-zA-Z0-9\_\-]+)/)[1] : "";
		var handler2 = (el.className && el.className.search(/validate-idade/) != -1) ? 'idade' : "";
		var handler3 = (el.className && el.className.search(/validate-hoje/) != -1) ? 'hoje' : "";
		var handler4 = (el.className && el.className.search(/validate-maxdate/) != -1) ? 'maxdate' : "";
		if (handler3 == '' && handler2 == '' && handler == '' && handler4 == '') {
			this.handleResponse(true, el);
			return true;
		}	
		else if(handler4 == '' &&  handler3 == '' && handler == '') {
			this.handleResponse(true, el);
			return true;
		}
		else if(handler4 == '' &&  handler2 == '' && handler == '') {
			this.handleResponse(true, el);
			return true;
		}
		else if(handler3 == '' &&  handler2 == '' && handler == '') {
			this.handleResponse(true, el);
			return true;
		}
		
		else if( handler4 == '' && handler == '') {
			this.handleResponse(true, el);
			return true;
		}
		else if( handler3 == '' && handler == '') {
			this.handleResponse(true, el);
			return true;
		}
		else if( handler2 == '' && handler == '') {
			this.handleResponse(true, el);
			return true;
		}
		else if( handler == '') {
			this.handleResponse(true, el);
			return true;
		}
		// Check the additional validation types
		if ((handler) && (handler != 'none') && (this.handlers[handler]) && el.get('value')) {
			// Execute the validation handler and return result
			if (this.handlers[handler].exec(el.get('value')) != true) {
				errorText = Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_'+handler);
				this.handleResponse(false, el);
				return false;
			}
		}
		
		if ((handler2) && (handler2 != 'none') && (this.handlers[handler2]) && el.get('value')) {
			// Execute the validation handler and return result
			if (this.handlers[handler2].exec(el.get('value')) != true) {
				errorText = Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_'+handler2);
				this.handleResponse(false, el);
				return false;
			}
		}

		if ((handler3) && (handler3 != 'none') && (this.handlers[handler3]) && el.get('value')) {
			// Execute the validation handler and return result
			if (this.handlers[handler3].exec(el.get('value')) != true) {
				errorText = Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_'+handler3);
				this.handleResponse(false, el);
				return false;
			}
		}
		
		if ((handler4) && (handler4 != 'none') && (this.handlers[handler4]) && el.get('value')) {
			// Execute the validation handler and return result
			if (this.handlers[handler4].exec(el.get('value')) != true) {
				errorText = Joomla.JText._('EASISTEMAS_SCRIPT_VALIDATE_ERROR_INPUT_'+handler4);
				this.handleResponse(false, el);
				return false;
			}
		}	
		// Return validation state
		this.handleResponse(true, el);
		return true;
	},

	isValid: function(form)
	{
		var valid = true;

		// Validate form fields
		var elements = form.getElements('fieldset').concat(Array.from(form.elements));
		for (var i=0;i < elements.length; i++) {
			if (this.validate(elements[i]) == false) {
				valid = false;
			}
		}

		// Run custom form validators if present
		new Hash(this.custom).each(function(validator){
			if (validator.exec() != true) {
				valid = false;
			}
		});

		return valid;
	},

	handleResponse: function(state, el)
	{
		// Find the label object for the given field if it exists
		if (!(el.labelref)) {
			var labels = $$('label');
			labels.each(function(label){
				if (label.get('for') == el.get('id')) {
					el.labelref = label;
				}
			});
		}

		// Set the element and its label (if exists) invalid state
		if (state == false) {
			if ( ( (el.get('tag')=='input' && el.get('type')=='checkbox' ) || (el.get('tag')=='input' && el.get('type')=='radio' ) ) &&  
			     ( el.hasClass('group-radio') || el.hasClass('group-checkboxes') ) 
			   ) {
				   
				$(el).parent('label').parent('fieldset').parent('div').addClass('has-error');
				//if($(el).closest('.tab-pane').attr('id').length)
				//	$('a[href="#' + $(el).closest('.tab-pane').attr('id')+'"]').parent('li').addClass('has-error');   
				$(el).parent('label').parent('fieldset').next('p').remove();
				$(el).parent('label').parent('fieldset').after( '<p class="error">'+errorText+'</p>' );
			}
			else if (el.get('tag')=='input' && el.get('type')=='checkbox' ) {
				$(el).parent('label').parent('div').addClass('has-error');
				//if($(el).closest('.tab-pane').attr('id').length)
				//	$('a[href="#' + $(el).closest('.tab-pane').attr('id')+'"]').parent('li').addClass('has-error');
				$(el).parent('label').next('p').remove();
				$(el).parent('label').after( '<p class="error">'+errorText+'</p>' );
			}
			else {

				if($(el).parent('div').hasClass('input-group'))
				{
					$(el).parent('div').parent('div').addClass('has-error');
					//if($(el).closest('.tab-pane').attr('id').length)
					//	$('a[href="#' + $(el).closest('.tab-pane').attr('id')+'"]').parent('li').addClass('has-error');
					$(el).parent('div').next('p').remove();
					$(el).parent('div').after( '<p class="error">'+errorText+'</p>' );		
				}
				else
				{
					if(el.hasClass('select2'))
					{
						$(el).parent('div').addClass('has-error');
						//if($(el).closest('.tab-pane').attr('id').length)
						//	$('a[href="#' + $(el).closest('.tab-pane').attr('id')+'"]').parent('li').addClass('has-error');
						$(el).closest('.tab-content').toggleClass( "highlight" );
						$(el).next('span').next('p').remove();
						$(el).next('span').after( '<p class="error">'+errorText+'</p>' );
					}
					else
					{
						$(el).parent('div').addClass('has-error');
						//if($(el).closest('.tab-pane').attr('id').length)
						//	$('a[href="#' + $(el).closest('.tab-pane').attr('id')+'"]').parent('li').addClass('has-error');
						$(el).next('p').remove();
						$(el).after( '<p class="error">'+errorText+'</p>' );
					}			
				}
			}
			tela ++;
			if (tela == 1 ) {
				$('html, body').animate({
					scrollTop: $(el).offset().top -$(window).height()/3
				}, 700);
			}
		} else {
			if ( ( (el.get('tag')=='input' && el.get('type')=='checkbox' ) || (el.get('tag')=='input' && el.get('type')=='radio' ) ) &&  
			     ( el.hasClass('group-radio') || el.hasClass('group-checkboxes') ) 
			   ) {
				$(el).parent('label').parent('fieldset').parent('div').removeClass('has-error');   
				$(el).parent('label').parent('fieldset').next('p').remove();
				
			}
			else if (el.get('tag')=='input' && el.get('type')=='checkbox' ) {
				$(el).parent('label').parent('div').removeClass('has-error');
				$(el).parent('label').next('p').remove();
			}
			else {
				
				if($(el).parent('div').hasClass('input-group'))
				{
					$(el).parent('div').parent('div').removeClass('has-error');
					$(el).parent('div').next('p').remove();
					error = false;			
				}
				else
				{
					if(el.hasClass('select2'))
					{
						$(el).next('span').next('p').remove();
						$(el).parent('div').removeClass('has-error');
						error = false;	
					}
					else
					{
						$(el).next('p').remove();
						$(el).parent('div').removeClass('has-error');
						error = false;	
					}		
				}
			}
		}
	}
});

document.formvalidator = null;
window.addEvent('domready', function(){
	document.formvalidator = new JFormValidator();
});
