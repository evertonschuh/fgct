"use strict";

$(function(){

    var $validator = $('.bs-stepper-content form').validate({
        rules: {
            id_equipe: {
                required: true
            },
            id_local: {
                requireone: true
            },
            databaseradio: {
                required: true
            },
            
        },
    });


    var e=document.getElementById("createApp");



    let r;
    e.addEventListener("show.bs.modal",
    function(e){
        var t=document.querySelector("#wizard-create-app");
        if(null!==t)
        {
            var n=[].slice.call(t.querySelectorAll(".btn-next")),
                c=[].slice.call(t.querySelectorAll(".btn-prev")),
                r=t.querySelector(".btn-submit");
                const a=new Stepper(t,{linear:!1});
                //a.steps("remove", 2);
                n&&n.forEach(e=>{e.addEventListener("click",e=>{if(l()) a.next()})}),
                c&&c.forEach(e=>{e.addEventListener("click",e=>{a.previous(),l()})}),
                r&&r.addEventListener("click",e=>{alert("Submitted..!!")})
        }
    });


    function l(){
        var $valid = $('.bs-stepper-content form').valid();

        /*$('.bs-stepper-content form input').each(function( index ) {
            alert($( this ).attr('name') );
          });*/
      //  alert(  $valid)
       // alert($('input[name="id_local"]:checked').val());
        if(!$valid) {
            $validator.focusInvalid();
            return false;
        }
        return true;
    }

	jQuery('.register').click(function(){
   
		var Control = jQuery(this);
		Control.attr('disabled',true).html('Inscreva-se<i class="ms-1 spinner-border spinner-border-sm"></i>');
  
        var id_etapa = Control.data('etapa');
        var id_prova = Control.data('prova');

        //Control.attr('disabled',false).html('Inscreva-se<i class="ms-1 bx bx-xs bx-chevron-right"></i>');
		
       // var CEP = Control.parent().find('.cep').val();

		jQuery.post('/dynamic/signUp.php', 
		{   
            id_etapa:id_etapa, id_prova:id_prova, execute:'show-modal'},
			function(valor)
            {
				 if(valor != 'error')
                 {
                    jQuery('#wizard-create-app').html(valor);
                    jQuery('#createApp').modal('show');

                    jQuery('.select2').select2({dropdownParent: $("#createApp")});

                    window.Helpers.initCustomOptionCheck();

                    var $validator = jQuery('.bs-stepper-content form').validate({
                        errorPlacement: function (error, element) {
                            if (element.parent('.input-group').length) { 
                                error.insertAfter(element.parent());      // radio/checkbox?
                            } else if (element.hasClass('select2-hidden-accessible')) {     
                                error.insertAfter(element.next('span'));  // select2
                                element.next('span').addClass('error').removeClass('valid');
                            } else {                                      
                                error.insertAfter(element);               // default
                            }
                        },
                    });

                    jQuery('.select2-hidden-accessible').on('change', function() {
                        if($(this).valid()) {
                            $(this).next('span').removeClass('error').addClass('valid');
                        }
                    });

                    jQuery.validator.addMethod('requireone', function(value, element) {
                        alert('est');
                        if (element.is(':checked')) {
                            return true;
                        } else {
                            return true;
                        }
                    }, 'Selecione pelo menos uma das opções.');

					Control.attr('disabled',false).html('Inscreva-se<i class="ms-1 bx bx-xs bx-chevron-right"></i>');
				}
				else {
					Control.attr('disabled',false).html('Inscreva-se<i class="ms-1 bx bx-xs bx-chevron-right"></i>');
					Swal.fire({title:'CEP não encontrado',html:'O CEP ' + CEP + ' não foi localizado no site dos correios. Verifique se você preencheu corretamente e corrija caso necessário.', icon:'info'});
			  	}	
			}).fail(function() {					
			Control.attr('disabled',false).html('Inscreva-se<i class="ms-1 bx bx-xs bx-chevron-right"></i>');
			Swal.fire({title:'CEP não encontrado',html:'O CEP ' + CEP + ' não foi localizado no site dos correios. Verifique se você preencheu corretamente e corrija caso necessário.', icon:'info'});
		});
	});




});


        /*
    
        t&&(
            r=new Cleave(t,{creditCard:!0,onCreditCardTypeChanged:function(e){document.querySelector(".app-card-type").innerHTML=""!=e&&"unknown"!=e?'<img src="'+assetsPath+"img/icons/payments/"+e+'-cc.png" class="cc-icon-image" height="28"/>':""}}))}n&&new Cleave(n,{date:!0,delimiter:"/",datePattern:["m","y"]}),c&&new Cleave(c,{numeral:!0,numeralPositiveOnly:!0}),e.addEventListener("show.bs.modal",function(e){var t=document.querySelector("#wizard-create-app");





;*/
