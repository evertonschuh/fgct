<?php
defined('_JEXEC') or die('Restricted access'); 

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

jimport('joomla.image.resize');
$resize = new JResize();

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

//$user_vinc = (!($this->item->user_id) ? 0 : 1);
$user = JFactory::getUser();
	
?>
<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="card-title mb-sm-0 me-2">Modelo</h5>
                            <?php if($this->item->status_documento < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_documento" value="<?php echo $this->item->status_documento;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_documento" <?php echo $this->item->status_documento == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_documento == 1 ? 'Ativo' : 'Inativo';?></label>
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
                            <a class="btn btn-outline-secondary" href="<?php echo JRoute::_('index.php?view=dmodel&layout=document&format=pdf&cid=' . $this->item->id_documento); ?>" target="_blank">
                                <i class="fa fa-search"></i>&nbsp;Pré-visualizar
                            </a>    
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="name_documento" class="form-label">Nome do Modelo</label>
                            <input
                                class="form-control required"
                                type="text"
                                id="name_documento"
                                name="name_documento"
                                value="<?php echo $this->item->name_documento; ?>"
                                autofocus
                            />
                        </div>

                        <div class="mb-4 col-md-12">
                        <label for="skin_documento" class="form-label">Papel de Fundo</label>                 
                        <div>
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
                                <div class="row">
                                <?php foreach($this->skinDocumentos as $i => $skinDocumentos): ?>
                                    <div class="col-md-2 mb-md-0 mb-2">
                                        <div class="form-check custom-option custom-option-image custom-option-image-radio <?php if($this->item->skin_documento==$skinDocumentos->value) echo 'checked'; ?>">
                                        <label class="form-check-label custom-option-content" for="skin_documento<?php echo $i;?>" id="skin_documento<?php echo $i;?>-lbl">
                                            <span class="custom-option-body">
                                            <img src="<?php echo $resize->resize(JPATH_CDN .DS. 'images' .DS. 'ptimbrado' .DS. $skinDocumentos->value.'_tumb.jpg', 205, 265, 'cache/' . $skinDocumentos->value.'_tumb.jpg', 'manterProporcao');?>" alt="radioImg">
                                            </span>
                                        </label>
                                        <input name="skin_documento" class="form-check-input" type="radio" id="skin_documento<?php echo $i;?>" value="<?php echo $skinDocumentos->value; ?>" <?php if($this->item->skin_documento==$skinDocumentos->value) echo 'checked="checked"'; ?>>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="clearfix"></div>


                        <div class="mb-3 col-md-12">
                            <div class="alert alert-info">
                                <span>É necessário incluir a variável <strong>Mensagem - {{MENSAGEM}}</strong> para que no momento do envio, o sistema substitua ela pelo conteúdo da mensagem.<br/>
                                    Para adiconar a variável, coloque o cursor no local desejado e clique no botão <strong>Variaveis</strong> e em seguida em <strong>Mensagem</strong>. </span>
                            </div>
                            <label for="theme_mailmessage_theme" class="form-label"><?php echo JText::_('Designer do Modelo'); ?></label>
                            <?php
                                
                                $editor = JFactory::getEditor('tinymce');

                                $params = array(
                                    'id_editor'=>'text_documento',
                                    'custom_plugin'=>'variable,ruler,paragraphs,signature',
                                    'custom_button'=>'variable|paragraphs-add|paragraphs-remove|signature',
                                    'html_height'=>'900px'
                                );
                     
                                echo $editor->display('text_documento', $this->item->text_documento, '100%', '60', '20', '10', array('article','image','pagebreak', 'readmore'), 'text_documento', null, null, $params);

                            ?>
                            <div class="clearfix"></div>
                        </div>                  
                    </div>


                    <div class="col-md-12 mt-4">
                        <h6 class="card-title mb-sm-0 me-2">Disponibilização do Modelo</h6>
                        <hr class="my-1 mb-3" />
                    </div>
                    <div class="mb-3 col-md float-right">
                        <fieldset class="radio" for="public_documento">
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-icon <?php echo !isset($this->item->public_documento) || $this->item->public_documento=='0' ? 'checked' : ''; ?>">
                                        <label class="form-check-label custom-option-content" for="public_documento0">
                                            <input class="form-check-input" style="margin: 7px 7px 0 0 !important;" <?php echo !isset($this->item->public_documento) ||  $this->item->public_documento=='0' ? 'checked' : ''; ?> type="radio" value="0" name="public_documento" id="public_documento0">
                                            <span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2">
                                                <i class="bx bx-power-off bx-xs"></i>
                                            </span>
                                            Não Disponibilizar                                        
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon <?php echo isset($this->item->public_documento) && $this->item->public_documento=='1' ? 'checked' : ''; ?>">
                                        <label class="form-check-label custom-option-content" for="public_documento1">
                                            <input  class="form-check-input" style="margin: 7px 7px 0 0 !important;" <?php echo isset($this->item->public_documento) && $this->item->public_documento=='1' ? 'checked' : ''; ?> type="radio" value="1" name="public_documento" id="public_documento1">
                                            <span class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2">
                                                <i class="bx bxs-planet bx-xs"></i>
                                            </span>
                                            Para Todos Associados
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon <?php echo isset($this->item->public_documento) && $this->item->public_documento=='2' ? 'checked' : ''; ?>">
                                        <label class="form-check-label custom-option-content" for="public_documento2">
                                            <input  class="form-check-input" style="margin: 7px 7px 0 0 !important;" <?php echo isset($this->item->public_documento) && $this->item->public_documento=='2' ? 'checked' : ''; ?> type="radio" value="2" name="public_documento" id="public_documento2">
                                            <span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2">
                                                <i class="bx bxs-user bx-xs"></i>
                                            </span>
                                            Para Lista Específica
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <?php
                        $blockAddUsers = 'style="display:none"';
                        if($this->item->public_documento == 2)
                            $blockAddUsers = '';
                    ?>
                    <div class="block-add-users mt-5" id="list-users" <?php echo $blockAddUsers; ?>>  
                        <?php if($this->item->id_documento): ?>
                            <div class="mb-3 sticky-element d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                            <div class="row">
                            </div>
                            <div class="action-btns">
                                <button type="button" class="btn btn-primary" id="openUsers">
                                    <span class="tf-icons bx bx-pie-chart-alt"></span>&nbsp; Adicionar
                                </button>
                                <?php if( $user->get('id')== '42' ): ?> 
                                <button type="button" onclick="Joomla.submitbutton('listCPF');" class="btn btn-danger">
                                    <span class="tf-icons bx bx-code"></span>&nbsp; Lista CPF no codigo
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="card-body">
                                    

                                <div class="card-datatable table-responsive">
                                    <div class="dataTables_wrapper dt-bootstrap5 no-footer ">

                                        <table class="datatables-users table border-top dataTable no-footer dtr-column">
                                            <thead>
                                                <tr>
                                                    <th class="sorting sorting_desc"><?php echo JHtml::_('grid.sort',  'Nome', 'name', $listDirn, $listOrder); ?></th>
                                                    <th class="sorting text-center"><?php echo JHtml::_('grid.sort', 'Status', 'status_associado', $listDirn, $listOrder); ?></th>
                                                    <th class="sorting text-center"><?php echo JHtml::_('grid.sort', 'Validade Anuidade', 'validate_associado', $listDirn, $listOrder); ?></th>
                                                    <th class="sorting_disabled" style="width: 145px;">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $this->loadTemplate('items'); ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7" align="right">
                                                        <strong>Total de Itens <?php echo $this->total; ?></strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mx-2">
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_info mt-4">
                                        <?php echo $this->pagination->getPagesCounter(); ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                        <div class="pagination-div float-end">
                                            <?php echo $this->pagination->getPagesLinks(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <h4>Salve uma primeira vez para ativar a inclusão de conteúdos</h4>
                        <?php endif; ?>     
                    </div> 
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_documento; ?>" />
    <input type="hidden" name="id_documento" value="<?php echo $this->item->id_documento; ?>" /> 
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />  
    <input type="hidden" name="boxchecked" value="0" />	
    <input type="hidden" name="controller" value="dmodel" />			
    <input type="hidden" name="view" value="dmodel" />
    <?php echo JHTML::_('form.token'); ?>	
</form>

<?php if( !empty($this->item->id_documento) ): ?>
<div class="modal fade" id="internalModal" data-bs-backdrop="static">
	<div class="modal-dialog modal-xl modal-simple modal-pricing">
    	<div class="modal-content modal-content">
            <div class="modal-body modal-items">    
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                <div class="rounded-top">
                    <iframe id="includeItem" src="" width="99.6%" height="100%" frameborder="0"></iframe>
                </div>
            </div>
    	</div>
  	</div>
</div>
<?php endif; ?>
