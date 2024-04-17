<?php
defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.formvalidation'); 

?>
<form role="form" method="post" name="adminForm" class="form-validate" >
    <div class="panel panel-default" id="refinar-busca">
        <div class="panel-heading">
            <span class="glyphicon left glyphicon-lock"></span>&nbsp;<?php echo JText::_('OEMPREGO_VIEW_REMEMBER_REMEMAIL_TITLE') ?>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-12 text-center">
                <label><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_INFO') ?></label>
                <span class="help-block"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_REMEMAIL_INFO_DESCRICAO') ?></span>
            </div>
 			<div class="form-group col-xs-12 text-center">
            
                <label class="radio-inline">
                    <input type="radio" name="type_doc" id="type_doc" value="0" <?php if($this->data->type_doc==0) echo ' checked'; ?> /><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_REMEMAIL_TYPE_CPF'); ?> 
                </label>
                <label class="radio-inline">
                    <input type="radio" name="type_doc" id="type_doc"  value="1"<?php if($this->data->type_doc==1) echo ' checked'; ?> /><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_REMEMAIL_TYPE_CNPJ'); ?>
                </label>
            </div> 
            <div id="toggle-cpf" style="display:<?php if($this->data->type_doc==0) echo 'block'; else echo 'none'; ?>;" >
            	<div class="form-group hidden-xs col-sm-3 col-md-3 col-lg-3" >&nbsp;</div>
                <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                    <input type="text" autocomplete="off" class="form-control required validate-cpf" name="cpf_pf" id="cpf_pf" <?php if($this->data->type_doc==1) echo 'disabled="disabled"'; ?>  value="<?php echo $this->data->cpf_pf; ?>" pattern="\d{3}.\d{3}.\d{3}-\d{2}" placeholder="<?php echo JText::_('OEMPREGO_VIEW_REGISTRAR_INPUT_CPF') ?>">
                </div>
                <div class="form-group hidden-xs col-sm-3 col-md-3 col-lg-3" >&nbsp;</div>
			</div>
            <div id="toggle-cnpj" style="display:<?php if($this->data->type_doc==1) echo 'block'; else echo 'none'; ?>; " >
            	<div class="form-group hidden-xs col-sm-3 col-md-3 col-lg-3" >&nbsp;</div>
                <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                     <input type="text" autocomplete="off" class="form-control required validate-cnpj" name="cnpj_pj" id="cnpj_pj" <?php if($this->data->type_doc==0) echo 'disabled="disabled"'; ?> value="<?php echo $this->data->cnpj_pj; ?>" onchange="this.setCustomValidity( validatorCNPJ(this) ? '<?php echo JText::_('OEMPREGO_VIEW_REGCOMPANY_INPUT_CNPJ_ERROR') ?>' : '');" pattern="\d{2}.\d{3}.\d{3}/\d{4}-\d{2}" placeholder="<?php echo JText::_('OEMPREGO_VIEW_REGCOMPANY_INPUT_CNPJ') ?>">
                </div>
                <div class="form-group hidden-xs col-sm-3 col-md-3 col-lg-3" >&nbsp;</div>
			</div>
            <?php 
                JPluginHelper::importPlugin('captcha');
                $dispatcher = JDispatcher::getInstance();
                $dispatcher->trigger('onInit','recaptcha');			
            ?>
            <div class="form-group col-xs-12 text-center">                
            <?php 
				$output = $dispatcher->trigger('onDisplay');
				echo $output[0];
				/* <div id="dynamic_recaptcha_1"></div> */
			?> 
            </div>
            <div class="form-group col-xs-12">
                <button type="submit" class="btn btn-success btn-lg btn-block validate"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_BUTTON_REMEMBER_REMAIL') ?></button>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" id="task" value="rememail" />
    <input type="hidden" name="layout" id="layout" value="rememail" />
    <input type="hidden" name="controller" id="controller" value="remember" />			
    <input type="hidden" name="view" id="view" value="remember" />
    <?php echo JHTML::_('form.token'); ?>	
</form>