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
    <tr class="<?php  echo $classLine; ?>">   
    	<td><?php echo JHtml::_('grid.id', $i, $item->id_user, false, 'mid'); ?></td>
        <td><?php echo $item->name; ?></td>
        <td align="center"><span class="grid-icon"><i class="fa <?php if ($item->status_associado) echo 'fa-eye'; else echo 'fa-eye-slash'; ?>"></i></span></td>
		<td align="center"><?php if($item->validate_associado) echo JHtml::date(JFactory::getDate($item->validate_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT', true); else echo '-'; ?></td>
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



