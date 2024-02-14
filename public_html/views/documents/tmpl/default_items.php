<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');


if(count($this->items))
{	
	foreach ($this->items as $i => $item) 
	{
?>       
    <tr>
    	<td><?php echo JHtml::_('grid.id', $i, $item->id_documento_numero); ?></td>
        <td >
        	<a class="table-link" target="_blank" href="<?php echo JRoute::_('index.php?view=document&format=pdf&cid=' . $item->id_documento_numero); ?>">
            <?php echo  $item->name; ?>
            </a>
        </td>
        <td class="text-center"><?php echo $item->numero_documento_numero; ?></td>
        <td class="text-center hidden-xs"><?php echo  $item->name_documento; ?></td>     
        <td class="text-center hidden-xs"><?php echo JHtml::date(JFactory::getDate($item->register_documento_numero, $siteOffset)->toISO8601(), 'DATE_FORMAT_DATATIME', true); ?> </td>    
        <td class="text-center"><?php echo $item->id_documento_numero; ?></td>
    </tr>  

<?php	
	}
	
}
else
{
?>
    <tr>
        <td colspan="4">
        Não há Documentos a exibir
        </td>
    </tr>
<?php
}
?>



