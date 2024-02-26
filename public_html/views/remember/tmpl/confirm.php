<?php
defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.formvalidation'); 

?>
<form role="form" method="post" name="adminForm" class="form-validate" >
    <div class="panel panel-default" id="refinar-busca">
        <div class="panel-heading">
            <span class="glyphicon left glyphicon-lock"></span>&nbsp;<?php echo JText::_('OEMPREGO_VIEW_REMEMBER_TITLE') ?>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-12 text-center">
                <label><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_INFO') ?></label>
                <span class="help-block"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_CONFIRM_INFO_DESCRICAO') ?></span>
            </div>
            <div class="form-group col-xs-12">
                <input type="email" autocomplete="off" name="email_remember" id="email_remember" validate="email" ="true" class="form-control required validate-email" placeholder="<?php echo JText::_('OEMPREGO_GLOBAL_LOGIN_INPUT_EMAIL') ?>" value="<?php echo $this->data->email_remember; ?>" >
            </div>
            <div class="form-group col-xs-12">                
                <input type="text" autocomplete="off" name="token_remember" id="token_remember" required="true" class="form-control required" placeholder="<?php echo JText::_('OEMPREGO_VIEW_REMEMBER_CONFIRM_INPUT_COD') ?>" >
            </div>
            <div class="form-group col-xs-12">
                <button type="submit" class="btn btn-success btn-lg btn-block validate"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_CONFIRM_BUTTON') ?></button>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" id="task" value="confirm" />
    <input type="hidden" name="controller" id="controller" value="remember" />			
    <input type="hidden" name="view" id="view" value="remember" />
    <?php echo JHTML::_('form.token'); ?>	
</form>