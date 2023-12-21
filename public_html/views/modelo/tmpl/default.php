<?php
defined('_JEXEC') or die('Restricted access'); 

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

//$user_vinc = (!($this->item->user_id) ? 0 : 1);
$user = JFactory::getUser();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- /.table-responsive -->
                <form method="post" name="adminForm" class="form-validate">
                    <div class="col-xs-12 col-lg-8 col-lg-offset-2">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label><?php echo JText::_('JGLOBAL_PUBLISH_Y_N') ?></label>
                                    <fieldset class="radio" >
                                    	<?php echo JHTML::_('select.booleanlist', 'status_documento', '', $this->item->status_documento); ?>
                                	</fieldset>
                                </div>
                                <div class="form-group">
                                    <label><?php echo JText::_('Título do Documento:*'); ?></label>
                                    <input type="text" autocomplete="off" class="form-control required" name="name_documento" id="name_documento" placeholder="<?php echo JText::_('Informe um Título para o Documento'); ?>" value="<?php echo $this->item->name_documento; ?>" />
                                </div>
                                <div id="textCertificate">
                                    <div class="form-group">  
                                        <fieldset class="group-border">
                                            <legend class="group-border">Documento</legend>
                                            <a  style="margin-top:-15px;"  href="<?php echo JRoute::_('index.php?view=documento&layout=document&format=pdf&cid=' . $this->item->id_documento); ?>" target="_blank" class="btn btn-primary btn-lg pull-right"><i class="fa fa-search"></i>&nbsp;Pré-visualizar</a>   
                                            
                                            <div class="form-group">
                                                <label for="skin_ava"><?php echo JText::_('Modelo:') ?></label>
                                                <div >
                                                    <style>
                                                        .div-radio{
                                                            display: block; 
                                                            box-shadow: 0 0 3px rgba(0,0,0,0.4);
                                                            height: 160px;
                                                        }
                                                        .div-radio img{
                                                          margin-bottom:5px;
                                                        }
                                                    </style>
                                                    <fieldset class="radio" >
                                                        <?php foreach($this->skinDocumentos as $i => $skinDocumentos): ?>
                                                        <label for="skin_documento<?php echo $i;?>" id="skin_documento<?php echo $i;?>-lbl" class="radiobtn" >
                                                            <input type="radio" name="skin_documento" id="skin_documento<?php echo $i;?>" value="<?php echo $skinDocumentos->value; ?>" <?php if($this->item->skin_documento==$skinDocumentos->value) echo 'checked="checked"'; ?> >
                                                            <div class="div-radio">
                                                                <img src="/media/images/ptimbrado/<?php echo $skinDocumentos->value; ?>_tumb.jpg" />
                                                            <p class="text-center text-radio-skin"><?php echo $skinDocumentos->text; ?></p>
                                                            </div>
                                                        </label>
                                                        <?php endforeach; ?>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="form-group">
                                                <label><?php echo JText::_('Texto do Documento:'); ?></label>
                                                <?php
                                                    $editor = JFactory::getEditor('tinymce');
                                                    $params = array('class_editor'=>'addVarsPlugins',
                                                      'custom_plugin'=>'variable, custon',
                                                      'custom_button'=>'variable | custon'
                                                    );
                                                    echo $editor->display('text_documento', $this->item->text_documento, '100%', '400', '60', '20', array('pagebreak','article','image','readmore'), null, null, null, $params,'addVarsPlugins');
                                                ?>                                                
                                                <div class="clearfix"></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="form-group">  
                                        <fieldset class="group-border">
                                            <legend class="group-border">Disponibilização do Modelo</legend>

                                            <div class="form-group">
                                                <label><?php echo JText::_('Disponibilizar no Site:') ?></label>
                                                <fieldset class="radio" for="public_documento">
                                                    <?php
                                                        $public_documento[] = JHTML::_('select.option', '0', JText::_('Não Disponibilizar'), 'value', 'text' );
                                                        $public_documento[] = JHTML::_('select.option', '1', JText::_('Para Todos Associados'), 'value', 'text' );
                                                        $public_documento[] = JHTML::_('select.option', '2', JText::_('Para Lista Específica'), 'value', 'text' );
                                                        echo JHTML::_('select.radiolist',  $public_documento, 'public_documento','class="group-radio required"', 'value', 'text', $this->item->public_documento ); 
                                                    ?>
                                                </fieldset>
                                            </div>

                                            <?php
                                            $blockAddUsers = 'style="display:none"';
                                            if($this->item->public_documento == 2)
                                                $blockAddUsers = '';
                                            ?>
                                            <div class="form-group block-add-users" <?php echo $blockAddUsers; ?>>    
                                                <fieldset class="group-border" >
                                                    <legend class="group-border">Lista de Associados</legend>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <?php if($this->item->id_documento): ?>
                                                            <div class="form-group group-fieldset">
                                                                <div class="panel panel-fieldset">
                                                                    <div class="panel-body">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <div class="row">
                                                                                    <div class="form-group">
                                                                                        
                                                                                        <div class="toolbar-buttons" id="toolbar"> 
                                                                                            <?php if( $user->authorize('core.admin', 'com_torneios') ): ?> 
                                                                                            <a href="javascript:void(0);" onclick="Joomla.submitbutton('listCPF');" class="quick-btn">
                                                                                                <i class="fa fa-code fa-2x"></i>
                                                                                                <span data-original-title="" title="">Lista CPF</span>
                                                                                            </a>
                                                                                            <?php endif; ?>
                                                                                            <a href="javascript:void(0);" id="openUsers" class="quick-btn">
                                                                                                <i class="fa fa-users fa-2x"></i>
                                                                                                <span data-original-title="" title="">Adicionar</span>
                                                                                            </a>                                                                                            
                                                                                            <a href="javascript:void(0);" onclick="if (document.adminForm.boxchecked.value==0){alert('Selecione algum item da lista');}else{if (confirm('Você tem certeza que deseja exluir o(s) item(s) selecionado(s)? Clique em [ok] para confirma e excluir ou [cancelar] para desistir.')){Joomla.submitbutton('removeUsers');}}" class="quick-btn">
                                                                                                <i class="fa fa-trash-o fa-2x"></i>
                                                                                                <span data-original-title="" title="">Excluir</span>
                                                                                            </a>
                                                                                        </div>                       
                                                                                    </div>                   
                                                                                </div>  
                                                                            </div>
                                                                        </div>      
                                                                        <div class="row">
                                                                            <table class="table table-striped">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="1%" ><input type="checkbox" name="checkall-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll(this)" /></th>
                                                                                        <th><?php echo JHtml::_('grid.sort', 'Nome', 'name', $listDirn, $listOrder); ?></th>
                                                                                        <th class="text-center"><?php echo JHtml::_('grid.sort', 'Status', 'status_associado', $listDirn, $listOrder); ?></th>
                                                                                        <th class="text-center"><?php echo JHtml::_('grid.sort', 'Validade Anuidade', 'validate_associado', $listDirn, $listOrder); ?></th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php  echo $this->loadTemplate('items'); ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="panel-footer text-center buttons-pagination">
                                                                        <div class="pagination-div">
                                                                            <?php echo $this->pagination->getListFooter(); ?>
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            </div>
                                                            <?php else: ?>
                                                            <h4>Salve uma primeira vez para ativar a inclusão de conteúdos</h4>
                                                            <?php endif; ?> 
                                                        </div>                  
                                                    </div>      
                                                </fieldset>
                                            </div> 

                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </div>
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="cid" id="cid" value="<?php echo $this->item->id_documento; ?>" />
                    <input type="hidden" name="id_documento" value="<?php echo $this->item->id_documento; ?>" /> 
                    <input type="hidden" name="id_user" value="<?php echo $this->item->id_user; ?>" /> 
                    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />  
                    <input type="hidden" name="boxchecked" value="0" />	
                    <input type="hidden" name="controller" id="controller" value="documento" />			
                    <input type="hidden" name="view" id="view" value="documento" />
                    <?php echo JHTML::_('form.token'); ?>	
                </form>

            </div>
                
			<?php if($this->item->id_documento) { ?>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-xs-12 col-lg-8 col-lg-offset-2">
                        <div class="col-xs-12">
                            <div class="form-horizontal footer-info">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo JText::_('Cadastro:'); ?></label>
                                    <div class="col-sm-9">
                                        <p><?php echo 'Em ' . JHtml::date(JFactory::getDate($this->item->register_documento, $siteOffset)->toISO8601(), 'DATE_FORMAT_DATATIME', true) . ' por ' . JFactory::getUser($this->item->user_register_documento)->get('name'); ?></p>
                                    </div>
                                </div>
                                <?php if($this->item->update_documento) {?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo JText::_('Última Modificação:'); ?></label>
                                    <div class="col-sm-9">
                                        <p><?php echo 'Em ' . JHtml::date(JFactory::getDate($this->item->update_documento, $siteOffset)->toISO8601(), 'DATE_FORMAT_DATATIME', true) . ' por ' . JFactory::getUser($this->item->user_update_documento)->get('name'); ?></p>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>          
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>  
<?php if($this->item->id_documento) { ?>
<div tabindex="-1" class="modal fade" id="internalModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <h5 class="modal-title">Incluir Associado</h5>
            </div>
            <div class="modal-body modal-items">
                <iframe id="includeItem" src="" width="99.6%" height="100%" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php /*
    <div class="form-group">
        <label id="text_documento-lbl" for="text_documento" class="hasTip" >
            <?php echo JText::_('Descrição'); ?>
        </label>
        <?php
            $editor = JFactory::getEditor('tinymce');
            $params = array('class_editor'=>'addVarsPlugins', 'custom_plugin'=>'variable', 'custom_button'=>'variable');
            echo $editor->display('text_documento', $this->item->text_documento, '100%', '400', '60', '20', array('pagebreak','article','image','readmore'), null, null, null, $params,'addVarsPlugins');
        ?>
        <div class="clearfix"></div>
    </div>
    */ 
?>