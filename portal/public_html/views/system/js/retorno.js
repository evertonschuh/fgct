$(function() {
    $('.image-preview-clear').click(function(){
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
		$('.image-preview-input').show();
		$("#load_return").html('<tr><td colspan="10">Não há arquivo de retorno carregado</td></tr>');
		$("#import").attr('disabled', true);
		
    }); 
    // Create the preview image
    $(" #file_return").change(function (){     
	 	$('#loading').append(spinner.el);	
	    var file = this.files[0];
		var data = new FormData();

		$('.image-preview-input').hide();
		$(".image-preview-clear").show();
		$(".image-preview-filename").val(file.name);  

		jQuery.each(jQuery('#file_return')[0].files, function(i, file) {
			data.append('file'+i, file);
		})
		
		$.ajax({
			url: "views/system/includes/retorno.php", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				$('#loading').children(spinner.el).remove();
				$("#load_return").html(data);
				if ( $('input[name="cid[]"]' ).length )
					$("#import").attr('disabled', false);
				if ( $('td' ).length ) {
					$("#print").attr('disabled', false);	
					$("#print").attr('href', 'index.php?view=finretorno&layout=report&tmpl=print&session=' + $("#sessionid").val() + '&file=' + $("#filename").val());
				}
					
			}
		});
    });  
});
