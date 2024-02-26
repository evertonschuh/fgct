<?php
defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.formvalidation'); 
?>

<div class="panel panel-default" id="refinar-busca">
    <div class="panel-heading">
        <span class="glyphicon left glyphicon-lock"></span>&nbsp;<?php echo JText::_('OEMPREGO_VIEW_REMEMBER_TITLE') ?>
    </div>
    <div class="panel-body">
        <div class="form-group col-xs-12 text-center">
            <label><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_INFO') ?></label>
            <span class="help-block"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_RESULT_INFO_DESCRICAO') ?></span>
        </div>
        <div class="form-group col-xs-12 ">
        <?php 
		if ( $this->mailResult ) {
			foreach($this->mailResult as $result){
				echo $result->username . '<br/>';
			}
		}
		?>
        </div>
        <div class="form-group col-xs-12">
            <a href="<?php echo JRoute::_('index.php?view=login'); ?>" class="btn btn-success btn-lg btn-block validate"><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_REDIRECT_LOGIN') ?></a>
        </div>
        <div class="form-group col-xs-12">
            <a href="<?php echo JRoute::_('index.php?view=remember'); ?>" ><?php echo JText::_('OEMPREGO_VIEW_REMEMBER_BUTTON_PASSWORD') ?></a>
        </div>
    </div>
</div>
