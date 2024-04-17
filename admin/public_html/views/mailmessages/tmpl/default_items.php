<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'ordering';
$ordering	= ($listOrder == 'ordering');
$user = JFactory::getUser();
$canChange	= $user->authorise('core.edit.state');

if(count($this->items))
{	
	foreach ($this->items as $i => $item) 
	{
?>       


<tr class="odd">
    
    <td class="sorting_1">
        <div class="d-flex justify-content-start align-items-center user-name">
            <div class="d-flex flex-column">
                <a href="<?php echo JRoute::_('index.php?view=mailmessage&cid=' . $item->id_mailmessage); ?>" class="text-body text-truncate">
                    <span class="fw-semibold"><?php echo $item->name_mailmessage_occurrence; ?></span>
                </a>
                <small class="text-muted">Assunto: <?php echo $item->subject_mailmessage; ?></small>
            </div>
        </div>
    </td>
    
    <td class="text-center">
        <span class="badge bg-label-<?php echo ($item->status_mailmessage == 1 ? 'success' : ($item->status_mailmessage < 0 ? 'secondary' : 'danger'))?>">
            <span class="badge bg-label-<?php echo ($item->status_mailmessage == 1 ? 'success' : ($item->status_mailmessage < 0 ? 'secondary' : 'danger'))?>">
            <?php echo ($item->status_mailmessage == 1 ? 'Ativo' : ($item->status_mailmessage < 0 ? 'Lixo' : 'Inativo'))?>
            <span>
        </span>
    </td>
    <td class="text-center">
        <span class="fw-semibold"><?php echo $item->id_mailmessage; ?></span>
    </td>
    <td>
        <div class="d-inline-block text-nowrap">
            <input type="checkbox" style="display:none" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $item->id_mailmessage; ?>" >
            <a href="<?php echo JRoute::_('index.php?view=mailmessage&cid=' . $item->id_mailmessage); ?>" class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>
            <?php if($item->status_mailmessage >= 0): ?>
            <button type="button" class="btn btn-sm btn-icon delete-record" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja enviar para lixeira este item?<br/><strong><?php echo $item->subject_mailmessage; ?></strong>',icon:'none',showCancelButton: true,confirmButtonColor: '#ff3e1d', cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('cb<?php echo $i; ?>','trash');}});"><i class="bx bx-trash"></i></button>
            <?php else: ?>

            <?php endif; ?>
            <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu dropdown-menu-end m-0">
                <?php if($item->status_mailmessage == 1): ?>
                <a href="javascript:;" onClick="return listItemTask('cb<?php echo $i; ?>','unpublish');" class="dropdown-item">Inativar</a>
                <?php elseif($item->status_mailmessage < 0): ?>
                <a href="javascript:;" onClick="return listItemTask('cb<?php echo $i; ?>','untrash');" class="dropdown-item">Restaurar</a>
                <a href="javascript:;" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja<br/><strong>REMOVER DEFINITIVAMENTE</strong><br/>este item?<br/><br/><strong><?php echo $item->subject_mailmessage; ?></strong><br/><br/>Uma vez excluído, não será mais possivel recuperar as informações.',icon:'none',showCancelButton: true,confirmButtonColor: '#ff3e1d', cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('cb<?php echo $i; ?>','remove');}});" class="dropdown-item">Remover Definitivamente</a>
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
        Não há mensagens a exibir
        </td>
    </tr>
<?php
}
?>



