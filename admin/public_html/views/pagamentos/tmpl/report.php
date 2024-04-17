<?php

	defined('_JEXEC') or die('Restricted access'); 
	$config = JFactory::getConfig();
	$siteOffset = $config->getValue('offset'); 
?>
<style>
@page{
	size: portrait;
	}

</style>

<div class="retrato contrato">

<?php 
$r = 0;	
if (count($this->report)>0) :
?>
<table border="1" cellpadding="0" cellspacing="0" width="100%" class="table-title-line retrato" style="font-size:11px;">
    <thead>
        <tr> 
            <th style="text-align:left; border: none;">
                <img src="/admin/views/system/assets/img/logo.png" class="img-responsive"/>
            </th> 
            <th colspan="3" style="text-align:center; border: none;">
                <h2 style="text-align:center; font-size:28px;"><em><strong>Lista para Carteiras Associados</strong></em></h2>
            </th> 
        </tr> 
        <tr> 
            <th class="item-title" width="250px" style="text-align: left;">
                <?php echo  JText::_( 'Nome do Associado' ); ?>
            </th> 
            <th class="item-title" style="text-align: center;">
                <?php echo  JText::_( 'CPF / CNPJ' ); ?>
            </th>
            <th class="item-title" style="text-align: center;">
                <?php echo  JText::_( 'Matrícula' ); ?>
            </th>
            <th class="item-title" width="250px" style="text-align: center;">
                <?php echo  JText::_( 'Clube de Associação' ); ?>
            </th>   
        </tr>
    </thead>
    <tbody>	
<?php
	foreach($this->report as $i => $item):
?>
        
            <tr class="cat-list-row<?php echo $r; ?> " height="32px" valign="middle"> 
                <td align="left" style="text-align:left">	
                	<?php echo $item->name_associado; ?>
                </td>
                <td align="center">
                    <?php echo $item->documanto_associado; ?>
                </td> 
                <td align="center">
                    <?php echo $item->matricula_associado; ?>
                </td> 
                <td align="center">
                    <?php echo $item->subfiliacao_associado; ?>
                </td> 
            </tr>
<?php
			$r++;
			if($r > 1)
				$r = 0;	
?>             


<?php

	endforeach; 
?>
    </tbody>
</table>
<br/>
<span class="pull-right" style="font-size:10px;"> Data da Impressão <?php echo JHtml::date(JFactory::getDate('now', $siteOffset)->toISO8601(), 'DATE_FORMAT', true); ?></span>
<br/>	
<?php /*
<script>window.print();</script>  */ 
endif;?>
</div>
