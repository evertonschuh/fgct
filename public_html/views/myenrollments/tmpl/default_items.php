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
                        $name = explode(' ', trim($item->name_prova));
                        $sigla = substr($name[0], 0, 1) . substr(end($name), 0, 1);
                        //$sigla = substr(trim(str_replace(array('Campeonato','Gaúcho','FGCT','de'), array('','','',''), $item->name_campeonato)), 0, 2);
                    ?>
                    <span class="avatar-initial rounded-circle bg-label-danger"><?php echo strtoupper($sigla); ?></span>
                </span>
            </div>
            <div class="d-flex flex-column">
                <span class="text-body text-truncate">
                    <span class="fw-semibold" style="font-size:13px"><?php echo  $item->name_etapa; ?></span>
                </span>
                <small class="text-break" style="font-size:12px"><?php echo  $item->name_prova; ?>.</small>
                <small class="text-muted" style="font-size:10px"><?php echo  'Etapa Inicia em ' . JHtml::date(JFactory::getDate($item->data_beg_etapa, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?></small>
            </div>
        </div>
    </td>
    <td class="text-center d-none d-md-table-cell"> <?php echo  JHtml::date(JFactory::getDate($item->date_register_inscricao_etapa, $siteOffset)->toISO8601(), 'DATE_FORMAT_DATATIME'); ?></td>
    <td class="text-center d-none d-md-table-cell">
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
    <td class="text-center d-none d-md-table-cell">
        <small class="text-body text-truncate">
            <?php echo 'Categoria: ' . $item->name_categoria . '<br/> Classe: ' . $item->name_classe; ?>
        </small>
        <br/>
        <small class="text-muted"><?php echo  $item->name_genero; ?></small>
    </td>
    
    <td class="text-center d-none d-md-table-cell">
        <span class="text-body text-truncate">
            <?php echo  $item->name_especie . ' ' . $item->name_calibre; ?>
        </span>
        <br/>
        <small class="text-muted text-center"><?php echo  $item->name_marca; ?></small>
    </td> 
    <td class="text-center">
        <a href="<?php echo JRoute::_('index.php?view=myenrollment&format=pdf&cid=' . $item->id_inscricao_etapa); ?>" class="btn btn-sm btn-icon" target="_blank"><i class='bx bx-printer bx-sm'></i></a>
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