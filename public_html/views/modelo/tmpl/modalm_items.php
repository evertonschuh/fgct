<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');


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

        if($item->id_documento)
            $classLine = 'info';
?>       
    <tr class="<?php  echo $classLine; ?>">
    	<td><?php if($item->id_documento) echo '<i class="fa fa-check" aria-hidden="true"></i>'; else  echo JHtml::_('grid.id', $i, $item->id_user, false, 'vid'); ?></td>
        <td><?php echo $item->name; ?></td>
        <td align="center"><span class="grid-icon"><i class="fa <?php if ($item->status_associado) echo 'fa-eye'; else echo 'fa-eye-slash'; ?>"></i></span></td>
		<td align="center"><?php if($item->validate_associado) echo JHtml::date(JFactory::getDate($item->validate_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT', true); else echo '-'; ?></td>
    </tr>  

<?php	
	}
	
}
else
{
?>
    <tr>
        <td colspan="8">
        Não há Associados a exibir
        </td>
    </tr>
<?php
}
?>



