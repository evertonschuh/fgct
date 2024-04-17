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
    <tr>
    	<td><?php echo JHtml::_('grid.id', $i, $item->id_mailmessage); ?></td>
        <td>
			<?php 
            $classBlock = 'full';
            if ($item->checked_out) :
				$userEdit = JFactory::getUser($item->checked_out);
				$classBlock='blockName';
            	echo JHtml::_('jgrid.checkedout', $i, $userEdit->get('name'), $item->checked_out_time);
			endif; 
            ?>  
        	<a class="table-link" href="<?php echo JRoute::_('index.php?view=autmanmessage&cid=' . $item->id_mailmessage); ?>"><?php echo $item->name_mailmessage_occurrence; ?></a></td>
        <td align="center"><?php echo  JHTML::_('grid.published', $item->status_mailmessage, $i); ?></td>
        <td class="hidden-xs hidden-sm" align="center"><?php echo $item->name_modality; ?></td>
        <td class="hidden-xs hidden-sm" align="center"><?php echo $item->name_evento_tipo; ?></td>
        <td class="hidden-xs hidden-sm" align="center"><?php echo $item->name_evento; ?></td>
        <td class="hidden-xs" align="center"><?php echo $item->id_mailmessage; ?></td>
    </tr>  


<?php	
	}
	
}
else
{
?>
    <tr>
        <td colspan="8">
        Não há mensagens automáticas a exibir
        </td>
    </tr>
<?php
}
?>



