<?php

defined('_JEXEC') or die('Restricted access'); 
$_app	 = JFactory::getApplication(); 

$config = JFactory::getConfig();
$_siteOffset = $config->get('offset'); 
?>
<style>
@page {margin: 0; size: portrait;}

body {
    font-family:"Public Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    margin: 0;
    padding: 0;
}
* {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}
.img-logo{
	position:absolute; 
	left:50px;
	top: 40px;
}
.img-qrcode{
	position:absolute; 
	right:150px; 
	bottom: 95px;
}

.box-autenticate {
	font-family: 'courier';
	position:absolute; 
	right:160px; 
	bottom: 37px;
    width:200px;
}

.autenticate {
	font-family: 'courier';
	position:absolute; 
	right:160px; 
	bottom: 64px;
}
.autenticate-code {
	font-family: 'courier';
	position:absolute; 
	right:160px; 
	bottom: 37px;
}
strong {
	font-weight:bold !important;

}
.page1, .page {
	background-repeat: no-repeat;
	background-position: center top;
}
.documento{
	padding: 40px 60px;
	/*margin-top: 120px;*/
}
p{margin: 5px 0;}
.page {margin: 0; padding:0 ;height:27cm; position:absolute}  
</style>


<div class="page1 page">
	<div class="paisagem documento">

        <table width="680px" border="1">
            <tr valign="bottom">
                <td align="left" rowspan="2">
                    <?php
                        $path = JPATH_BASE .DS. 'images'.DS.'logo-ficha.png';
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    ?>
                    <img src="<?php echo $base64; ?>" style="margin:0 0 10px 0"/>
                </td>

                <td align="right">
                    <h3 style="padding:0;margin:10px 0 0 0">
                        <strong>FEDERAÇÃO GAÚCHA DE CAÇA E TIRO</strong>
                    </h3>   
                </td>
            </tr>
            <tr valign="bottom">
                <td align="right">
                    <em style="font-size:10px;line-height:10px;padding:0;margin:0">
                        <?php echo 'Registro de Inscrição em: ' . JHTML::_('date', JFactory::getDate($this->item->date_register_inscricao_etapa, $_siteOffset)->toISO8601(true), 'DATE_FORMAT_LC2'); ?>
                    </em>
                </td>
            </tr>
        </table>
        
        <table width="680px" cellspacing="0" cellpadding="0" bgcolor="#467119" style="color:#FFFFFF;">
            <tr>
                <td align="center">
                    <h4 style="line-height: 25px; padding:0;margin:5px 0">
                        <strong>
                            <?php echo JText::_('COMPROVANTE DE INSCRIÇÃO ONLINE'); ?>
                        </strong>
                    </h4>   
                </td>
            </tr>
        </table>
        <div class="divider text-start">
                        <div class="divider-text">Start</div>
                      </div>
        <div class="width-100 retrato" style="border: 1px solid; margin:5px; padding:5px">
    <div class="width-100 fltlft text-center font-20 margin-top-175 margin-bottom-20">
    	  <strong><?php echo JText::_('FEDERAÇÃO GAÚCHA DE CAÇA E TIRO'); ?></strong>
    </div>
    <img class="img-responsive" src="cache/<?php echo $this->item->id_inscricao_etapa . '-comporvante.png' ?>" />
    <div class="width-100 fltlft text-center font-18 margin-top-175 margin-bottom-20">
        <strong><?php echo JText::_('Comprovante de Inscrição Online'); ?></strong>
    </div>
    <div class="width-100 fltlft text-center font-16 margin-top-175 margin-bottom-20">
        <?php echo $this->item->name_campeonato; ?>
    </div>
    <div class="width-100 fltlft text-center font-14 margin-top-175 margin-bottom-20">
        <?php echo $this->item->name_etapa . ' - ' . $this->item->name_clube; ?>
    </div>  
    <div class="width-100 fltlft text-center font-14 margin-top-175 margin-bottom-20">
        <?php echo $this->item->logradouro_pj.', '.$this->item->numero_pj.' - '.$this->item->name_cidade.'/'.$this->item->sigla_estado;?>
    </div> 
	<br/>
    <div class="width-100 fltlft font-14">    
        <table width="100%" border="0">
            <tr>
                <td width="65"><strong>Atleta:</strong></td>
                <td colspan="5"><?php echo $this->item->name_atleta; ?></td>
                <td rowspan="4" valign="top">
                    <?php
                    if(!JFile::exists(JPATH_BASE . DS . 'cache' . DS .  $this->item->id_inscricao_etapa . '-comporvante.png')){
                        QRcode::png(  $this->item->id_inscricao_etapa, JPATH_BASE . DS . 'cache' . DS . $this->item->id_inscricao_etapa . '-comporvante.png', 'L', 4, 2);
                        echo JPATH_BASE . DS . 'cache' . DS .  $this->item->id_inscricao_etapa . '-comporvante.png';
                    }    
                   ?>
                    <img class="img-responsive" src="/cache/<?php echo $this->item->id_inscricao_etapa . '-comporvante.png' ?>" />
                </td>
            </tr>
            <tr>
                <td><strong>Prova:</strong></td>
                <td colspan="5"><?php echo $this->item->name_prova; ?></td>
            </tr>
            <tr>
                <td><strong>Equipe:</strong></td>
                <td colspan="5"><?php echo $this->item->name_equipe; ?></td>
            </tr>
            <tr>
                <td><strong>Arma:</strong></td>
                <td colspan="5">
                    <table>
                        <tr>
                            <td style="padding-right:5px"><strong>Número:</strong></td>
                            <td style="padding-right:10px"><?php echo $this->item->numero_arma; ?></td>
                            <td style="padding-right:5px"><strong>Tipo:</strong></td>
                            <td style="padding-right:10px"><?php echo $this->item->name_especie; ?></td>
                            <td style="padding-right:5px"><strong>Calibre:</strong></td>
                            <td style="padding-right:10px"><?php echo $this->item->name_calibre; ?></td>
                            <td style="padding-right:5px"><strong>Marca:</strong></td>
                            <td><?php echo $this->item->name_marca; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td><strong>Gênero:</strong></td>
                <td width="120"><?php echo $this->item->name_genero; ?></td>
                <td width="80"><strong>Categoria:</strong></td>
                <td width="120"><?php echo $this->item->name_categoria; ?></td>
                <td width="65"><strong>Classe:</strong></td>
                <td width="110"><?php echo $this->item->name_classe; ?></td>
            </tr>
        </table> 
        <br/>
		<?php /*
		$registry = new JRegistry;
		$registry->loadString($this->item->params_inscricao_etapa);
		$params_inscricao_etapa = $registry->toArray();
		if(!empty($params_inscricao_etapa) || !empty($this->additionalPrint)):
		?>	
        <table width="100%" border="0" >
            <tr>
                <td class="font-14 text-center" ><strong>Informações</strong></td>
			</tr> 
			<?php
				if(!empty($params_inscricao_etapa)):
                    echo '<tr><td class="font-14 text-center"><strong>Provas:</strong><br/>' . strtoupper (implode(', ', $params_inscricao_etapa)) . '</td></tr>';
                endif;

                if(!empty($this->additionalPrint)):
                    echo '<tr><td class="font-14 text-center">'.$this->additionalPrint. '</td></tr>';
                endif;
            ?>    	
        </table>   
        <br/>
        <?php endif; ?>
		<?php if(count($this->agendamentosPrint)>0): ?>  
        <table width="100%" border="0" >
            <tr>
                <th colspan="5" class="font-14 text-center" style=" padding-bottom:10px;"><strong>Agendamentos</strong></th>
            </tr>   
            <tr>
                <td class="text-center"><strong>Nr</strong></td>  
                <td class="text-center"><strong>Data</strong></td>
                <?php if($this->item->inscricao_bateria_prova>1): ?>
                <td class="text-center"><strong><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'BATERIA') ?></strong></td>
                <?php endif; ?>
                <?php if($this->item->inscricao_turma_prova>1): ?>
                <td class="text-center"><strong><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'TURMA') ?></strong></td>
                <?php endif; ?>
                <td class="text-center"><strong><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'POSTO') ?></strong></td>
            </tr>
            <?php foreach($this->agendamentosPrint as $i => $agendamento):?>
            <tr>
            	<td class="text-center"><?php echo $i + 1; ?></td>  
                <td class="text-center"><?php echo JHTML::_('date', JFactory::getDate($agendamento->date_inscricao_agenda, $_siteOffset)->toISO8601(true), 'DATE_FORMAT_LC3'); ?> </td>
                <?php if($this->item->inscricao_bateria_prova>1): ?>
                <td class="text-center"><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'BATERIA') . ' ' . $agendamento->bateria_inscricao_agenda; ?></td>
                <?php endif; ?>
                <?php if($this->item->inscricao_turma_prova>1): ?>
                	<?php
					$agendamento_text = $agendamento->turma_inscricao_agenda;
					if($this->item->id_prova == 596 ):
                        $agendamento_text = $agendamento->turma_inscricao_agenda + (ceil($agendamento->turma_inscricao_agenda/8)-1)*2;
						//$agendamento_text = $agendamento->turma_inscricao_agenda + (ceil($agendamento->turma_inscricao_agenda/6)-1)*4;
						//$agendamento_text = $agendamento->turma_inscricao_agenda + (ceil($agendamento->turma_inscricao_agenda/9)-1)*1;
                    endif;
                	?>
                
                <td class="text-center"><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'TURMA') . ' ' . $agendamento_text; ?></td>
                <?php endif; ?> 
                <td class="text-center"><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'POSTO') . ' ' . $agendamento->posto_inscricao_agenda; ?></td>
            </tr>
            <?php endforeach;?>
        </table>
        <br/>    
    	<?php endif; */?>
		<div class="width-100 fltrht font-10" style="float:right; font-size:11px; margin-right:10px">    
            <em>
                <?php echo 'Registro de Inscrição em: ' . JHTML::_('date', JFactory::getDate($this->item->date_register_inscricao_etapa, $_siteOffset)->toISO8601(true), 'DATE_FORMAT_LC2'); ?>
            </em>
           
    	</div>      
 		<br/>
    </div>    
</div>      







	</div>
</div>

