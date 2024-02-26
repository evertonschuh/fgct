<?php
defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.formvalidation'); 

?>
<form role="form" method="post" name="adminForm" class="form-validate" >
    <div class="panel panel-default" id="refinar-busca">
        <div class="panel-heading">
            <span class="glyphicon left glyphicon-lock"></span>&nbsp;<?php echo JText::_('Reenviar Link') ?>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-12 text-center">
                <label><?php echo JText::_('Reenvio do Link de Ativação de Cadastro.') ?></label>
                <span class="help-block"><?php echo JText::_('Informe o e-mail cadastrado no Portal OEmprego. O sistema enviará uma mensagem contendo o link para ativação do seu cadastro.') ?></span>
            </div>
            <div class="form-group col-xs-12">
                <input type="email" autocomplete="off" name="email_remember" id="email_remember" validate="email" required="true" class="form-control required validate-email" placeholder="<?php echo JText::_('OEMPREGO_GLOBAL_LOGIN_INPUT_EMAIL') ?>" value="<?php echo $this->data->email_remember; ?>" />
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
                <button type="submit" class="btn btn-success btn-lg btn-block validate"><?php echo JText::_('Reenviar Link') ?></button>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" id="task" value="activator" />
    <input type="hidden" name="controller" id="controller" value="remember" />			
    <input type="hidden" name="view" id="view" value="remember" />
    <?php echo JHTML::_('form.token'); ?>	
</form>