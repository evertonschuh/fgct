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
    <td class="sorting_1">
        <div class="d-flex justify-content-start align-items-center user-name">
            <div class="avatar-wrapper">
                <?php if(empty($item->image_arma)): ?>
                <div class="avatar avatar-sm me-3">
                    <?php 
                        $sigla = substr($item->name_especie, 0, 2);
                    ?>
                    <span class="avatar-initial rounded-circle bg-label-danger"><?php echo strtoupper($sigla); ?></span>
                </div>
                <?php else: ?>
                <div class="avatar avatar-sm me-3">
                    <img src="<?php echo $resize->resize(JPATH_CDN .DS. 'images' .DS. 'armas'  .DS. $item->image_arma, 100, 100, 'cache/' . $item->image_arma, 'manterProporcao');?>" alt="Arma <?php echo $item->numero_arma;?>" class="rounded-circle">
                </div>
                <?php endif; ?>
            </div>
            <div class="d-flex flex-column">
                <a href="<?php echo JRoute::_('index.php?view=weapon&cid=' . $item->id_arma); ?>" class="text-body text-truncate">
                    <span class="fw-semibold"><?php echo $item->name_especie; ?></span>
                </a>
                <small class="text-muted"><?php echo 'Calibre: ' . $item->name_calibre; ?></small>
            </div>
        </div>
    </td>
    <td class="text-center d-none d-md-table-cell"><?php echo $item->numero_arma; ?></td>
    <td class="text-center d-none d-md-table-cell"><?php echo $item->name_marca; ?></td>
    <td class="text-center d-none d-md-table-cell"><?php echo $item->name_acervo; ?></td>
    <td class="text-center" width="2%">
        <div class="d-inline-block text-nowrap">
            <input type="checkbox" style="display:none" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $item->id_arma; ?>" >
            <a href="<?php echo JRoute::_('index.php?view=weapon&cid=' . $item->id_arma); ?>" class="btn btn-sm btn-icon me-2"><i class="bx bx-edit bx-sm"></i></a>
            <button type="button" class="btn btn-sm btn-icon delete-record" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja remover esta arma do seu cadastro?<br/><strong><?php echo $item->name_especie . ' número ' .$item->numero_arma; ?></strong>',icon:'warning',showCancelButton: true,confirmButtonColor: '#595cd9',cancelButtonColor: '#ff3e1d',cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('cb<?php echo $i; ?>','remove');}});"><i class="bx bx-trash bx-sm"></i></button>
            <?php /*
            <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu dropdown-menu-end m-0">
                <a href="app-user-view-account.html" class="dropdown-item">Visualizar</a>
                <a href="javascript:;" class="dropdown-item">Suspender</a>
            </div>
            */
            ?>
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