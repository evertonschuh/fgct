<?php
defined('_JEXEC') or die('Restricted access'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
		
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 


?>
<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <!-- /.table-responsive -->
                    <div class="col-xs-12 col-lg-8 col-lg-offset-2">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label><?php echo JText::_('Motivo do Diaparo:*'); ?></label>
                                            <select name="id_mailmessage_occurrence" id="id_mailmessage_occurrence" class="form-control select2 required">
                                               <option disabled selected class="default" value=""><?php echo JText::_('- Motivo do Diaparo -'); ?></option>
                                                <?php echo JHTML::_('select.options',  $this->occurrences, 'value', 'text', $this->item->id_mailmessage_occurrence ); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label><?php echo JText::_('JGLOBAL_PUBLISH_Y_N') ?></label>
                                    <fieldset>
                                    	<?php echo JHTML::_('select.booleanlist', 'status_mailmessage', '', $this->item->status_mailmessage); ?>
                                	</fieldset>
                                </div>  
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label><?php echo JText::_('Layout da Mensagem:*'); ?></label>
                                            <select name="id_mailmessage_theme" id="id_mailmessage_theme" class="form-control required">
                                               <option disabled selected class="default" value=""><?php echo JText::_('- Layout -'); ?></option>
                                                <?php echo JHTML::_('select.options',  $this->themes, 'value', 'text', $this->item->id_mailmessage_theme ); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group">
                                    <label><?php echo JText::_('Salvar em Notificação do Aluno') ?></label>
                                    <fieldset>
                                    	<?php echo JHTML::_('select.booleanlist', 'save_mailmessage', '', $this->item->save_mailmessage); ?>
                                	</fieldset>
                                </div>  
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Remetente Alternativo</legend>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Nome:'); ?></label>
                                            <input  autocomplete="nope" type="text"  class="form-control" name="name_account_mailmessage" value="<?php echo $this->item->name_account_mailmessage; ?>" placeholder="<?php echo JText::_('Nome do remetente alternativo') ?>" />
                                        </div>      
                                        <div class="form-group">
                                            <label><?php echo JText::_('E-mail:'); ?></label>
                                            <input  autocomplete="nope" type="email"  class="form-control validate-email" name="account_mailmessage" value="<?php echo $this->item->account_mailmessage; ?>" placeholder="<?php echo JText::_('E-mail alternativo do domínio') ?>" />
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4">
                                                    <label><?php echo JText::_('Senha do E-mail:'); ?></label>                         
                                                    <input  autocomplete="new-password" type="password" class="form-control" name="password_mailmessage"  value="<?php echo $this->item->password_mailmessage; ?>" placeholder="<?php echo JText::_('Senha do E-mail alternativo') ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                	</fieldset>
                                </div>         


                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Acompanhamentos</legend>
                                        <?php /*
                                		<div class="form-group">
                                            <label><?php echo JText::_('Email(s):'); ?></label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="text" autocomplete="off" class="form-control" name="relatory_mailmessage" id="relatory_mailmessage" placeholder="<?php echo JText::_('Informe o(s) destinatário(s) do relatório'); ?>" value="<?php echo $this->item->relatory_mailmessage; ?>" />
                                                </div>
                                            </div>
                                            <p class="help-block">Em caso de multiplos endereços de e-mail, separe-os por virgula(,).</p>
                                       		<div class="clearfix"></div>
                                    	</div>
                                        */ ?>
                                		<div class="form-group">
                                            <label><?php echo JText::_('Responder Para:'); ?></label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="text" autocomplete="off" class="form-control" name="replyto_mailmessage" id="replyto_mailmessage" placeholder="<?php echo JText::_('Informe o(s) destinatário(s) do resposta'); ?>" value="<?php echo $this->item->replyto_mailmessage; ?>" />
                                                </div>
                                            </div>
                                            <p class="help-block">Em caso de multiplos endereços de e-mail, separe-os por virgula(,).</p>
                                       		<div class="clearfix"></div>
                                    	</div>
                                	</fieldset>
                                </div>    
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Fixar Destinatário</legend>
                                		<div class="form-group">
                                            <label><?php echo JText::_('Enviar Para:'); ?></label>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="text" autocomplete="off" class="form-control" name="recipient_mailmessage" id="recipient_mailmessage" placeholder="<?php echo JText::_('Informe o(s) destinatário(s)'); ?>" value="<?php echo $this->item->recipient_mailmessage; ?>" />
                                                </div>
                                            </div>
                                            <p class="help-block">Em caso de multiplos endereços de e-mail, separe-os por virgula(,).</p>
                                       		<div class="clearfix"></div>
                                    	</div>
                                	</fieldset>
                                </div>       
   
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Grupo de Envio</legend>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Modalidade:') ?></label>
                                            <fieldset class="group-radio" for="modality_mailmessage">
                                                <?php 
                                                    $modalidades[] = JHTML::_('select.option', '-1', JText::_( 'Todas' ), 'value', 'text' );
                                                    $modalidades[] = JHTML::_('select.option', '0', JText::_( 'Presencial' ), 'value', 'text' );
                                                    $modalidades[] = JHTML::_('select.option', '1', JText::_( 'EAD' ), 'value', 'text' );
                                                    
                                                    echo JHTML::_('select.radiolist',  $modalidades, 'modality_mailmessage','class="group-radio required"', 'value', 'text',  is_null($this->item->modality_mailmessage) ? -1 : $this->item->modality_mailmessage );
                                                ?>
                                            </fieldset>
                                        </div> 
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_('Tipo do Evento:'); ?></label>
                                                    <select name="id_evento_tipo" id="id_evento_tipo" class="form-control required">
                                                        <?php 
                                                            $id_evento_tipo[] = JHTML::_('select.option', '-1', JText::_( 'Todos' ), 'value', 'text' );
                                                            $id_evento_tipo = array_merge($id_evento_tipo, $this->tipos);
                                                            echo JHTML::_('select.options',  $id_evento_tipo, 'value', 'text',  $this->item->id_evento_tipo );
                                                         ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label>Nome do Curso:</label>
                                                    <select id="id_evento" name="id_evento" class="form-control select2 required ">
                                                        <?php 
                                                            $id_evento[] = JHTML::_('select.option', '-1', JText::_( 'Todos' ), 'value', 'text' );
                                                            $id_evento = array_merge($id_evento, $this->eventos);
                                                            echo JHTML::_('select.options',  $id_evento, 'value', 'text',  $this->item->id_evento );
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> 
                                	</fieldset>
                                </div>
                                <div class="form-group">
                                    <fieldset class="group-border">
                                        <legend class="group-border">Mensagem</legend>
                                            <div class="alert alert-info">
                                                <span>
                                                    Para adiconar variáveis, coloque o cursor no local desejado e clique no botão <strong>Variaveis</strong> e em seguida na variável desejada.<br/>
                                                    Se você está cadastrando pela primeira vez, salve primeiro para que o botão de <strong>Variáveis</strong> seja exibido.<br/> 
                                                    Se você está atualizando o <strong>Tipo de Ocorrência</strong>, salve primeiro com a nova ocorrencia selecionada para que as <strong>Variáveis</strong> sejam atualizadas.
                                                </span>
                                            </div>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Assunto da Mensagem:*'); ?></label>
                                            <input type="text" autocomplete="off" class="form-control required" name="subject_mailmessage" id="subject_mailmessage" placeholder="<?php echo JText::_('Informe o assunto da mensagem'); ?>" value="<?php echo $this->item->subject_mailmessage; ?>" />
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo JText::_('Conteúdo da Mensagem:'); ?></label>
                                            <?php
                                                $params = array();
                                                $editor = JFactory::getEditor('tinymce');
                                                if(isset($this->item->plugin_mailmessage_occurrence) && !empty($this->item->plugin_mailmessage_occurrence)):
                                                $params = array(
                                                                'id_editor'=>'mensagem_mailmessage',
                                                                'custom_plugin'=>'custon',
                                                                'custom_button'=>'custon',
                                                                );
                                                endif;                
                                                echo $editor->display('mensagem_mailmessage', $this->item->mensagem_mailmessage, '100%', '200', '60', '20', array('article','image','pagebreak', 'readmore'),'mensagem_mailmessage', null, null, $params);
                                            ?>
                                            <div class="clearfix"></div>
                                        </div>
									</fieldset>
                            	</div>

                            </div>
                        </div>                  
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_mailmessage; ?>" />
    <input type="hidden" name="id_mailmessage" value="<?php echo $this->item->id_mailmessage; ?>" /> 
    <input type="hidden" name="controller" value="autmanmessage" />			
    <input type="hidden" name="view" value="autmanmessage" />
    <?php echo JHTML::_('form.token'); ?>	
</form>

<?php if( !empty($this->item->id_mailmessage) ): ?>
<div class="modal fade" id="sendcobranca">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<form method="post" id="adminForma" class="form-validate"> 
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo JText::_('JGLOBAL_BUTTON_CLOSE'); ?></span></button>
                    <h4 class="modal-title"><?php echo JText::_('Enviar Mensagem Teste'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><?php echo JText::_('Destinatário:'); ?></label>
                        <input type="text" class="form-control required validate-email" name="destinatario" placeholder="<?php echo JText::_('Informe o  Destinatário'); ?>" value="<?php echo JFactory::getUser()->get('email'); ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm validate" >Enviar</button>	
                    <input type="hidden" name="task" value="sendmail" />
                    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_mailmessage; ?>" />
                    <input type="hidden" name="id_schedule" value="<?php echo $this->item->id_mailmessage; ?>" /> 
                    <input type="hidden" name="controller" value="autmanmessage" />			
                    <input type="hidden" name="view" value="autmanmessage" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>  
    	</div>
  	</div>
</div>
<?php endif; ?>	