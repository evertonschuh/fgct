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

<div class="contrato" style="margin: 0 auto; max-width:840px;">

<?php 
$r = 0;	
if (count($this->etiqueta)>0) :
?>
<table cellpadding="0" cellspacing="0" width="100%" class="" style="font-size:11px; line-height:16px; max-width:840px; ">
    <thead>
        <tr> 
            <th colspan="3" style="padding-top:20px;">&nbsp;</th> 
        </tr>
    </thead> 
    <tbody>	
<?php 
	$x = 0;
	foreach($this->etiqueta as $i => $item): ?>
		
	<?php 
		switch ($x):
 			case 0: 
				$x++;
	?>
    	<tr class="cat-list-row<?php echo $r; ?> " height="96px" valign="middle"> 
            <td align="left" width="280px" style="padding-left:20px">
                <strong><?php echo $item->name; ?></strong><br/>
                <?php echo $item->logradouro . ' ' . $item->numero; ?><br/>
                <?php echo (!empty($item->complemento) ? $item->complemento . ' - ' : '') . $item->bairro; ?><br/>
                <?php echo $item->cep; ?><br/>
                <?php echo $item->name_cidade . ' - ' . $item->sigla_estado; ?><br/>
            </td>
	<?php 	break;
			case 1:
				$x++;
	?>
            <td align="left" width="280px" style="padding-left:20px">
                <strong><?php echo $item->name; ?></strong><br/>
                <?php echo $item->logradouro . ' ' . $item->numero; ?><br/>
                <?php echo (!empty($item->complemento) ? $item->complemento . ' - ' : '') . $item->bairro; ?><br/>
                <?php echo $item->cep; ?><br/>
                <?php echo $item->name_cidade . ' - ' . $item->sigla_estado; ?><br/>
            </td>
	<?php 	break;
			case 2: 
				$x = 0
	?>
            <td align="left" width="280px" style="padding-left:20px">
                <strong><?php echo $item->name; ?></strong><br/>
                <?php echo $item->logradouro . ' ' . $item->numero; ?><br/>
                <?php echo (!empty($item->complemento) ? $item->complemento . ' - ' : '') . $item->bairro; ?><br/>
                <?php echo $item->cep; ?><br/>
                <?php echo $item->name_cidade . ' - ' . $item->sigla_estado; ?><br/>
            </td>
  		</tr>    
	<?php 	break;
		endswitch;
	
		if(count($this->etiqueta)-1 == $i && $x>0):
			for($m=$x; $m>=0 ;$m--):
				echo '<td align="center"></td>';
			endfor;
			echo '</tr>';
		endif;
?>
      
<?php
	endforeach; 
?>
    </tbody>
</table>
<?php /*
<script>window.print();</script>  */ 
endif;?>
</div>
