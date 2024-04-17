<?php
defined('_JEXEC') or die('Restricted access'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
		
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="card-title mb-sm-0 me-2">Mensagem de Email</h5>
                            <?php if($this->item->status_mailmessage < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_mailmessage" value="<?php echo $this->item->status_mailmessage;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_mailmessage" <?php echo $this->item->status_mailmessage == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_mailmessage == 1 ? 'Ativo' : 'Inativo';?></label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="action-btns">
                        <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary  me-2 validate">Salvar</button>
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
                        <div class="mb-3 col-12 col-md-6">
                            <label for="id_mailmessage_occurrence" class="form-label">Motivo do Diaparo</label>
                            <select name="id_mailmessage_occurrence" id="id_mailmessage_occurrence" class="form-control select2 required">
                                <option disabled selected class="default" value=""><?php echo JText::_('- Motivo do Diaparo -'); ?></option>
                                <?php echo JHTML::_('select.options',  $this->occurrences, 'value', 'text', $this->item->id_mailmessage_occurrence ); ?>
                            </select>
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="id_mailmessage_theme" class="form-label">Layout da Mensagem</label>
                            <select name="id_mailmessage_theme" id="id_mailmessage_theme" class="form-control required">
                                <option disabled selected class="default" value=""><?php echo JText::_('- Layout -'); ?></option>
                                <?php echo JHTML::_('select.options',  $this->themes, 'value', 'text', $this->item->id_mailmessage_theme ); ?>
                            </select>
                        </div>
                        <div class="col-md-12 mt-4">
                            <h6 class="card-title mb-sm-0 me-2">Remetente Alternativo</h6>
                            <hr class="my-1 mb-3" />
                        </div>
                        <div class="mb-3 col-12 col-md-4">
                            <label for="name_mailmessage" class="form-label"><?php echo JText::_('Nome'); ?></label>
                            <input  autocomplete="nope" type="text"  class="form-control" name="name_mailmessage" value="<?php echo $this->item->name_mailmessage; ?>" placeholder="<?php echo JText::_('Nome do remetente alternativo') ?>" />
                        </div>
                        <div class="mb-3 col-12 col-md-4">
                            <label for="account_mailmessage" class="form-label"><?php echo JText::_('E-mail'); ?></label>
                            <input  autocomplete="nope" type="email"  class="form-control validate-email" name="account_mailmessage" value="<?php echo $this->item->account_mailmessage; ?>" placeholder="<?php echo JText::_('E-mail alternativo do domínio') ?>" />
                        </div>
                        <div class="mb-3 col-12 col-md-4">
                            <label for="password_mailmessage" class="form-label"><?php echo JText::_('Senha do E-mail'); ?></label>                         
                            <input  autocomplete="new-password" type="password" class="form-control" name="password_mailmessage"  value="<?php echo $this->item->password_mailmessage; ?>" placeholder="<?php echo JText::_('Senha do E-mail alternativo') ?>"/>
                        </div>

                        <div class="col-md-12 mt-4">
                            <h6 class="card-title mb-sm-0 me-2">Outros Recursos</h6>
                            <hr class="my-1 mb-3" />
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="replyto_mailmessage" class="form-label"><?php echo JText::_('Responder Para'); ?></label>                         
                            <input  autocomplete="off" type="text" class="form-control" name="replyto_mailmessage"  value="<?php echo $this->item->replyto_mailmessage; ?>" placeholder="<?php echo JText::_('Informe o(s) destinatário(s) do resposta') ?>"/>
                            <p class="text-muted mb-0">Em caso de multiplos endereços de e-mail, separe-os por virgula(,).</p>
                        </div>
                        <div class="mb-3 col-12 col-md-6">
                            <label for="relatory_mailmessage" class="form-label"><?php echo JText::_('Enviar Relatório'); ?></label>                         
                            <input  autocomplete="off" type="text" class="form-control" name="relatory_mailmessage"  value="<?php echo $this->item->relatory_mailmessage; ?>" placeholder="<?php echo JText::_('Informe o(s) destinatário(s) do relatório') ?>"/>
                            <p class="text-muted mb-0">Em caso de multiplos endereços de e-mail, separe-os por virgula(,).</p>
                        </div>

                        <div class="col-md-12 mt-4">
                            <h6 class="card-title mb-sm-0 me-2">Mensagem</h6>
                            <hr class="my-1 mb-3" />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="subject_mailmessage" class="form-label"><?php echo JText::_('Assunto da Mensagem'); ?></label>                         
                            <input  autocomplete="off" type="text" class="form-control required" name="subject_mailmessage"  value="<?php echo $this->item->subject_mailmessage; ?>" placeholder="<?php echo JText::_('Informe o assunto da mensagem') ?>"/>
                        </div>     
                        <div class="mb-3 col-md-12">
                            <div class="alert alert-info">
                                <span>Para adiconar variáveis, coloque o cursor no local desejado e clique no botão <strong>Variaveis</strong> e em seguida na variável desejada.<br/>
                                        Se você está cadastrando pela primeira vez, salve primeiro para que o botão de <strong>Variáveis</strong> seja exibido.<br/> 
                                        Se você está atualizando o <strong>Tipo de Ocorrência</strong>, salve primeiro com a nova ocorrencia selecionada para que as <strong>Variáveis</strong> sejam atualizadas.
                                </span>
                            </div>
                            <label for="mensagem_mailmessage" class="form-label"><?php echo JText::_('Conteúdo da Mensagem:'); ?></label>
                            <?php
                                $params = array();
                                $editor = JFactory::getEditor('tinymce');
                                if(isset($this->item->plugin_mailmessage_occurrence) && !empty($this->item->plugin_mailmessage_occurrence)):

                                $params = array(
                                    'id_editor'=>'mensagem_mailmessage',
                                    'custom_plugin'=>'variable',
                                    'custom_button'=>'variable',
                                    //'html_height'=>'900px'
                                    );
              
              
                                                 


                                endif;             
                                echo $editor->display('mensagem_mailmessage', $this->item->mensagem_mailmessage, '100%', '60', '20', '10', array('article','image','pagebreak', 'readmore'), 'mensagem_mailmessage', null, null, $params);
                            ?>
                            <div class="clearfix"></div>
                        
                        </div>                  
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_mailmessage; ?>" />
    <input type="hidden" name="id_mailmessage" value="<?php echo $this->item->id_mailmessage; ?>" /> 
    <input type="hidden" name="controller" value="mailmessage" />			
    <input type="hidden" name="view" value="mailmessage" />
    <?php echo JHTML::_('form.token'); ?>	
</form>


<?php if( !empty($this->item->id_mailmessage) ): ?>
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
                    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_mailmessage; ?>" />
                    <input type="hidden" name="id_schedule" value="<?php echo $this->item->id_mailmessage; ?>" /> 
                    <input type="hidden" name="controller" value="mailmessage" />			
                    <input type="hidden" name="view" value="mailmessage" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>  
    	</div>
  	</div>
</div>
<?php endif; ?>	