<?php
defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

?>
<form method="post" name="adminForm" class="form-validate">
    <div class="card shadow mb-4">
		<div class="card-header py-3">
        	<h6 class="m-0 font-weight-bold text-primary"><?php echo empty($this->item->id_pf) ? 'Novo Cadastro' : 'Editar Cadastro' ?></h6>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo JText::_('Referência do Cobrança:*'); ?></label>
                                <input type="text" autocomplete="off" class="form-control required" name="text_pagamento" id="text_pagamento" placeholder="<?php echo JText::_('Informe um Texto de Referência para esta cobrança'); ?>" value="<?php echo $this->item->text_pagamento; ?>" />
                            </div>

                            <div class="form-group">
                                <label><?php echo JText::_('Método de Pagamento:*'); ?></label>
                                <select name="id_pagamento_metodo"  id="id_pagamento_metodo" class="form-control required">
                                    <?php if ( empty($this->item->id_pagamento_metodo) ) { ?>
                                    <option disabled selected class="default" value=""><?php echo JText::_('- Selecione o Método -'); ?></option>
                                    <?php } ?>
                                    <?php echo JHTML::_('select.options',  $this->metodos, 'value', 'text', $this->item->id_pagamento_metodo ); ?>
                                </select>
                            </div> 
                            <div class="form-group">
                                <label><?php echo JText::_('JGLOBAL_PUBLISH_Y_N') ?></label>
                                <fieldset>
                                <?php echo JHTML::_('select.booleanlist', 'status_pagamento', '', $this->item->status_pagamento); ?>
                                </fieldset>
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('Registrado') ?></label>
                                <fieldset>
                                <?php echo JHTML::_('select.booleanlist', 'registrado_pagamento', '', $this->item->registrado_pagamento); ?>
                                </fieldset>
                            </div>
                        </div>

                        <div class="col-12 col-md-9">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="valores-tab" data-toggle="tab" href="#valores" role="tab" aria-controls="valores" aria-selected="true"><b>Valores</b></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" id="vinculo-tab" data-toggle="tab" href="#vinculo" role="tab" aria-controls="vinculo" aria-selected="true"><b>Vinculação</b></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="arquivos-tab" data-toggle="tab" href="#arquivos" role="tab" aria-controls="arquivos" aria-selected="true"><b>Automação</b></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="valores" role="tabpanel" aria-labelledby="valores-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>
                                            <div class="row">
                                                <div class=" col-sm-12 col-md-6 col-lg-5">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Data de Vencimento:*') ?></label>
                                                        <div class="input-group date">
                                                            <input type="text" class="form-control required" name="vencimento_pagamento" id="vencimento_pagamento" value="<?php if( $this->item->vencimento_pagamento ) echo JHtml::date(JFactory::getDate($this->item->vencimento_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="Data de Vencimento Original" />
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>                                                      
                                                    </div>
                                                </div>  
                                                <div class="form-group visible-xs visible-sm"></div>
                                                <div class="col-sm-12 col-md-6 col-lg-5">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Valor da Cobrança:*') ?></label>
                                                        <input type="text" class="form-control value-money required" name="valor_pagamento" id="valor_pagamento" placeholder="<?php echo JText::_('Valor da Cobrança'); ?>" value="<?php if( $this->item->valor_pagamento > 0 ) echo mascaraMoeda($this->item->moeda_pagamento, $this->item->valor_pagamento); ?>" />
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="row">
                                                <div class=" col-sm-12 col-md-6 col-lg-5">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Data para Desconto:') ?></label>
                                                        <div class="input-group date" >
                                                            <input type="text" class="form-control" name="vencimento_desconto_pagamento" id="vencimento_desconto_pagamento" value="<?php if( $this->item->vencimento_desconto_pagamento ) echo JHtml::date(JFactory::getDate($this->item->vencimento_desconto_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="Data para Desconto" />
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>                                                        
                                                    </div>
                                                </div>  
                                                <div class="form-group visible-xs visible-sm"></div>
                                                <div class="col-sm-12 col-md-6 col-lg-5">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Valor com Desconto:') ?></label>
                                                        <input type="text" class="form-control value-money" name="valor_desconto_pagamento" id="valor_desconto_pagamento" value="<?php if( $this->item->valor_desconto_pagamento > 0 ) echo mascaraMoeda($this->item->moeda_pagamento, $this->item->valor_desconto_pagamento); ?>" placeholder="Valor com Desconto" />
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="row">
                                                <div class=" col-sm-12 col-md-6 col-lg-5">
                                                    <div class="form-group">
                                                        <label><?php echo JText::_('Data do Pagamento:') ?></label>
                                                        <div class="input-group date">
                                                            <input type="text" name="confirmado_pagamento" id="confirmado_pagamento" class="form-control" value="<?php if( $this->item->confirmado_pagamento ) echo JHtml::date(JFactory::getDate($this->item->confirmado_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" size="8" placeholder="Data do Pagamento" />
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                        </div>                                                       
                                                    </div>
                                                </div>
                                                <div class="form-group visible-xs visible-sm"></div>
                                                <div class=" col-sm-12 col-md-6 col-lg-5">
                                                    <div class="form-group">
                                                    <label><?php echo JText::_('Valor Pago:') ?></label>
                                                    <input type="text" class="form-control value-money" id="valorpago_pagamento" name="valorpago_pagamento" placeholder="<?php echo JText::_('Valor Pago'); ?>" value="<?php if( $this->item->valorpago_pagamento > 0 ) echo mascaraMoeda($this->item->moeda_pagamento, $this->item->valorpago_pagamento); ?>" />
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="form-group">  
                                                <fieldset class="checkbox-img-remove">
                                                    <label>
                                                        <input type="checkbox" name="processar" id="processar" value="1" />
                                                        <?php echo JText::_('Marque para executar procedimentos de baixa.'); ?>
                                                    </label>  
                                                </fieldset>
                                                <fieldset class="checkbox-img-remove">
                                                    <label>
                                                        <input type="checkbox" name="send_mail" id="send_mail" value="1" />
                                                        <?php echo JText::_('Marque para enviar e-mail de confirmação de baixa ao cliente.'); ?>
                                                    </label>  
                                                </fieldset>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade active show" id="vinculo" role="tabpanel" aria-labelledby="vinculo-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>

                                        </div>                                                      
                                    </div>
                                </div>  
                                <div class="tab-pane fade active show" id="arquivos" role="tabpanel" aria-labelledby="arquivos-tab">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            </br>

                                        </div>                                                      
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_pagamento; ?>" />
    <input type="hidden" name="id_pagamento" value="<?php echo $this->item->id_pagamento; ?>" /> 
    <input type="hidden" name="controller" id="controller" value="pagamento" />			
    <input type="hidden" name="view" id="view" value="pagamento" />
    <?php echo JHTML::_('form.token'); ?>	
</form>



<?php if( !empty($this->item->id_pagamento) ): ?>
<div class="modal fade" id="sendcobranca">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<form method="post" id="adminForma" class="form-validate"> 
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo JText::_('JGLOBAL_BUTTON_CLOSE'); ?></span></button>
                    <h4 class="modal-title"><?php echo JText::_('Enviar Pagamento'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo JText::_('Destinatário:'); ?></label>
                        <input type="text" class="form-control required validate-email" name="destinatario" placeholder="<?php echo JText::_('Inform o  Destinatário'); ?>" value="<?php echo $this->item->email; ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm validate" >Enviar</button>	
                    <input type="hidden" name="task" value="sendmail" />
                    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_pagamento; ?>" />
                    <input type="hidden" name="id_pagamento" value="<?php echo $this->item->id_pagamento; ?>" /> 
                    <input type="hidden" name="controller" id="controller" value="finpagamento" />
                    <input type="hidden" name="view" id="view" value="finpagamento" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>  
    	</div>
  	</div>
</div>
<?php endif; ?>	

