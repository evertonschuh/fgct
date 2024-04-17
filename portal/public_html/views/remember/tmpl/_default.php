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
                <span class="help-block"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_INFO_DESCRICAO') ?></span>
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
                <button type="submit" class="btn btn-success btn-lg btn-block validate"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_BUTTON_REMEMBER') ?></button>
            </div>
            <div class="form-group col-xs-12">
            	<p><a href="<?php echo JRoute::_('index.php?view=remember&layout=rememail'); ?>" ><?php echo JText::_('OEMPREGO_GLOBAL_LOGIN_LINK_REMEMBER_EMAIL') ?></a></p>
            	<p><a href="<?php echo JRoute::_('index.php?view=remember&layout=activator'); ?>" ><?php echo JText::_('Quer que reenvie o link de ativação de cadastro?') ?></a></p>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" id="task" value="remember" />
    <input type="hidden" name="controller" id="controller" value="remember" />			
    <input type="hidden" name="view" id="view" value="remember" />
    <?php echo JHTML::_('form.token'); ?>	
</form>