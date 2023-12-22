<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'ordering';
$ordering	= ($listOrder == 'ordering');

$canChange	= $this->_user->authorise('core.edit.state');

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

if(count($this->items))
{	
	foreach ($this->items as $i => $item) 
	{
		$classLine = '';
		if($item->status_pagamento):
			if( $item->baixa_pagamento>0 ):
				$classLine = 'success';
			//elseif( JFactory::getDate('now', $siteOffset)->toFormat('%Y-%m-%d', true) <= JFactory::getDate($item->vencimento_pagamento, $siteOffset)->toFormat('%Y-%m-%d', true) ):
			//	$classLine = 'warning';
			elseif( JFactory::getDate('now', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->vencimento_pagamento, $siteOffset)->toFormat('%Y-%m-%d', true) ):
				$classLine = 'danger';
			endif;
		else:
			$classLine = 'dark';
		endif;
?>       
    <tr class="<?php echo $classLine; ?>">
    	<td>
			<?php echo JHtml::_('grid.id', $i, $item->id_pagamento); ?>
            <input type="hidden" id="value<?php echo $item->id_pagamento ?>" value="<?php echo $item->valor_pagamento; ?>" />
            <input type="hidden" id="dias<?php echo $item->id_pagamento ?>" value="<?php echo $item->dias_pagamento; ?>" /> 
        </td>
        <td>
			<?php 
            $classBlock = 'full';
            if ($item->checked_out) :
				$userEdit = JFactory::getUser($item->checked_out);
				$classBlock='blockName';
            	echo JHtml::_('jgrid.checkedout', $i, $userEdit->get('name'), $item->checked_out_time);
			endif; 
            ?>  
        	<a class="table-link" href="<?php echo JRoute::_('index.php?view=finpagamento&cid=' . $item->id_pagamento); ?>">
				<?php echo $item->id_pagamento; ?>
            </a>
        </td>
        <td align="center"><?php echo  JHTML::_('grid.published', $item->status_pagamento, $i); ?></td>
        <td class="hidden-xs hidden-sm" ><?php echo $item->name; ?></td>
		<td class="hidden-xs hidden-sm" ><?php echo $item->produto; ?></td>
        <td class="hidden-xs" align="center"><?php echo JHtml::date(JFactory::getDate($item->vencimento_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT', true); ?></td>    
        <td align="center"><?php echo number_format($item->valor_pagamento, 2, ',', '.'); ?></td>
        <td  class="hidden-xs text-center" align="center"><?php if($item->valor_desconto_pagamento>0) echo number_format($item->valor_desconto_pagamento, 2, ',', '.'); else echo '-'; ?></td>
        <td class="hidden-xs" align="center"><?php echo $item->baixa_pagamento>0 ? JHtml::date(JFactory::getDate($item->baixa_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT', true) : '-'; ?></td>  
        <td align="center"><?php  echo $item->baixa_pagamento>0 ? number_format($item->valor_pago_pagamento, 2, ',', '.') : '-'; ?></td>
        <td class="hidden-xs hidden-sm hidden-md" ><?php echo $item->name_pagamento_metodo; ?></td>
        <td class="hidden-xs hidden-sm hidden-md" align="center"><?php echo ($item->registrado_pagamento>0) ? 'Sim': 'Não'; ?></td>
    </tr>  

<?php	
	}
	
}
else
{
?>
    <tr>
        <td colspan="12">
			Não há pagamento a exibir
        </td>
    </tr>
<?php
}
?>



