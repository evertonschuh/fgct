jQuery(document).ready(function(){

	jQuery("#dataInicio").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L',
	});
	
	jQuery("#dataFim").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L',
	});

	function defineMaxDate(){
		var date = jQuery('#dataFim').data('date')
	   	if(date){
			jQuery('#dataInicio').data("DateTimePicker").maxDate(moment(date, 'DD-MM-YYYY'));
		}
	}
	defineMaxDate();
	
	function defineMinDate(){
		var date = jQuery('#dataInicio').data('date')
	   	if(date){
			jQuery('#dataFim').data("DateTimePicker").minDate(moment(date, 'DD-MM-YYYY'));
		}
	}
	defineMinDate();

	
	jQuery('#dataInicio').on('dp.change', function(e){ jQuery( 'form[name="adminForm"]' ).submit(); })
	jQuery('#dataFim').on('dp.change', function(e){ jQuery( 'form[name="adminForm"]' ).submit(); })
	
});