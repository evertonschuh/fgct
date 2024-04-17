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
	
	
	$('#lote').change(function(){
		var lote_value = $(this).val();

		$('#loteparams').html('');
		$('#loading').append(spinner.el);	
		$.post('views/system/includes/dynamiclote.php', 
			  {lote:lote_value},
			  function(valor){
				 $('#loteparams').html(valor);
				 $('#loading').children(spinner.el).remove();
				 if(lote_value == 'updatevalue')
				 	initMaskMoney('.value-money');
			  }
		)
	});	
	
	$('input[name="checkall-toggle"]').click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
		executeChkFunction()
	});
	
	$('input[name="cid[]"]').click(function(){
		executeChkFunction()
	});
	

	
	$(document).on('click', '#preview_value', function() {	
		if($(this).prop('checked'))
			$(' .div-result-calc').fadeIn();
		else
			$(' .div-result-calc').fadeOut();
	});
	
		
	Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
		places = !isNaN(places = Math.abs(places)) ? places : 2;
		symbol = symbol !== undefined ? symbol : "$";
		thousand = thousand || ",";
		decimal = decimal || ".";
		var number = this, 
			negative = number < 0 ? "-" : "",
			i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
		return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
	};		

	
});
function initMaskMoney(selector) {
    $(selector).maskMoney({prefix:'R$ ', thousands:'.', decimal:',', affixesStay: true}); 
}

function executeChkFunction()
{
	var checktotal = 0;
	var checkDias = 0
	
	$('input[name="cid[]"]').each(function(){
		if($(this).prop('checked')){
			checktotal++;
			checkDias += parseInt($('#dias' + $(this).val()).val());
		}
	});

	checkDias = parseInt(checkDias / checktotal );
	var BaseCalculo = 0.05/30*checkDias;
	var valorTotal = 0;
	var desagioTotal = 0;
	var liquidoTotal = 0;
	$('input[name="cid[]"]').each(function(){
		if($(this).prop('checked')){
			var valor =  parseFloat($('#value' + $(this).val()).val());
			var desagio = valor*BaseCalculo;
			var liquido = valor - desagio;
			valorTotal += valor;
			desagioTotal += desagio;
			liquidoTotal += liquido;
			//$('#ds' + $(this).val()).value(valor);
			$('#dsh' + $(this).val()).html(desagio.formatMoney(2, "R$ ", ".", ","));
			$('#lqh' + $(this).val()).html(liquido.formatMoney(2, "R$ ", ".", ","));
		}
		else
		{
			$('#dsh' + $(this).val()).html('');
			$('#lqh' + $(this).val()).html('');
		}
		
	});


	$('#ttit').html(checktotal);
	$('#mddia').html(checkDias);
	$('#ttval').html(valorTotal.formatMoney(2, "R$ ", ".", ","));
	$('#ttdes').html(desagioTotal.formatMoney(2, "R$ ", ".", ","));
	$('#ttliq').html(liquidoTotal.formatMoney(2, "R$ ", ".", ","));
	
}


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
