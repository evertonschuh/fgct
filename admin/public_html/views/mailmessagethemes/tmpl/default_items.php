<?php

defined( '_JEXEC' ) or die( 'Restricted access' );


$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

if(count($this->items))
{	
	foreach ($this->items as $i => $item) 
	{
?>      
<tr class="odd">
    <td class="control" tabindex="0" style="display: none;"></td>
    <td class="sorting_1">
        <a href="<?php echo JRoute::_('index.php?view=mailmessagetheme&cid=' . $item->id_mailmessage_theme); ?>" class="text-body text-truncate">
            <span class="fw-semibold"><?php echo $item->name_mailmessage_theme; ?></span>
        </a>
    </td>
    <td class="text-center">
        <span class="badge bg-label-<?php echo ($item->status_mailmessage_theme == 1 ? 'success' : ($item->status_mailmessage_theme < 0 ? 'secondary' : 'danger'))?>">
            <span class="badge bg-label-<?php echo ($item->status_mailmessage_theme == 1 ? 'success' : ($item->status_mailmessage_theme < 0 ? 'secondary' : 'danger'))?>">
            <?php echo ($item->status_mailmessage_theme == 1 ? 'Ativo' : ($item->status_mailmessage_theme < 0 ? 'Lixo' : 'Inativo'))?>
            <span>
        </span>
    </td>
    <td class="text-center">
        <span class="fw-semibold"><?php echo $item->id_mailmessage_theme; ?></span>
    </td>
    <td>
        <div class="d-inline-block text-nowrap">
            <input type="checkbox" style="display:none" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $item->id_mailmessage_theme; ?>" >
            <a href="<?php echo JRoute::_('index.php?view=mailmessage&cid=' . $item->id_mailmessage_theme); ?>" class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>
            <?php if($item->status_mailmessage_theme >= 0): ?>
            <button type="button" class="btn btn-sm btn-icon delete-record" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja enviar para lixeira este item?<br/><strong><?php echo $item->name_mailmessage_theme; ?></strong>',icon:'none',showCancelButton: true,confirmButtonColor: '#ff3e1d', cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('cb<?php echo $i; ?>','trash');}});"><i class="bx bx-trash"></i></button>
            <?php else: ?>

            <?php endif; ?>
            <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu dropdown-menu-end m-0">
                <?php if($item->status_mailmessage_theme == 1): ?>
                <a href="javascript:;" onClick="return listItemTask('cb<?php echo $i; ?>','unpublish');" class="dropdown-item">Inativar</a>
                <?php elseif($item->status_mailmessage_theme < 0): ?>
                <a href="javascript:;" onClick="return listItemTask('cb<?php echo $i; ?>','untrash');" class="dropdown-item">Restaurar</a>
                <a href="javascript:;" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja<br/><strong>REMOVER DEFINITIVAMENTE</strong><br/>este item?<br/><br/><strong><?php echo $item->name_mailmessage_theme; ?></strong><br/><br/>Uma vez excluído, não será mais possivel recuperar as informações.',icon:'none',showCancelButton: true,confirmButtonColor: '#ff3e1d', cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('cb<?php echo $i; ?>','remove');}});" class="dropdown-item">Remover Definitivamente</a>
                <?php else: ?>
                <a href="javascript:;" onClick="return listItemTask('cb<?php echo $i; ?>','publish');" class="dropdown-item">Ativar</a>
                <?php endif; ?>
            </div>
        </div>
    </td>
</tr>  

<?php	
	}
	
}
else
{
?>
    <tr>
        <td colspan="8">
        Não há temas a exibir
        </td>
    </tr>
<?php
}
?>



