<?php

defined('_JEXEC') or die('Restricted access');

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
jimport('joomla.image.resize');
$resize = new JResize();

if (count($this->items)) {
    foreach ($this->items as $i => $item) {

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



<tr class="odd">
    <td class="  control" tabindex="0" style="display: none;"></td>
    <td class="sorting_1">
        <div class="d-flex justify-content-start align-items-center user-name">
            <div class="avatar-wrapper">
                <?php if(empty($item->image_pf)): ?>
                <div class="avatar avatar-sm me-3">
                    <?php 
                        $name = explode(' ', $item->name);
                        $sigla = substr($name[0], 0, 1) . substr(end($name), 0, 1);
                    ?>
                    <span class="avatar-initial rounded-circle bg-label-danger"><?php echo strtoupper($sigla); ?></span>
                </div>
                <?php else: ?>
                <div class="avatar avatar-sm me-3">
                    <img src="<?php echo $resize->resize(JPATH_CDN .DS. 'images' .DS. 'avatar'  .DS. $item->image_pf, 100, 100, 'cache/' . $item->image_pf, 'manterProporcao');?>" alt="Avatar" class="rounded-circle">
                </div>
                <?php endif; ?>
            </div>
            <div class="d-flex flex-column">
                <a href="<?php echo JRoute::_('index.php?view=associado&cid=' . $item->id_associado); ?>" class="text-body text-truncate">
                    <span class="fw-semibold"><?php echo $item->name; ?></span>
                </a>
                <small class="text-muted"><?php echo $item->name_cidade .'/'.$item->sigla_estado; ?></small>
            </div>
        </div>
    </td>
    <td>
        <?php 
            if($item->tipo == 1): 
                $name_type = 'Clube/Associação';
                $color_type = 'primary';
                $icon_type = 'bxs-business';
            elseif($item->compressed_air_pf == '0' && $item->copa_brasil_pf == '0'):
                $name_type = 'Sócio Padrão';
                $icon_type = 'bx-user';
                $color_type = 'success';
            elseif($item->compressed_air_pf == '1' && $item->copa_brasil_pf == '0'):
                $name_type = 'Ar Comprimido';
                $color_type = 'warning';
                $icon_type = 'bxs-spray-can';

            elseif($item->compressed_air_pf == '0' && $item->copa_brasil_pf == '1'):
                $name_type = 'Copa Brasil';
                $color_type = 'danger';
                $icon_type = 'bxs-trophy';
            endif; 
        ?>


        <a class="jgrid grid-icon" title="<?php echo $name_type; ?>">
            <span class="text-truncate d-flex align-items-center">
                <span class="badge badge-center rounded-pill bg-label-<?php echo $color_type; ?> w-px-30 h-px-30 me-2">
                    <i class="bx <?php echo $icon_type; ?> bx-xs"></i>
                </span>
            </span>
        </a>
    </td>
    <td>
        <span class="fw-semibold"><?php echo $item->doc; ?></span>
    </td>
    <td class="text-center"><?php echo !empty($item->validate_associado) ? JFactory::getDate($item->validate_associado, $siteOffset)->toFormat('%Y', true) : '-'; ?></td>
    <td class="text-center">



    
        <?php 

        $status = '<span class="badge bg-label-success">Ativo</span>';
        if($item->status_associado==1):
            if(is_null($item->validate_associado)):
                $status = '<span class="badge bg-label-info">Novo</span>';;
            elseif( JFactory::getDate('now -1 year', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->validate_associado, $siteOffset)->toFormat('%Y-%m-%d', true) ):
                $status = '<span class="badge bg-label-danger">Abandono</span>';
            elseif( JFactory::getDate('now', $siteOffset)->toFormat('%Y-%m-%d', true) > JFactory::getDate($item->validate_associado, $siteOffset)->toFormat('%Y-%m-%d', true) ):
                $status = '<span class="badge bg-label-warning">Vencido</span>';
            endif;
        else:
            $status = '<span class="badge bg-label-secondary">Inativo</span>';
        endif;
        echo $status;
       
        ?>
        
    </td>
    <td>
        <div class="d-inline-block text-nowrap">
            <input type="checkbox" style="display:none" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $item->id_associado; ?>" >
            <a href="<?php echo JRoute::_('index.php?view=associado&cid=' . $item->id_associado); ?>" class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>
            <button type="button" class="btn btn-sm btn-icon delete-record" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja remover este associado?<br/><strong><?php echo $item->name; ?></strong>',icon:'warning',showCancelButton: true,confirmButtonColor: '#595cd9',cancelButtonColor: '#ff3e1d',cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('cb<?php echo $i; ?>','remove');}});"><i class="bx bx-trash"></i></button>
            <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu dropdown-menu-end m-0">
                <a href="app-user-view-account.html" class="dropdown-item">Visualizar</a>
                <a href="javascript:;" class="dropdown-item">Suspender</a>
            </div>
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