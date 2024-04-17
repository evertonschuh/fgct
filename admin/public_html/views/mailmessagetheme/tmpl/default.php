<?php
defined('_JEXEC') or die('Restricted access'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
		
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

/*
var $id_mailmessage_theme = null;
var $status_mailmessage_theme = null;
var $name_mailmessage_theme = null;
var $theme_mailmessage_theme = null;
var $checked_out = null;
var $checked_out_time = null;

*/

?>
<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="card-title mb-sm-0 me-2">Modelo de Layout</h5>
                            <?php if($this->item->status_mailmessage_theme < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_mailmessage_theme" value="<?php echo $this->item->status_mailmessage_theme;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_mailmessage_theme" <?php echo $this->item->status_mailmessage_theme == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_mailmessage_theme == 1 ? 'Ativo' : 'Inativo';?></label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="action-btns">
                        <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary me-2">Salvar</button>
                        <button type="button" onclick="Joomla.submitbutton('cancel')" class="btn btn-outline-secondary">Sair</button>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">
                        <div class="mb-4 col-md-12">
                            <a class="btn btn-outline-secondary" href="#" data-bs-toggle="modal" data-bs-target="#sendcobranca">
                                <i class="tf-icons bx bx-mail-send me-1"></i>Enviar Teste
                            </a>  
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name_mailmessage_theme" class="form-label">Nome do Modelo</label>
                            <input
                                class="form-control required"
                                type="text"
                                id="name_mailmessage_theme"
                                name="name_mailmessage_theme"
                                value="<?php echo $this->item->name_mailmessage_theme; ?>"
                                autofocus
                            />
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="alert alert-info">
                                <span>É necessário incluir a variável <strong>Mensagem - {{MENSAGEM}}</strong> para que no momento do envio, o sistema substitua ela pelo conteúdo da mensagem.<br/>
                                    Para adiconar a variável, coloque o cursor no local desejado e clique no botão <strong>Variaveis</strong> e em seguida em <strong>Mensagem</strong>. </span>
                            </div>
                            <label for="theme_mailmessage_theme" class="form-label"><?php echo JText::_('Designer do Modelo'); ?></label>
                            <?php
                                
                                $editor = JFactory::getEditor('tinymce');

                                $params = array(
                                    'id_editor'=>'theme_mailmessage_theme',
                                    'custom_plugin'=>'variable',
                                    'custom_button'=>'variable',
                                    //'html_height'=>'900px'
                                );
                     
                                echo $editor->display('theme_mailmessage_theme', $this->item->theme_mailmessage_theme, '100%', '60', '20', '10', array('article','image','pagebreak', 'readmore'), 'theme_mailmessage_theme', null, null, $params);

                            ?>
                            <div class="clearfix"></div>
                        </div>                  
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_mailmessage_theme; ?>" />
    <input type="hidden" name="id_mailmessage_theme" value="<?php echo $this->item->id_mailmessage_theme; ?>" /> 
    <input type="hidden" name="controller" value="mailmessagetheme" />			
    <input type="hidden" name="view" value="mailmessagetheme" />
    <?php echo JHTML::_('form.token'); ?>	
</form>

<?php if( !empty($this->item->id_mailmessage_theme) ): ?>
<div class="modal fade" id="sendcobranca">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<form method="post" id="adminForma" class="form-validate"> 
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Enviar Mensagem Teste</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="destinatario" class="form-label">Destinatário</label>
                            <input
                                class="form-control required"
                                type="text"
                                id="destinatario"
                                name="destinatario"
                                value="<?php echo JFactory::getUser()->get('email'); ?>"
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary validate" >Enviar</button>	
                    <input type="hidden" name="task" value="sendmail" />
                    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_mailmessage_theme; ?>" />
                    <input type="hidden" name="id_mailmessage_theme" value="<?php echo $this->item->id_mailmessage_theme; ?>" /> 
                    <input type="hidden" name="controller" value="mailmessagetheme" />			
                    <input type="hidden" name="view" value="mailmessagetheme" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>  
    	</div>
  	</div>
</div>
<?php endif; 


?>	



