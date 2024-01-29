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
                <span class="badge bg-label-warning p-3 me-3 ">
                    <?php 
                        $sigla = substr(trim(str_replace(array('Campeonato','Gaúcho','FGCT','de'), array('','','',''), $item->name_campeonato)), 0, 2);
                    ?>
                    <span class="avatar-initial rounded-circle bg-label-danger"><?php echo strtoupper($sigla); ?></span>
                </span>
            </div>
            <div class="d-flex flex-column">
                <a href="<?php echo JRoute::_('index.php?view=weapon&cid=' . $item->id_campeonato); ?>" class="text-body text-truncate">
                    <span class="fw-semibold"><?php echo $item->name_campeonato; ?></span>
                </a>
                <small class="text-muted"><?php echo 'Calibre: ' . $item->name_etapa . ' (' . JHtml::date(JFactory::getDate($item->data_beg_etapa, $siteOffset)->toISO8601(), 'DATE_FORMAT') . ')' ?></small>
            </div>
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center avatar-group">
            <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" aria-label="Vinnie Mostowy" data-bs-original-title="Vinnie Mostowy">
                <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle">
            </div>
            <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" aria-label="Marrie Patty" data-bs-original-title="Marrie Patty">
                <img src="../../assets/img/avatars/12.png" alt="Avatar" class="rounded-circle">
            </div>
            <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" aria-label="Jimmy Jackson" data-bs-original-title="Jimmy Jackson">
                <img src="../../assets/img/avatars/9.png" alt="Avatar" class="rounded-circle">
            </div>
            <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" aria-label="Kristine Gill" data-bs-original-title="Kristine Gill">
                <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle">
            </div>
            <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" aria-label="Nelson Wilson" data-bs-original-title="Nelson Wilson">
                <img src="../../assets/img/avatars/14.png" alt="Avatar" class="rounded-circle">
            </div>
        </div>
    </td>
    <td class="text-center">

    <?php
        $data_ia = JFactory::getDate($item->insc_beg_etapa, $siteOffset)->toISO8601(true);
        $data_fa = JFactory::getDate($item->insc_end_etapa, $siteOffset)->toISO8601(true);
        $data_ha = JFactory::getDate('now', $siteOffset)->toISO8601(true);

        $data_inicioa = new DateTime($data_ia);
        $data_fima = new DateTime($data_fa);
        $data_hojea = new DateTime($data_ha);
        $dateIntervala = $data_inicioa->diff($data_fima);
        $diasa = $dateIntervala->days;
        if($data_ha <= $data_ia)
            $cursadoa = 0;
        elseif($data_fa <= $data_ha)
            $cursadoa = $diasa;
        else{
            $dateInterval1a = $data_inicioa->diff($data_hojea);
            $cursadoa = $dateInterval1a->days;
        }

        if($diasa > 0)
            $progressa = round($cursadoa*100/$diasa);


        ?>

        <div class="d-flex align-items-center gap-3">
            <div class="progress w-100" style="height: 8px;">
                <div class="progress-bar" style="width: <?php echo $progressa; ?>%" aria-valuenow="<?php echo $progressa; ?>%" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <br/>
        </div>
        
        <small class="text-muted">
            <?php echo  JHtml::date(JFactory::getDate($item->insc_beg_etapa, $siteOffset)->toISO8601(), 'DATE_FORMAT_RESUME_2') 
                        . ' até ' .
                        JHtml::date(JFactory::getDate($item->insc_end_etapa, $siteOffset)->toISO8601(), 'DATE_FORMAT_RESUME_2'); 
            ?>
        </small>
    </td>
    <td class="text-center">
                <i class="bx bx-user bx-xs me-2 text-primary"></i>14
            
            <?php /*
            <div class="w-px-50 d-flex align-items-center">
                <i class="bx bx-book-open bx-xs me-2 text-info"></i>48
            </div>
            <div class="w-px-50 d-flex align-items-center">
                <i class="bx bx-video bx-xs me-2 text-danger"></i>43
            </div>
            */ ?>
    </td>

    <td class="text-right">
        <button class="btn btn-sm btn-primary">Inscreva-se</button>
    </td> 
    <?php /*
    <td class="text-center"><?php echo $item->numero_arma; ?></td>
    <td class="text-center"><?php echo $item->name_marca; ?></td>
    <td class="text-center"><?php echo $item->name_acervo; ?></td>
    <td>
        <div class="d-inline-block text-nowrap">
            <input type="checkbox" style="display:none" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $item->id_arma; ?>" >
            <a href="<?php echo JRoute::_('index.php?view=weapon&cid=' . $item->id_arma); ?>" class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>
            <button type="button" class="btn btn-sm btn-icon delete-record" onclick="Swal.fire({title:'Atenção',html:'Você tem certeza que deseja remover esta arma do seu cadastro?<br/><strong><?php echo $item->name_especie . ' número ' .$item->numero_arma; ?></strong>',icon:'warning',showCancelButton: true,confirmButtonColor: '#595cd9',cancelButtonColor: '#ff3e1d',cancelButtonText: 'Desistir',confirmButtonText: 'Confirmar!'}).then((result) => {if (result.isConfirmed) { return listItemTask('cb<?php echo $i; ?>','remove');}});"><i class="bx bx-trash"></i></button>
            
            <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
            <div class="dropdown-menu dropdown-menu-end m-0">
                <a href="app-user-view-account.html" class="dropdown-item">Visualizar</a>
                <a href="javascript:;" class="dropdown-item">Suspender</a>
            </div>
           
        </div>
    </td> 
    */
     ?>
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