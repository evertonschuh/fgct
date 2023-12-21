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
<h4 style="text-align:center;">Relatório de Pagamentos (<?php echo JHtml::date(JFactory::getDate('now', $siteOffset)->toISO8601(), 'DATE_FORMAT', true); ?>)</h4>
<?php
	$total = 0;
	$totalValor = 0;
	$totalDesconto = 0;
	$totalValorPago = 0;
	$totalCheioOuDesconto = 0;
	$totalRelatorio = 0;
	$totalRelatorioValor = 0;
	$totalRelatorioDesconto = 0;
	$totalRelatorioValorPago = 0;
	$totalRelatorioCheioOuDesconto = 0;
	
	foreach($this->report as $i => $item):
		if($i == 0 || JFactory::getDate($this->report[$i -1]->vencimento_pagamento)->toFormat('%m',true) !=  JFactory::getDate($item->vencimento_pagamento)->toFormat('%m',true)) :
?>
	
    <table border="1" cellpadding="0" cellspacing="0" width="100%" class="table-title-line retrato">
        <thead>
            <tr> 
                <th colspan="6" style="text-align: left;">
                    <strong><?php echo JHtml::date(JFactory::getDate($item->vencimento_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT_RESUME_LC', true); ?></strong>
                </th>
            </tr>	
            <tr> 
                <th class="item-title"  style="text-align: center;">
                    <?php echo  JText::_( 'Dia' ); ?>
                </th> 
                <th class="item-title" style="text-align: center;">
                    <?php echo  JText::_( 'Quantidade' ); ?>
                </th> 
                <th class="item-title"  style="text-align: center;">
                    <?php echo  JText::_( 'Cheio' ); ?>
                </th> 
                <th class="item-title"  style="text-align: center;">
                    <?php echo  JText::_( 'Desconto' ); ?>
                </th> 
                <th class="item-title"  style="text-align: center;">
                    <?php echo  JText::_( ' Cheio ou Desconto' ); ?>
                </th> 
                <th class="item-title" style="text-align: center;">
                    <?php echo  JText::_( 'Pago' ); ?>
                </th> 
            </tr>
        </thead>
        <tbody>	
<?php	endif; ?>

            <tr class="cat-list-row<?php echo $r; ?> "> 
                <td align="center" >	
                    <?php echo JHtml::date(JFactory::getDate($item->vencimento_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT', true); ?></strong>
                </td>
                <td align="center" >	
                	<?php echo $item->total_dia; ?>
                </td>
                <td align="center">
                    <?php echo $item->valor_pagamento > 0 ?  number_format($item->valor_pagamento, 2, ',', '.') : '-' ; ?>
                </td>                    
                <td align="center">
                    <?php echo $item->valor_desconto_pagamento > 0 ?  number_format($item->valor_desconto_pagamento, 2, ',', '.') : '-' ; ?>
                </td>     
                <td align="center">
                    <?php echo $item->valor_cheio_desconto_pagamento > 0  ? number_format($item->valor_cheio_desconto_pagamento, 2, ',', '.') : '-' ; ?>
                </td>            
                <td align="center">
                    <?php echo  $item->valorpago_pagamento > 0 ?  number_format($item->valorpago_pagamento, 2, ',', '.') : '-' ; ?>
                </td>  
             
            </tr>
<?php
			$total += $item->total_dia;
			$totalValor += $item->valor_pagamento;
			$totalDesconto += $item->valor_desconto_pagamento;
			$totalValorPago += $item->valorpago_pagamento;
			$totalCheioOuDesconto += $item->valor_cheio_desconto_pagamento;
			
			$r++;
			if($r > 1)
				$r = 0;	
				
		if($i == count($this->report) - 1 || JFactory::getDate($this->report[$i +1]->vencimento_pagamento)->toFormat('%m',true) != JFactory::getDate($item->vencimento_pagamento)->toFormat('%m',true) ) :
?>             
        <tr> 
            <td align="right"  style="text-align: right;">
            	<strong>Totais:</strong>
            </td>
            <td>
            	<strong><?php echo $total; ?></strong>
            </td> 
            <td>
            	<strong><?php echo number_format($totalValor, 2, ',', '.'); ?></strong>
            </td>  
            <td>
            	<strong><?php echo number_format($totalDesconto, 2, ',', '.'); ?></strong>
            </td> 
            <td>
            	<strong><?php echo number_format($totalCheioOuDesconto, 2, ',', '.'); ?></strong>
            </td>                  
            <td>
            	<strong><?php echo number_format($totalValorPago, 2, ',', '.'); ?></strong>
            </td>
        </tr>	
    </tbody>
</table>
<br/>
<br/>	
<?php
			$totalRelatorio += $total;
			$totalRelatorioValor += $totalValor;
			$totalRelatorioDesconto += $totalDesconto;
			$totalRelatorioCheioOuDesconto +=  $totalCheioOuDesconto;
			$totalRelatorioValorPago += $totalValorPago;

			$total = 0;
			$totalValor = 0;
			$totalDesconto = 0;
			$totalValorPago = 0;
			$totalCheioOuDesconto = 0;
		endif;
	endforeach; 
?>
<table border="1" cellpadding="0" cellspacing="0" width="100%" class="table-title-line retrato">
    <thead>
        <tr> 
            <th colspan="5" style="text-align: center;">
                <strong>Totais do Relatório:</strong>
            </th>
        </tr>	
        <tr> 
            <th class="item-title" style="text-align: center;">
                <?php echo  JText::_( 'Quantidades' ); ?>
            </th> 
            <th class="item-title" style="text-align: center;">
                <?php echo  JText::_( 'Valores Cheios' ); ?>
            </th> 
            <th class="item-title" style="text-align: center;">
                <?php echo  JText::_( 'Valores com Desconto' ); ?>
            </th> 
            <th class="item-title" style="text-align: center;">
                <?php echo  JText::_( 'Valores Cheios Ou Descontos' ); ?>
            </th>             
            <th class="item-title" style="text-align: center;">
                <?php echo  JText::_( 'Valores Pagos' ); ?>
            </th> 
        </tr>
    </thead>
    <tbody>
        <tr> 
            <td>
            	<strong><?php echo number_format($totalRelatorio, 2, ',', '.'); ?></strong>
            </td>  

            <td>
            	<strong><?php echo number_format($totalRelatorioValor, 2, ',', '.'); ?></strong>
            </td>  
            <td>
            	<strong><?php echo number_format($totalRelatorioDesconto, 2, ',', '.'); ?></strong>
            </td>   

            <td>
            	<strong><?php echo number_format($totalRelatorioCheioOuDesconto, 2, ',', '.'); ?></strong>
            </td>       
            <td>
            	<strong><?php echo number_format($totalRelatorioValorPago, 2, ',', '.'); ?></strong>
            </td>        
        </tr>	
    </tbody>
</table>

<script>window.print();</script>   
<?php endif;?>
</div>
