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
                            <?php echo JHTML::_('select.options',  $this->services, 'value', 'text',  $this->item->id_service); ?>    
                        </select>    
                    </div> 
                </div>   
                <div class="row mb-3">   
                    <div class="col-md-12">
                        <label class="form-label" for="message_service">Mensagem:</label>
                        <textarea class="form-control" disabled="disabled" id="message_service" name="message_service" rows="4"></textarea>
                    </div>
                </div>
                <div class="action-btns">
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
                                        <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-warning"></span></span>
                                        <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-0">Client Meeting</h6>
                                            <small class="text-muted">Today</small>
                                        </div>
                                        <p class="mb-2">Project meeting with john @10:15am</p>
                                        <div class="d-flex flex-wrap">
                                            <div class="avatar me-3">
                                            <img src="../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle">
                                            </div>
                                            <div>
                                            <h6 class="mb-0">Lester McCarthy (Client)</h6>
                                            <span>CEO of Infibeam</span>
                                            </div>
                                        </div>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                    <?php /*
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-info"></span></span>
                                <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Create a new project for client</h6>
                                    <small class="text-muted">2 Day Ago</small>
                                </div>
                                <p class="mb-0">Add files to new design folder</p>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-primary"></span></span>
                                <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Shared 2 New Project Files</h6>
                                    <small class="text-muted">6 Day Ago</small>
                                </div>
                                <p class="mb-2">Sent by Mollie Dixon <img src="../../assets/img/avatars/4.png" class="rounded-circle ms-3" alt="avatar" height="20" width="20"></p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="javascript:void(0)" class="me-3">
                                    <img src="../../assets/img/icons/misc/pdf.png" alt="Document image" width="20" class="me-2">
                                    <span class="h6">App Guidelines</span>
                                    </a>
                                    <a href="javascript:void(0)">
                                    <img src="../../assets/img/icons/misc/doc.png" alt="Excel image" width="20" class="me-2">
                                    <span class="h6">Testing Results</span>
                                    </a>
                                </div>
                                </div>
                            </li>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point-wrapper"><span class="timeline-point timeline-point-success"></span></span>
                                <div class="timeline-event pb-0">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Project status updated</h6>
                                    <small class="text-muted">10 Day Ago</small>
                                </div>
                                <p class="mb-0">Woocommerce iOS App Completed</p>
                                </div>
                            </li>
                            */ ?>
                            <li class="timeline-end-indicator">
                                <i class="bx bx-check-circle "></i>
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