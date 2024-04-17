
$(document).ready(function(){

	jQuery('#value_question').on('input', function() {
		this.value = this.value.replace(/(?!^-)[^0-9.]/g, "").replace(/(\..*)\./g, '$1'); 
  	});
});

