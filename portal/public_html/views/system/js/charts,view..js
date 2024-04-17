jQuery(document).ready(function(){

	jQuery('select[name="type_data_chart"]').change(function(){
		window.parent.postMessage({
            mceAction: 'execCommand',
            cmd: 'chartCommand',
            value: jQuery(this).val()
          }, origin);
	});	

});