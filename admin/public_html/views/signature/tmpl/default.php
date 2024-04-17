<?php
defined('_JEXEC') or die('Restricted access'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
		
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

/*
var $id_signature = null;
var $status_signature = null;
var $name_signature = null;
var $theme_signature = null;
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
                            <h5 class="card-title mb-sm-0 me-2">Assinatura</h5>
                            <?php if($this->item->status_signature < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_signature" value="<?php echo $this->item->status_signature;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_signature" <?php echo $this->item->status_signature == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_signature == 1 ? 'Ativo' : 'Inativo';?></label>
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
                        <div class="mb-3 col-md-12">
                            <label for="name_signature" class="form-label">Nome da Assinatura</label>
                            <input
                                class="form-control required"
                                type="text"
                                id="name_signature"
                                name="name_signature"
                                value="<?php echo $this->item->name_signature; ?>"
                                autofocus
                            />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="signature_signature" class="form-label"><?php echo JText::_('Assinatura'); ?></label>
                            <?php
                                $editor = JFactory::getEditor('tinymce');

                                $params = array(
                                    'id_editor'=>'signature_signature',
                                    'mode' =>'0',
                                    'custom_plugin'=>'imgmanager',
                                    'custom_button'=>'imgmanager',
                                    'html_height'=>'300px'
                                );
                     
                                echo $editor->display('signature_signature', $this->item->signature_signature, '100%', '60', '20', '10', array('article','image','pagebreak', 'readmore'), 'signature_signature', null, null, $params);
                                ?>
                                <p class="form-text text-muted"><em>Crie um modelo para a assinatura.</em></p>
                            </div>
                            <div class="clearfix"></div>
                        </div>   
                        <div class="mb-3 col-md-6">
                            <label for="certificate_signature_new" class="form-label">Cerificado Digital (.pfx)</label>
                            <input class="form-control" type="file" name="certificate_signature_new" accept=".pfx">
                        </div> 
                        
                        <div class="mb-3 col-md-6 form-password-toggle">
                            <label for="password_signature" class="form-label">Senha do Certificado</label>
                           
                            <div class="input-group input-group-merge">
                                <input
                                type="password"
                                id="password_signature"
                                class="form-control"
                                name="password_signature"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password_signature"
                                value="<?php echo $this->item->password_signature; ?>" 
                                />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        
                        <?php if ( !empty( $this->item->certificate_signature )): ?>
                        <div class="mb-3">
                            <input type="hidden" id="certificate_signature" name="certificate_signature" value="<?php echo $this->item->certificate_signature; ?>" />    
                            <a download="<?php echo 'certificado.pfx'; ?>" href="//<?php echo $this->item->certificate_signature; ?>" title="Fazer Download do Certificado">
                                <i class="bx bx-check-shield bx-lg" ></i>
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="mb-3">
                            <fieldset class="checkbox-img-remove">
                                <label>
                                    <input type="checkbox" name="remove_certificate" id="remove_certificate" value="1" />
                                    <?php echo JText::_('Marque e salve para apenas remover o certificado!'); ?>
                                </label>  
                            </fieldset>
                        </div>
                        <?php endif; ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_signature; ?>" />
    <input type="hidden" name="id_signature" value="<?php echo $this->item->id_signature; ?>" /> 
    <input type="hidden" name="controller" value="signature" />			
    <input type="hidden" name="view" value="signature" />
    <?php echo JHTML::_('form.token'); ?>	
</form>

<?php if( !empty($this->item->id_signature) ): ?>
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
                    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_signature; ?>" />
                    <input type="hidden" name="id_signature" value="<?php echo $this->item->id_signature; ?>" /> 
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



