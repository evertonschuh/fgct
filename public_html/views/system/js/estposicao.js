var checkVisible = false;

$(document).ready(function(){
	$("#dataInicio").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L'
	});
	
	$("#dataFim").datetimepicker({
		useCurrent: false ,
		locale: 'pt-br',
		format: 'L',
	});
	
	$('#advanceSearch').on('shown.bs.collapse', function () {
		$('#advanceCod').html('(-)');
	});
	$('#advanceSearch').on('hide.bs.collapse', function () {
		$('#advanceCod').html('(+)');
	});
	
	function defineMaxDate(){
		var date = $('#dataFim').data('date')
	   	if(date){
			$('#dataInicio').data("DateTimePicker").maxDate(moment(date, 'DD-MM-YYYY'));
		}
	}
	defineMaxDate();
	
	function defineMinDate(){
		var date = $('#dataInicio').data('date')
	   	if(date){
			$('#dataFim').data("DateTimePicker").minDate(moment(date, 'DD-MM-YYYY'));
		}
	}
	defineMinDate();

	
	$('#dataInicio').on('dp.change', function(e){ $( 'form[name="adminForm"]' ).submit(); })
	$('#dataFim').on('dp.change', function(e){ $( 'form[name="adminForm"]' ).submit(); })
	
	
});