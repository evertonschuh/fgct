<?php

defined('_JEXEC') or die('Restricted access');

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
jimport('joomla.image.resize');
$resize = new JResize();

if (count($this->items)) {
    foreach ($this->items as $i => $item) {

?>
<tr class="odd">
    <td class="sorting_1 d-none d-md-table-cell"><?php echo $item->id_service; ?></td>
    <td class="sorting_1"><?php echo $item->name_service_type; ?></td>
    <td class="text-center d-none d-md-table-cell"><?php echo JHtml::date(JFactory::getDate($item->create_service, $siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME', true); ?></td>
    
    <td class="text-center">  
        <?php 
        if($item->status_service==1):
            if($item->lasted_user == $item->id_user ):
                $status = '<span class="badge bg-label-info">Em processamento</span>';
            else:
                $status = '<span class="badge bg-label-warning">Aguardando resposta</span>';
            endif;
        elseif($item->status_service==2):
            $status = '<span class="badge bg-label-success">Finalizado</span>';
        else:
            $status = '<span class="badge bg-label-secondary">Cancelado</span>';
        endif;
        echo $status;
       
        ?>
        
    </td>
    <td class="text-center d-none d-md-table-cell"><?php echo JHtml::date(JFactory::getDate($item->lastupdate_service, $siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME');  ?></td>
      <td class="text-center" width="2%">
        <div class="d-inline-block text-nowrap">
            <a href="<?php echo JRoute::_('index.php?view=request&cid=' . $item->id_service); ?>" class="btn btn-sm btn-icon me-2"><i class="bx bx-edit bx-sm"></i></a>
        </div>
    </td>
</tr>

<?php
    }
} else {
    ?>
<tr>
    <td colspan="7">
        Não há ítens a exibir
    </td>
</tr>
<?php
}
?>