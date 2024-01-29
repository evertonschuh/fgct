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
                            <h5 class="card-title mb-sm-0 me-2">Cadastro de Arma</h5>
                        </div>
                    </div>
                    <div class="action-btns">
                        <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary">Salvar</button>
                        <button type="button" onclick="Joomla.submitbutton('cancel')" class="btn btn-outline-secondary me-1">Sair</button>
                    </div>
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
                        <div class="mb-3 col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="numero_arma">Número:*</label>
                                <input type="text" class="form-control required" name="numero_arma" id="numero_arma" value="<?php echo $this->item->numero_arma; ?>" placeholder="<?php echo JText::_('Número') ?>">
                            </div>
                        </div> 
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="id_especie">Espécie:*</label>
                            <select id="id_especie" name="id_especie" class="select2 form-select required">
                                <?php if (empty($this->item->id_especie)) { ?>
                                <option disabled selected class="default" value=""><?php echo JText::_('- Espécies -'); ?></option>
                                <?php } ?>
                                <?php echo JHTML::_('select.options',  $this->especie, 'value', 'text',  $this->item->id_especie); ?>    
                            </select>    
                        </div>  
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="id_calibre">Calibre:*</label>
                            <select id="id_calibre" name="id_calibre" class="select2 form-select required">
                                <?php if (empty($this->item->id_calibre)) { ?>
                                <option disabled selected class="default" value=""><?php echo JText::_('- Calibres -'); ?></option>
                                <?php } ?>
                                <?php echo JHTML::_('select.options',  $this->calibre, 'value', 'text',  $this->item->id_calibre); ?>    
                            </select>    
                        </div>  
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="id_marca">Marca:*</label>
                            <select id="id_marca" name="id_marca" class="select2 form-select required">
                                <?php if (empty($this->item->id_marca)) { ?>
                                <option disabled selected class="default" value=""><?php echo JText::_('- Marcas -'); ?></option>
                                <?php } ?>
                                <?php echo JHTML::_('select.options',  $this->marca, 'value', 'text',  $this->item->id_marca); ?>    
                            </select>    
                        </div>   
                        <div class="mb-3 col-md-3">
                                <label class="form-label" for="modelo_arma">Modelo:</label>
                                <input type="text" class="form-control" name="modelo_arma" id="modelo_arma" value="<?php echo $this->item->modelo_arma; ?>" placeholder="Modelo">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="id_marca"><?php echo JText::_('Funcionamento:'); ?></label>
                            <select name="id_funcionamento"  id="id_funcionamento" class="form-control select2">
                                <option disabled selected class="default" value=""><?php echo JText::_('- Funcionamento -'); ?></option>
                                    <?php echo JHTML::_('select.options',  $this->funcionamento, 'value', 'text', $this->item->id_funcionamento ); ?>
                            </select>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="id_pais">País de Fabricação:</label>
                            <select name="id_pais"  id="id_pais" class="form-control select2">
                                <option disabled selected class="default" value=""><?php echo JText::_('- País -'); ?></option>
                                    <?php echo JHTML::_('select.options',  $this->pais, 'value', 'text', $this->item->id_pais ); ?>
                            </select>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="acabamento_arma">Acabamento:</label>
                            <input type="text" class="form-control" name="acabamento_arma" id="acabamento_arma" value="<?php echo $this->item->acabamento_arma; ?>" placeholder="<?php echo JText::_('Acabamento') ?>">
                        </div>
                        <?php /*
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="quant_cano_arma">Quantidade de Canos:</label>
                            <select name="quant_cano_arma" class="form-control select2">
                                <option selected class="default" value=""><?php echo JText::_('- Quantidade de Canos -'); ?></option>
                                    <?php
                                    for($i=1;$i<=5;$i++) 
                                    $quant_cano_arma[] = JHTML::_('select.option', $i , JText::_( $i ), 'value', 'text' );
                                    echo JHtml::_('select.options',  $quant_cano_arma, 'value', 'text', $this->item->quant_cano_arma);
                                    ?>
                            </select>
                        </div> 
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <label><?php echo JText::_('Comprimento:'); ?></label>
                            <input type="text" class="form-control" name="comp_cano_arma" id="comp_cano_arma" value="<?php echo $this->item->comp_cano_arma; ?>" placeholder="<?php echo JText::_('Comprimento') ?>">
                        </div>
                        <div class=" col-sm-12 col-md-3 col-lg-3">
                                <label><?php echo JText::_('Capacidade:'); ?></label>
                                <input type="text" class="form-control" name="cap_carreg_arma" id="cap_carreg_arma" value="<?php echo $this->item->cap_carreg_arma; ?>" placeholder="<?php echo JText::_('Capacidade') ?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo JText::_('Número de Raias:*'); ?></label>
                            <select name="num_raia_arma" class="form-control select2">
                                <option selected class="default" value=""><?php echo JText::_('- Número de Raias -'); ?></option>
                                    <?php
                                    for($i=1;$i<=10;$i++) 
                                    $num_raia_arma[] = JHTML::_('select.option', $i , JText::_( $i ), 'value', 'text' );
                                    echo JHtml::_('select.options',  $num_raia_arma, 'value', 'text', $this->item->num_raia_arma);
                                    ?>
                            </select>
                        </div>
                        <div class=" col-sm-12 col-md-3 col-lg-3">
                            <label><?php echo JText::_('Sentido:'); ?></label>
                            <select name="sentido_arma" class="form-control select2">
                                <option selected class="default" value=""><?php echo JText::_('- Sentido -'); ?></option>
                                    <?php 
                                    $sentido_arma[] = JHTML::_('select.option', '1', JText::_( 'À Direita' ), 'value', 'text' );
                                    $sentido_arma[] = JHTML::_('select.option', '2', JText::_( 'À Esquerda' ), 'value', 'text' );
                                    echo JHtml::_('select.options',  $sentido_arma, 'value', 'text', $this->item->sentido_arma);
                                    ?>
                            </select>
                        </div>
                        */ ?>

                        <div class="col-md-12 mt-2">
                            <h6 class="card-title mb-sm-0 me-2">Registro da Ama</h6>
                            <hr class="my-1 mb-3" />
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="registro_tipo_arma"><?php echo JText::_('Local do Registro:'); ?></label>
                            <select name="registro_tipo_arma" class="form-control select2">
                                <option selected class="default" value=""><?php echo JText::_('- Local do Registro -'); ?></option>
                                    <?php 
                                    $registro_tipo_arma[] = JHTML::_('select.option', '1', JText::_( 'Exército (Sigma)' ), 'value', 'text' );
                                    $registro_tipo_arma[] = JHTML::_('select.option', '2', JText::_( 'Polícia Federal (Sinarm)' ), 'value', 'text' );
                                    echo JHtml::_('select.options',  $registro_tipo_arma, 'value', 'text', $this->item->registro_tipo_arma);
                                    ?>
                            </select>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="registro_arma"><?php echo JText::_('Número do Registro:'); ?></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                name="registro_arma" 
                                id="registro_arma" 
                                value="<?php echo $this->item->registro_arma; ?>"  
                                placeholder="<?php echo JText::_('Número do Registro') ?>"
                            >
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="data_registro_arma">Data de Registro:</label>
                            <div class="input-group input-group-merge date">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="data_registro_arma"
                                    value="<?php if( $this->item->data_registro_arma ) echo JHtml::date(JFactory::getDate($this->item->data_registro_arma, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" 
                                    placeholder="<?php echo JText::_('Data de Registro') ?>" 
                                />
                                <button class="btn btn-outline-primary" type="button">
                                    <i class="bx bx-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label" for="vencimento_registro_arma">Vencimento do Registro:</label>
                            <div class="input-group input-group-merge date">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="vencimento_registro_arma" 
                                    value="<?php if( $this->item->vencimento_registro_arma ) echo JHtml::date(JFactory::getDate($this->item->vencimento_registro_arma, $siteOffset)->toISO8601(), 'DATE_FORMAT'); ?>" 
                                    placeholder="<?php echo JText::_('Data de Vencimento') ?>" 
                                />
                                <button class="btn btn-outline-primary" type="button">
                                    <i class="bx bx-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="id_acervo">Acervo:</label>
                            <select name="id_acervo"  id="id_acervo" class="form-control select2 required">
                                <option selected class="default" value=""><?php echo JText::_('- Acervo -'); ?></option>
                                <?php echo JHTML::_('select.options',  $this->acervo, 'value', 'text', $this->item->id_acervo ); ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>   
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_arma; ?>" />
    <input type="hidden" name="id_pf" value="<?php echo $this->item->id_arma; ?>" />
    <input type="hidden" name="controller" value="weapon" />
    <input type="hidden" name="view" value="weapon" />
    <?php echo JHTML::_('form.token'); ?>
</form>