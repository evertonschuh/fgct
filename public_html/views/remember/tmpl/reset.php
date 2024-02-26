<?php
defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.formvalidation'); 
?>
<form role="form" method="post" name="adminForm" class="form-validate">
    <div class="panel panel-default" id="refinar-busca">
        <div class="panel-heading">
            <span class="glyphicon left glyphicon-lock"></span>&nbsp;<?php echo JText::_('OEMPREGO_VIEW_REMEMBER_TITLE') ?>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-12 text-center">
                <label><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_INFO') ?></label>
                <span class="help-block"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_RESET_INFO_DESCRICAO') ?></span>
            </div>
            <div class="form-group col-xs-12 ">
            	<input type="password" autocomplete="off" class="form-control required validate-password" name="password" id="password"  validate="equalspass" pattern="(?=.*[a-zA-Z0-9]).{6,}" value="<?php echo isset($this->data->password) ? $this->data->password: '' ; ?>" placeholder="<?php echo JText::_('OEMPREGO_VIEW_REGISTRAR_INPUT_SENHA') ?>" >
            </div>
            <div class="form-group col-xs-12 ">                
            	<input type="password" autocomplete="off" class="form-control required validate-equalspass" name="password2" id="password2"  validate="equalspass" value="<?php echo isset($this->data->password) ? $this->data->password: ''; ?>"  placeholder="<?php echo JText::_('OEMPREGO_VIEW_REGISTRAR_INPUT_CONFIRMA_SENHA') ?>">
            </div>
            <div class="form-group col-xs-12">
                <button type="submit" class="btn btn-success btn-lg btn-block validate"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_RESET_BUTTON') ?></button>
            </div>
        </div>
    </div>
    <input type="hidden" name="controller" id="controller" value="remember" />			
    <input type="hidden" name="view" id="view" value="remember" />
	<input type="hidden" name="task" id="task" value="resetpass" />
    <?php echo JHTML::_('form.token'); ?>	
</form>