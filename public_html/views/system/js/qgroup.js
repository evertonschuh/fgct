
$(document).ready(function(){


	jQuery('#add_subtitle input').blur(function() {
		validate(jQuery(this))
	});

	jQuery('#add_subtitle select').blur(function() {
		validate(jQuery(this))
	});

	jQuery(document).on('click','.add_subtitle', function(){
		var valid = true;

		jQuery('#add_subtitle input').each(function( index ) {
			if (validate(jQuery(this)) == false) {
				valid = false;
			}
		});
		
		jQuery('#add_subtitle select').each(function( index ) {
			if (validate(jQuery(this)) == false) {
				valid = false;
			}
		});

		if(valid){

			var min = jQuery('#minimo').val();
			var max = jQuery('#maximo').val();
			var med = jQuery('#medida').val();
			var text = jQuery('#texto').val();


			if(jQuery('#table-subtitles tbody .no-active').length)
				jQuery('#table-subtitles tbody .no-active').remove();	
			
			valor = '<td>De ' + min + ' até ' + max + '</td>'+
					'<td>'+ (med==0 ? 'Pontos' : 'Percentual')+'</td>'+
					'<td>'+text+'</td>'+
					'<td width="1%">'+
					'<input type="hidden" name="min_result[]" value="'+min+'">'+
					'<input type="hidden" name="max_result[]" value="'+max+'">'+
					'<input type="hidden" name="uni_result[]" value="'+med+'">'+
					'<input type="hidden" name="tex_result[]" value="'+text+'">'+
					'<button class="btn btn-danger btn-sm remove" type="button">Remove</button>'+
					'</td>' ;

			jQuery('#table-subtitles tbody').append('<tr>'+valor+'</tr>');

			jQuery('#minimo').val('');
			jQuery('#maximo').val('');
			jQuery('#medida').val('');
			jQuery('#texto').val('');


		}
	});

	jQuery(document).on('click','.remove', function(){

		Swal.fire({
			icon: 'question',
			title: 'Excluir Legenda',
			text: 'Você tem certeza que deseja enviar o relatório e finalizar este encontro ?',

			//showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: `Sim! Excluir`,
			cancelButtonText: 'Cancelar e voltar',
		  }).then((result) => {

				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					var tr = $(this).parent().parent();
					var tbody = $(this).parent().parent().parent();
					tr.remove();
					if(!tbody.find('tr').length) {
						tbody.append('<tr class="no-active"><td colspan="4">Nenhuma Legenda Cadastrada</td></tr>'); 
					}
	
				} else if (result.dismiss === Swal.DismissReason.cancel) {
					return;
				}
		})




	});





});

function validate( obj )
{
	if(obj.val()=="" || obj.val()==null){
		obj.addClass('is-invalid');
		return false;
	}
	obj.removeClass('is-invalid');

	return true;

}
