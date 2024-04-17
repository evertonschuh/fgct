<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');


if(count($this->items))
{	
	foreach ($this->items as $i => $item) 
	{
        $classLine = '';
	/*	if($item->status_associado==1):
			if(is_null($item->validate_associado)):
				$classLine = 'table-success';
			elseif( JFactory::getDate('now -1 year', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->validate_associado, $siteOffset)->toFormat('%Y-%m-%d', true) ):
				$classLine = 'table-danger';
			elseif( JFactory::getDate('now', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->validate_associado, $siteOffset)->toFormat('%Y-%m-%d', true) ):
				$classLine = 'table-warning';
			endif;
		else:
			$classLine = 'table-secondary';
		endif;
*/
        if($item->id_documento)
            $classLine = 'table-success';
?>       


<tr class="odd <?php  echo $classLine; ?>">
	<td class="sorting_1"><?php echo $item->name; ?> </td>
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
			<?php if($item->id_documento): ?>
				<span class="disabled btn-sm btn-icon">
					<i class='bx bxs-check-circle text-success'></i>
				</span>
			<?php else: ?>
			<input type="checkbox" style="display:none" id="vid<?php echo $i; ?>" name="vid[]" value="<?php echo $item->id_user; ?>" >
			<button type="button" class="btn btn-sm btn-icon" onclick="return listItemTask('vid<?php echo $i; ?>','addUsers');"><i class='bx bx-plus-circle'></i></button>
			<?php endif; ?>
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
        Não há Associados a exibir
        </td>
    </tr>
<?php
}
?>