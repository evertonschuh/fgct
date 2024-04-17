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
    <td class="sorting_1 d-none d-md-table-cell"><?php echo $item->id_pagamento; ?></td>
    <td class="sorting_1"><?php echo $item->produto; ?></td>
    <td class="text-center d-none d-md-table-cell"><?php echo JHtml::date(JFactory::getDate($item->vencimento_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT', true); ?></td>
    <td class="text-center d-none d-md-table-cell"><?php echo number_format($item->valor_pagamento, 2, ',', '.'); ?></td>
    <td class="text-center">  
        <?php 

        $status = '<span class="badge bg-label-success">Pago</span>';
        $pago = true;
        if($item->status_pagamento==1):
            if(is_null($item->baixa_pagamento)):
                $status = '<span class="badge bg-label-info">Aguardando</span>';
                $pago = false;
            if( JFactory::getDate('now', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->vencimento_pagamento, $siteOffset)->toFormat('%Y-%m-%d', true) )
                $status = '<span class="badge bg-label-danger">Vencido</span>';
            endif;
        else:
            $status = '<span class="badge bg-label-secondary">Cancelado</span>';
        endif;
        echo $status;
       
        ?>
        
    </td>
    <td class="text-center d-none d-md-table-cell"><?php echo $item->baixa_pagamento>0 ? JHtml::date(JFactory::getDate($item->baixa_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT', true) : '-'; ?></td>
    <td class="text-center d-none d-md-table-cell"><?php echo $item->name_pagamento_metodo; ?></td>
    <td class="text-center" width="2%">
        <div class="d-inline-block text-nowrap">
            <?php if(!$pago): ?>
            <a href="<?php echo JRoute::_('index.php?view=payment&format=pdf&cid=' . $item->id_pagamento); ?>" class="btn btn-sm btn-icon" target="_blank"><i class='bx bx-printer'></i></a>
            <?php endif; ?>
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