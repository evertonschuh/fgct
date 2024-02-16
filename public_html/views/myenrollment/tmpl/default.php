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

table tr.title { }
</style>


<div class="page1 page">
	<div class="paisagem documento">

        <table width="680px" heigth="100px">
            <tr valign="bottom">
                <td align="left" style="line-height: 14px;">
                    &nbsp;
                </td>
                <td align="right" rowspan="3">
                    <?php
                        $path = JPATH_BASE .DS. 'images'.DS.'logo-ficha.png';
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    ?>
                    <img src="<?php echo $base64; ?>" style="margin:0 0 5px 0; padding:0"/>
                </td>
            </tr>
            <tr valign="bottom">
                <h3 style="padding:0;margin:10px 0 0 0;line-height: 10px;">
                    <strong>FEDERAÇÃO GAÚCHA DE CAÇA E TIRO</strong>
                </h3>  
            </tr>
            <tr valign="bottom">
                <td align="left">
                    <em style="font-size:10px;line-height:10px;padding:0;margin:0">
                        <?php echo 'Data do Registro de Inscrição: ' . JHTML::_('date', JFactory::getDate($this->item->date_register_inscricao_etapa, $_siteOffset)->toISO8601(true), 'DATE_FORMAT_LC2'); ?>
                    </em>
                </td>
            </tr>
        </table>
        
        <table width="680px" cellspacing="0" cellpadding="0" bgcolor="#467119" style="color:#FFFFFF;">
            <tr valign="center">
                <td align="center">
                    <h4 style="line-height: 18px; padding:0;margin:5px 0">
                        <strong>
                            <?php echo JText::_('COMPROVANTE DE INSCRIÇÃO ONLINE'); ?>
                        </strong>
                    </h4>   
                </td>
            </tr>
        </table>  
        <br/>      
        <table width="680px" cellspacing="0" cellpadding="0">
            <tr valign="center">
                <td align="left">
                    <h6 style="line-height: 12px; padding:0;margin:0px">
                        <strong>
                            Dados da Inscrição
                        </strong>
                    </h6>   
                    <hr/>
                </td>
            </tr>
        </table>
        <table width="680px" cellspacing="0" cellpadding="0" style="font-size:12px">
            <tr valign="center">
                <td align="left" width="45px">
                    <strong>Atleta:</strong>
                </td>
                <td align="left" width="325px">
                    <?php echo $this->item->name_atleta; ?>
                </td>
                <td align="left" width="35px">
                    <strong>CPF:</strong>
                </td>
                <td align="left" width="130px">
                <?php echo $this->item->cpf_atleta; ?>
                </td>
                <td align="left" width="100px">
                    <strong>Matrícula FGCT:</strong>
                </td>
                <td align="right" width="35px">
                <?php echo $this->item->id_associado; ?>
                </td>
            </tr>
        </table>   
         
        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:5px;font-size:12px">
            <tr valign="center">
                <td align="left" width="125px">
                    <strong>Número da Inscrição:</strong>
                </td>
                <td align="left">
                    <?php echo $this->item->id_inscricao_etapa; ?>
                </td>
                <td align="left" width="65px">
                    <strong>Categoria:</strong>
                </td>
                <td align="left">
                    <?php echo $this->item->name_categoria; ?>
                </td>
                <td align="left" width="50px">
                    <strong>Classe:</strong>
                </td>
                <td align="left">
                    <?php echo $this->item->name_classe; ?>
                </td>
                <td align="left" width="55px">
                    <strong>Genero:</strong>
                </td>
                <td align="right" width="5%">
                    <?php echo $this->item->name_genero; ?>
                </td>
            </tr>
        </table>  
        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:5px;font-size:12px">
            <tr valign="center">
                <td align="left" width="50px">
                    <strong>Equipe:</strong>
                </td>
                <td align="left" width="90%">
                    <?php echo $this->item->name_equipe; ?>
                </td>
                <td align="left" width="90px">
                    <strong>Tipo de Sócio:</strong>
                </td>
                <td align="right" nowrap>
                    <?php if($this->item->compressed_air_pf == '0' && $this->item->copa_brasil_pf == '0'): ?>
                    Completo
                    <?php elseif($this->item->compressed_air_pf == '1' && $this->item->copa_brasil_pf == '0'): ?>
                    Ar Comprimido
                    <?php elseif($this->item->compressed_air_pf == '0' && $this->item->copa_brasil_pf == '1'): ?>
                    Copa Brasil
                    <?php endif; ?>
                </td>
            </tr>
        </table> 
          
        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:5px;font-size:12px">
            <tr valign="center">
                <td align="left" width="40px">
                    <strong>Local:</strong>
                </td>
                <td align="left" width="70%">
                    <?php echo $this->item->name_clube; ?>
                </td>
                <td align="left" width="70px">
                    <strong>Endereço:</strong>
                </td>
                <td align="right" nowrap>
                    <?php echo $this->item->logradouro_pj.', '.$this->item->numero_pj.' - '.$this->item->name_cidade.' / '.$this->item->sigla_estado;?>
                </td>
            </tr>
        </table> 
        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:25px">
            <tr valign="center">
                <td align="left">
                    <h6 style="line-height: 12px; padding:0;margin:0px">
                        <strong>
                            Informações da Competição
                        </strong>
                    </h6>   
                    <hr/>
                </td>
            </tr>
        </table>
        <table width="680px" cellspacing="0" cellpadding="0" style="font-size:12px">
            <tr valign="center">
                <td align="left" width="85px">
                    <strong>Campeonato:</strong>
                </td>
                <td align="left" width="70%">
                    <?php echo $this->item->name_campeonato; ?>
                </td>
                <td align="left" width="80px">
                    <strong>Modalidade:</strong>
                </td>
                <td align="right" nowrap>
                <?php echo $this->item->name_modalidade; ?>
                </td>
            </tr>
        </table> 
        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:5px;font-size:12px">
            <tr valign="center">
                <td align="left" width="45px">
                    <strong>Prova:</strong>
                </td>
                <td align="left">
                    <?php echo $this->item->name_prova; ?>
                </td>
                <td align="left" width="45px">
                    <strong>Etapa:</strong>
                </td>
                <td align="left">
                    <?php echo $this->item->name_etapa; ?>
                </td>
                <td align="left" width="140px">
                    <strong>Período da Realização:</strong>
                </td>
                <td align="right"  width="148px">
                    <?php echo JHTML::_('date', JFactory::getDate($this->item->data_beg_etapa, $_siteOffset)->toISO8601(true), 'DATE_FORMAT') .' até ' . JHTML::_('date', JFactory::getDate($this->item->data_end_etapa, $_siteOffset)->toISO8601(true), 'DATE_FORMAT'); ?>
                </td>
            </tr>
        </table>  

        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:25px">
            <tr valign="center">
                <td align="left">
                    <h6 style="line-height: 12px; padding:0;margin:0px">
                        <strong>
                            Arma(s) do Atleta
                        </strong>
                    </h6>   
                    <hr/>
                </td>
            </tr>
        </table>
        <table width="680px" cellspacing="0" cellpadding="0" style="font-size:12px">
            <tr valign="center">
                <td>
                    <table width="680px" cellspacing="0" cellpadding="0" border="1" style="font-size:12px">
                        <tr valign="center">
                            <th>Registrador</th>                        
                            <th>Registro</th>
                            <th>Número</th>
                            <th>Espécie</th>
                            <th>Calibre</th>
                            <th>Marca</th>
                        </tr>
                        <tr valign="center">
                            <td align="center"><?php echo $this->item->registro_name_arma; ?></td>
                            <td align="center"><?php echo $this->item->registro_arma; ?></td>
                            <td align="center"><?php echo $this->item->numero_arma; ?></td>
                            <td align="center"><?php echo $this->item->name_especie; ?></td>
                            <td align="center"><?php echo $this->item->name_calibre; ?></td>
                            <td align="center"><?php echo $this->item->name_marca; ?></td>
                        </tr>
                    </table> 
                </td>
            </tr>
        </table> 


        <?php if(count($this->agendamentosPrint)>0): ?>  

        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:25px">
            <tr valign="center">
                <td align="left">
                    <h6 style="line-height: 12px; padding:0;margin:0px">
                        <strong>
                            Agendamento(s) de Tiro
                        </strong>
                    </h6>   
                    <hr/>
                </td>
            </tr>
        </table>

        <table width="680px" cellspacing="0" cellpadding="0" style="font-size:12px">
            <tr valign="center">
                <td>
                    <table width="680px" cellspacing="0" cellpadding="0" border="1" style="font-size:12px">
                        <tr valign="center">
                            <th>Nr</th>                        
                            <th>Data</th>
                            <?php if($this->item->inscricao_bateria_prova>1): ?>
                            <th><strong><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'BATERIA') ?></th>
                            <?php endif; ?>
                            <?php if($this->item->inscricao_turma_prova>1): ?>
                            <th><strong><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'TURMA') ?></th>
                            <?php endif; ?>
                            <th><strong><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'POSTO') ?></th>
                        </tr>
                        <?php foreach($this->agendamentosPrint as $i => $agendamento):?>
                        <tr>
                            <td align="center"><?php echo $i + 1; ?></td>  
                            <td align="center"><?php echo JHTML::_('date', JFactory::getDate($agendamento->date_inscricao_agenda, $_siteOffset)->toISO8601(true), 'DATE_FORMAT_LC3'); ?> </td>
                            <?php if($this->item->inscricao_bateria_prova>1): ?>
                            <td align="center"><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'BATERIA') . ' ' . $agendamento->bateria_inscricao_agenda; ?></td>
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
                            <td align="center"><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'TURMA') . ' ' . $agendamento_text; ?></td>
                            <?php endif; ?> 
                            <td align="center"><?php echo JText::_('TORNEIOS_VIEWS_INSCRICOES_AGENDAMENTO_'.$this->tagLanguage.'POSTO') . ' ' . $agendamento->posto_inscricao_agenda; ?></td>
                        </tr>
                        <?php endforeach;?>
                    </table> 
                </td>
            </tr>
        </table> 


    	<?php endif;?>




        <?php
        $registry = new JRegistry;
		$registry->loadString($this->item->params_inscricao_etapa);
		$params_inscricao_etapa = $registry->toArray();
		if(!empty($params_inscricao_etapa) || !empty($this->additionalPrint)):?>	

        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:25px">
            <tr valign="center">
                <td align="left">
                    <h6 style="line-height: 12px; padding:0;margin:0px">
                        <strong>
                            Outras Informações
                        </strong>
                    </h6>   
                    <hr/>
                </td>
            </tr>
        </table>
        <table width="680px" cellspacing="0" cellpadding="0" style="font-size:12px">
            <?php if(!empty($this->additionalPrint)): ?>
            <tr valign="center">
                <td><?php echo $this->additionalPrint; ?></td>
            </tr>
            <?php endif; ?>
            <?php if(!empty($params_inscricao_etapa)): ?>
            <tr valign="center">
                <td><?php echo '<strong>Registros Adicionais: </strong>' . strtoupper (implode(', ', $params_inscricao_etapa)); ?></td>
            </tr>
            <?php endif; ?>

        </table> 
        <?php endif; ?>

        <table width="680px" cellspacing="0" cellpadding="0" style="margin-top:60px">
            <tr valign="center">
                <td align="center">
                <?php
                    if(!JFile::exists(JPATH_BASE . DS . 'cache' . DS .  $this->item->id_inscricao_etapa . '-comporvante.png'))
                        QRcode::png(  $this->item->id_inscricao_etapa, JPATH_BASE . DS . 'cache' . DS . $this->item->id_inscricao_etapa . '-comporvante.png', 'L', 4, 2);

                        
                        $pathQR = JPATH_BASE . DS . 'cache' . DS .  $this->item->id_inscricao_etapa . '-comporvante.png';
                        $typeQR = pathinfo($pathQR, PATHINFO_EXTENSION);
                        $dataQR = file_get_contents($pathQR);
                        $base64QR = 'data:image/' . $typeQR . ';base64,' . base64_encode($dataQR);
                    
                    ?>
                    <img src="<?php echo $base64QR; ?>" width="150"  height="150"/>
                </td>
            </tr>
            <tr>
                <td align="left"> 
                    <hr style="margin-top:30px"/>
                </td>
            <tr>
            <tr valign="top">
                <td align="right">
                    <em style="font-size:8px;padding:0;margin:0">
                        <?php echo 'Data do Documento: ' . JHTML::_('date', JFactory::getDate('now', $_siteOffset)->toISO8601(true), 'DATE_FORMAT_LC2'); ?>
                    </em>
                </td>
            </tr>
        </table>





	</div>
</div>

