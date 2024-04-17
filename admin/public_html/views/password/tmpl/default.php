<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation'); 
JHtml::_('behavior.keepalive');

?>
<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">

    <div class="row">
        <div class="col-sm-12 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
            <div class="card shadow mb-4 ">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo $this->item->get('name'); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-lg-8 offset-lg-2">
                            <div class="form-group">
                                <label id="label_cpf"><?php echo JText::_('Senha Atual:'); ?></label>
                                <input type="password" autocomplete="off" class="form-control required validate-password" name="password_atual" id="password_atual" pattern="(?=.*[a-zA-Z0-9]).{6,}" placeholder="<?php echo JText::_('Informe a Senha Atual'); ?>" />
                            </div>
                            <div class="form-group" >
                                <label><?php echo JText::_('Nova Senha:'); ?></label>
                                <input type="password" autocomplete="off" class="form-control required validate-password" name="password" id="password"  validate="equalspass" pattern="(?=.*[a-zA-Z0-9]).{6,}"  placeholder="<?php echo JText::_('Informe a Nova Senha'); ?>" />
                            </div>
                            <div class="form-group">
                                <label><?php echo JText::_('Confirmar Senha:'); ?></label>
                                <input type="password" autocomplete="off" class="form-control required validate-equalspass" name="password2" id="password2"  validate="equalspass" placeholder="<?php echo JText::_('Confirme a Nova Senha'); ?>" />
                            </div>
                            <div class="form-group text-center mt-5">
                                <a href="javascript:void(0);" onclick="Joomla.submitbutton('apply')" class="btn btn-primary shadow btn-lg" >
                                    <i class="fas fa-check"></i> Aplicar Alteração
                                </a>
                            </div>


                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" id="controller" value="password" />			
    <input type="hidden" name="view" id="view" value="password" />
    <?php echo JHTML::_('form.token'); ?>	
</form>