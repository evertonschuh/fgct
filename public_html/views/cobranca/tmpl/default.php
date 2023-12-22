<?php
defined('_JEXEC') or die('Restricted access'); 
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

?>

<form method="post" name="adminForm" class="form-validate">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!-- /.table-responsive -->
                    <div class="col-xs-12 col-lg-8 col-lg-offset-2">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Informações Essenciais</legend>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Referência do Cobrança:*'); ?></label>
                                            <input type="text" autocomplete="off" class="form-control required" name="text_pagamento" id="text_pagamento" placeholder="<?php echo JText::_('Informe um Texto de Referência para esta cobrança'); ?>" value="<?php echo $this->item->text_pagamento; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo JText::_('JGLOBAL_PUBLISH_Y_N') ?></label>
                                            <fieldset>
                                            <?php echo JHTML::_('select.booleanlist', 'status_pagamento', '', $this->item->status_pagamento); ?>
                                            </fieldset>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Método de Pagamento:*'); ?></label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select name="id_pagamento_metodo"  id="id_pagamento_metodo" class="form-control required">
                                                        <?php if ( empty($this->item->id_pagamento_metodo) ) { ?>
                                                        <option disabled selected class="default" value=""><?php echo JText::_('- Selecione o Método -'); ?></option>
                                                        <?php } ?>
                                                        <?php echo JHTML::_('select.options',  $this->metodos, 'value', 'text', $this->item->id_pagamento_metodo ); ?>
                                                    </select>
                                                </div>
                                            </div> 
                                        </div>  
                                        <div class="form-group">
                                            <label><?php echo JText::_('Registrado') ?></label>
                                            <fieldset>
                                            <?php echo JHTML::_('select.booleanlist', 'registrado_pagamento', '', $this->item->registrado_pagamento); ?>
                                            </fieldset>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Data de Vencimento:*') ?></label>
                                            <div class="input-group date col-md-4" id="dataVencimento">
                                                <input type="text" class="form-control required" name="vencimento_pagamento" id="vencimento_pagamento" value="<?php if( $this->item->vencimento_pagamento ) echo JHtml::date(JFactory::getDate($this->item->vencimento_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="Data de Vencimento Original" />
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_('Valor da Cobrança:*') ?></label>
                                                    <input type="text" class="form-control value-money required" name="valor_pagamento" id="valor_pagamento" placeholder="<?php echo JText::_('Valor da Cobrança'); ?>" value="<?php if( $this->item->valor_pagamento > 0 ) echo 'R$ ' . number_format($this->item->valor_pagamento,2,',', '.'); ?>" />
                                                </div>
                                            </div>
                                        </div>   
                                	</fieldset>
                                </div>
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Desconto</legend>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Data para Desconto:') ?></label>
                                            <div class="input-group date col-md-4" id="dataDesconto">
                                                <input type="text" class="form-control" name="vencimento_desconto_pagamento" id="vencimento_desconto_pagamento" value="<?php if( $this->item->vencimento_desconto_pagamento ) echo JHtml::date(JFactory::getDate($this->item->vencimento_desconto_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" placeholder="Data de Vencimento Original" />
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_('Valor com Desconto:') ?></label>
                                                    <input type="text" class="form-control value-money" name="valor_desconto_pagamento" id="valor_desconto_pagamento" placeholder="<?php echo JText::_('Valor da Cobrança'); ?>" value="<?php if( $this->item->valor_desconto_pagamento > 0 ) echo 'R$ ' . number_format($this->item->valor_desconto_pagamento,2,',', '.'); ?>" />
                                                </div>
                                            </div>
                                        </div>    
                                	</fieldset>
                                </div>
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Confirmação de Pagamento</legend>
                                        <div class="form-group">  
                                            <label><?php echo JText::_('Data do Pagamento:') ?></label>
                                            <div class="input-group date col-md-4" id="dataPagamento">
                                                <input type="text" name="baixa_pagamento" id="baixa_pagamento" class="form-control" value="<?php if( $this->item->baixa_pagamento ) echo JHtml::date(JFactory::getDate($this->item->baixa_pagamento, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" size="8" placeholder="Data do Pagamento" />
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_('Valor Pago:') ?></label>
                                                    <input type="text" class="form-control value-money" id="valor_pago_pagamento" name="valor_pago_pagamento" placeholder="<?php echo JText::_('Valor Pago'); ?>" value="<?php if( $this->item->valor_pago_pagamento > 0 ) echo 'R$ ' . number_format($this->item->valor_pago_pagamento,2,',', '.'); ?>" />
                                                </div>
                                            </div>
                                        </div>   
                                    </fieldset>               
                                </div>
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Produto</legend>          
                                        <div class="form-group">
                                            <label><?php echo JText::_('Associado:*'); ?></label>
                                            <select name="id_user" id="id_user" class="form-control required chosen">
                                                <?php if ( empty($this->item->id_user) ) { ?>
                                                <option disabled selected class="default" value=""><?php echo JText::_('- Selecione o Cliente -'); ?></option>
                                                <?php } ?>
                                                <?php echo JHTML::_('select.options',  $this->associadosList, 'value', 'text', $this->item->id_user ); ?>
                                            </select>
                                        </div>  
                                        <div class="form-group">
                                            <label><?php echo JText::_('Tipo de Pagamento:') ?></label>
                                            <fieldset>
                                            <?php echo JHTML::_('select.booleanlist', 'type_pagamento', '', $this->item->type_pagamento, 'Produtos/Serviços', 'Anuidade'); ?>
                                            </fieldset>
                                        </div>
                                        <?php
											$disableProduto = 'disabled="disabled"';
											$styleProduto = 'style="display:none"';
											$disableAnuidade = '';
											$styleAnuidade = '';		
										if($this->item->type_pagamento == 1){
											$disableAnuidade = 'disabled="disabled"';
											$styleAnuidade = 'style="display:none"';
											$disableProduto = '';
											$styleProduto = '';			
										}
										?>
                                        
                                        
                                        <div class="form-group" id="anuidade" <?php echo $styleAnuidade; ?>>
                                            <label><?php echo JText::_('Anuidade:*'); ?></label>
                                            <select name="id_anuidade"  id="id_anuidade" class="form-control required" <?php echo $disableAnuidade; ?> >
                                                <?php if ( empty($this->item->id_produto) ) { ?>
                                                <option disabled selected class="default" value="" ><?php echo JText::_('- Anuidade -'); ?></option>
                                                <?php } ?>
                                                <?php echo JHTML::_('select.options',  $this->anuidadePagamento, 'value', 'text', $this->item->id_anuidade ); ?>
                                            </select>
                                        </div> 
                                        <div class="form-group" id="produto" <?php echo $styleProduto; ?>>
                                            <label><?php echo JText::_('Produto/Serviço:*'); ?></label>
                                            <select name="id_produto"  id="id_produto" class="form-control required" <?php echo $disableProduto; ?> >
                                                <?php if ( empty($this->item->id_produto) ) { ?>
                                                <option disabled selected class="default" value="" ><?php echo JText::_('- Produto/Serviço -'); ?></option>
                                                <?php } ?>
                                                <?php echo JHTML::_('select.options',  $this->produtoPagamento, 'value', 'text', $this->item->id_produto ); ?>
                                            </select>
                                        </div>                 
                                	</fieldset>
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
    <input type="hidden" name="controller" id="controller" value="finpagamento" />			
    <input type="hidden" name="view" id="view" value="finpagamento" />
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

