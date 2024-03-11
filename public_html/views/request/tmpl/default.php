<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');

jimport('joomla.image.resize');
$resize = new JResize();

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

/*
if ( !empty( $this->item->image_arma )):
    $imageUser = $resize->resize(JPATH_CDN .DS. 'images' .DS. 'armas' .DS. $this->item->image_arma, 100, 100, 'cache/' . $this->item->image_arma, 'manterProporcao');
else:
    $imageUser = $resize->resize(JPATH_IMAGES .DS. 'noimageweapon.png' , 100, 100, 'cache/noimageweapon.png', 'manterProporcao'); 
endif;  
*/ 
?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header sticky-element  d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="card-title mb-sm-0 me-2">Serviço</h5>
                        </div>
                    </div>
                    <?php /*
                    <div class="action-btns">
                        <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary me-3">Salvar</button>
                        <button type="button" onclick="Joomla.submitbutton('cancel')" class="btn btn-outline-secondary me-1">Sair</button>
                    </div>
                    */ ?>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <?php /* imagem da arma
                    <div class="row mb-3">
                        <div class="col-md mb-md-0 mb-2">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img
                                    src="<?php echo $imageUser; ?>"
                                    alt="user-avatar"
                                    class="d-block rounded user-associado"
                                    height="100"
                                    width="100"
                                />
                                <input type="hidden" name="image_arma" value="<?php echo $this->item->image_arma; ?>" />
                                <input type="hidden" id="imgSRC" value="<?php echo $imageUser; ?>" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3 upload-image" tabindex="0">
                                        <span class="d-none d-sm-block">Enviar Imagem
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input
                                            type="file"
                                            id="upload"
                                            class="account-file-input"
                                            hidden
                                            name="image_pf_new"
                                            accept="image/png, image/jpeg, image/gif"
                                        />
                                    </label>
                                    <button type="button" class="btn btn-outline-danger me-2 mb-3 skip-upload" style="display:none;">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Desistir do Envio</span>
                                    </button>
                                    <div class="col mb-2">
                                        <?php if ( !empty( $this->item->image_pf )): ?>
                                        <fieldset class="checkbox-img-remove">
                                            <label>
                                                <input type="checkbox" name="remove_image_pf" value="1" />
                                                <?php echo JText::_('Marque e salve para remover a imagem!'); ?>
                                            </label>  
                                        </fieldset>
                                        <div class="clearfix"></div>
                                        <?php endif; ?> 
                                    </div>
                                    <p class="text-muted mb-0">Somente JPG, GIF ou PNG. Tamanho sugerido: 250x250 (px)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    */ ?>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="id_especie">Serviço:*</label>
                        <select class="form-select required select2" <?php echo ($this->item->id_service ? 'disabled="disabled"' : ''); ?> id="id_service_type" name="id_service_type">
                            <option disabled selected class="default" value=""><?php echo JText::_('- Serviços -'); ?></option>
                            <?php echo JHTML::_('select.options',  $this->services, 'value', 'text',  $this->item->id_service_type); ?>    
                        </select>    
                    </div> 
                </div>   
                <div class="row mb-3">   
                    <div class="col-md-12">
                        <label class="form-label" for="message_service">Mensagem:</label>
                        <textarea class="form-control" <?php echo (empty($this->item->id_service) ? 'disabled="disabled"' : ''); ?>  id="message_service" name="message_service" rows="4"></textarea>
                    </div>
                </div>
                <div class="action-btns mb-3">
                    <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary me-3">Enviar</button>
                    <button type="button" onclick="Joomla.submitbutton('cancel')" class="btn btn-outline-secondary me-1">Sair</button>
                </div>
                <?php if($this->item->id_service):?>
                <div class="row mb-3">
                    <div class="col-xl-12 col-lg-7 col-md-7">
                        <!-- Activity Timeline -->
                        <div class="card card-action mb-4">
                            <div class="card-header align-items-center">
                                <h5 class="card-action-title mb-0"><i class="bx bx-list-ul me-2"></i>Histórico do Serviço</h5>
                            </div>
                            <div class="card-body">
                                <ul class="timeline ms-2">
                                    <?php foreach($this->serviceMaps as $i => $seriveMap): ?>
                                    <li class="timeline-item timeline-item-transparent">
                                        <span class="timeline-point-wrapper">
                                            <span class="timeline-point timeline-point-<?php echo $seriveMap->color_service_stage ?>"></span>
                                        </span>
                                        <div class="timeline-event">
                                            <div class="timeline-header mb-1">
                                                <h6 class="mb-0"><?php echo $seriveMap->name_service_stage ?></h6>
                                                <small class="text-muted"><?php echo JHtml::date(JFactory::getDate($seriveMap->update_service, $siteOffset)->toISO8601(true), 'DATE_FORMAT_DATATIME'); ?></small>
                                            </div>
                                            <?php if(!empty($seriveMap->title_service)): ?>
                                            <p class="mb-2"><?php echo $seriveMap->title_service ?></p>
                                            <?php endif; ?>
                                            <?php if(!empty($seriveMap->message_service)): ?>
                                            <p class="mb-2 wrap-line"><?php echo $seriveMap->message_service ?></p>
                                            <?php 
                                                if ( !empty( $seriveMap->image_pf ) && file_exists(JPATH_CDN .DS. 'images' .DS. 'avatar' .DS. $seriveMap->image_pf)):
                                                    $imageUser = $resize->resize(JPATH_CDN .DS. 'images' .DS. 'avatar' .DS. $seriveMap->image_pf, 100, 100, 'cache/' . $seriveMap->image_pf, 'manterProporcao');
                                                else:
                                                    $imageUser = $resize->resize(JPATH_IMAGES .DS. 'noimageuser.png' , 100, 100, 'cache/noimageuser.png', 'manterProporcao'); 
                                                endif;   
                                            ?>
                                            <img src="<?php echo $imageUser; ?>" alt="<?php echo $seriveMap->name; ?>"class="rounded-circle me-2" height="20" width="20"><small class="mb-2"><?php echo $seriveMap->name; ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                    <li class="timeline-end-indicator">
                                        <i class="bx bxs-check-circle text-success"></i>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>   
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_service; ?>" />
    <input type="hidden" name="controller" value="request" />
    <input type="hidden" name="view" value="request" />
    <?php echo JHTML::_('form.token'); ?>
</form>