
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
    <td class="sorting_1"><?php echo  $item->name_documento; ?></td>
    <td class="text-center"><?php echo $item->numero_documento_numero; ?></td>
    <td class="text-center"><?php echo JHtml::date(JFactory::getDate($item->register_documento_numero, $siteOffset)->toISO8601(), 'DATE_FORMAT_DATATIME', true); ?></td>
    <td class="text-center"><?php echo $item->id_documento_numero; ?></td>
    <td>
        <div class="d-inline-block text-nowrap">
            <a href="<?php echo JRoute::_('index.php?view=document&cid=' . $item->id_documento_numero); ?>" class="btn btn-sm btn-icon" target="_blank"><i class='bx bx-printer'></i></a>
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

