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
    $classLine = '';
    if($item->status_associado==1):
        if(is_null($item->validate_associado)):
            $classLine = 'success';
        elseif( JFactory::getDate('now -1 year', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->validate_associado, $siteOffset)->toFormat('%Y-%m-%d', true) ):
            $classLine = 'danger';
        elseif( JFactory::getDate('now', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->validate_associado, $siteOffset)->toFormat('%Y-%m-%d', true) ):
            $classLine = 'warning';
        endif;
    else:
        $classLine = 'dark';
    endif;
?>     
    <tr class="odd <?php  echo $classLine; ?>">
        <td class="sorting_1"><?php echo $item->name; ?></td>
        <td class="text-center">       
            <?php 
            $status = '<span class="badge bg-label-success">Ativo</span>';
            if($item->status_associado==0)
                $status = '<span class="badge bg-label-secondary">Inativo</span>';

            echo $status;
            ?>
        </td>
        <td class="text-center">
            <span class="fw-semibold"><?php if($item->validate_associado) echo JHtml::date(JFactory::getDate($item->validate_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT', true); else echo '-'; ?></span>
        </td>
        <td>
            <div class="d-inline-block text-nowrap">
                <input type="checkbox" style="display:none" id="mid<?php echo $i; ?>" name="mid[]" value="<?php echo $item->id_user; ?>" >
                <button type="button" class="btn btn-sm btn-icon delete-record" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja remover este associado desta lista?<br/><strong><?php echo $item->name; ?></strong>',icon:'warning',showCancelButton: true,confirmButtonColor: '#595cd9',cancelButtonColor: '#ff3e1d',cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('mid<?php echo $i; ?>','removeUsers');}});"><i class="bx bx-trash"></i></button>

            </div>
        </td>
    </tr> 

    <?php /*
    <td class="order">
			<?php if ($canChange) : ?>
                <?php if ($saveOrder) :?>
                    <?php if ($listDirn == 'asc') : ?>
                        <span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->id_aula == $item->id_aula && @$this->items[$i-1]->semestre_material == $item->semestre_material && @$this->items[$i-1]->id_material_type == $item->id_material_type), 'orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                        <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->id_aula == $item->id_aula && @$this->items[$i+1]->semestre_material == $item->semestre_material && @$this->items[$i+1]->id_material_type == $item->id_material_type), 'orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                    <?php elseif ($listDirn == 'desc') : ?>
                        <span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->id_aula == $item->id_aula && @$this->items[$i-1]->semestre_material == $item->semestre_material && @$this->items[$i-1]->id_material_type == $item->id_material_type), 'orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                        <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->id_aula == $item->id_aula && @$this->items[$i+1]->semestre_material == $item->semestre_material && @$this->items[$i+1]->id_material_type == $item->id_material_type), 'orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
                <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
                <input type="text" name="ordering[]" size="4" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" /> 
            <?php else : ?>
                <?php echo $item->ordering; ?>
            <?php endif; ?>
        </td>   
    </tr>  
    */
    ?>

<?php	
	}
	
}
else
{
?>
    <tr>
        <td colspan="6">
        Não há conteúdo a exibir
        </td>
    </tr>
<?php
}
?>



