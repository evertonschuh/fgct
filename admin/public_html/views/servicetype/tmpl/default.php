<?php
defined('_JEXEC') or die('Restricted access'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
		
JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation'); 

/*
var $id_service_type = null;
var $status_service_type = null;
var $name_service_type = null;
var $theme_service_type = null;
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
                            <h5 class="card-title mb-sm-0 me-2">Tipo de Serviço</h5>
                            <?php if($this->item->status_service_type < 0 ): ?>
                                <span class="badge bg-label-secondary mt-3">
                                    <span class="badge bg-label-secondary">
                                        Item na Lixeira
                                    <span>
                                </span>
                                <input name="status_service_type" value="<?php echo $this->item->status_service_type;?>" type="hidden">
                            <?php else: ?>
                            <div class="form-check form-switch mt-3">
                                <input value="1" class="form-check-input" name="status_service_type" <?php echo $this->item->status_service_type == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_service_type == 1 ? 'Ativo' : 'Inativo';?></label>
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
                            <label for="name_service_type" class="form-label">Serviço</label>
                            <input
                                class="form-control required"
                                type="text"
                                id="name_service_type"
                                name="name_service_type"
                                value="<?php echo $this->item->name_service_type; ?>"
                                <?php echo $this->item->id_service_type ? 'autofocus' : ''; ?>
                            />
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="name_service_type" class="form-label">Código do Serviço</label>
                            <input
                                class="form-control"
                                type="text"
                                maxlength="15"
                                id="codigo_service_type"
                                name="codigo_service_type"
                                value="<?php echo $this->item->codigo_service_type; ?>"
                            />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="public_service_type" class="form-label">Disponível como Serviço</label>
                            <div class="form-check form-switch">
                                <input value="1" class="form-check-input" name="public_service_type" <?php echo $this->item->public_service_type == 1 ? 'checked="checked"' :'';?> type="checkbox" >
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="message_service_type" class="form-label">Ativar Descição do Serviço</label>
                            <div class="form-check form-switch">
                                <input value="1" class="form-check-input" name="message_service_type" <?php echo $this->item->message_service_type == 1 ? 'checked="checked"' :'';?> type="checkbox" >
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="automatic_service_type" class="form-label">Impressão automática de documento</label>
                            <div class="form-check form-switch">
                                <input value="1" class="form-check-input" name="automatic_service_type" <?php echo $this->item->automatic_service_type == 1 ? 'checked="checked"' :'';?> type="checkbox" >
                            </div>
                        </div>
                        <?php 
                            $disabled = 'disabled="disabled"';
                            $style = 'style="display:none;"';
                            if ( $this->item->automatic_service_type == 1 ){
                                $disabled = '';
                                $style = '';
                            } 
                        ?>
                        <div class="mb-3 col-12 col-lg-6 control-document" <?php echo $style; ?>>
                            <label for="id_documento" class="form-label">Documento</label>
                            <select name="id_documento" id="id_documento" class="form-control select2 required" <?php echo $disabled; ?>>
                                <option disabled selected class="default" value=""><?php echo JText::_('- Escolha um documento -'); ?></option>
                                <?php echo JHTML::_('select.options',  $this->documentos, 'value', 'text', $this->item->id_documento ); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" value="<?php echo $this->item->id_service_type; ?>" />
    <input type="hidden" name="id_service_type" value="<?php echo $this->item->id_service_type; ?>" /> 
    <input type="hidden" name="controller" value="servicetype" />			
    <input type="hidden" name="view" value="servicetype" />
    <?php echo JHTML::_('form.token'); ?>	
</form>
