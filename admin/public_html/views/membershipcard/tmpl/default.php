<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_LIBRARIES .DS. 'qrcode' .DS. 'qrlib.php');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');


jimport('joomla.image.resize');
$resize = new JResize();

$nofile = $resize->resize(JPATH_IMAGES .DS. 'carteira.png' , 404, 256, 'cache/carteira.png', 'manterProporcao'); 


if ( !empty( $this->item->image_front_carteira_digital ) && file_exists(JPATH_CDN.DS.'images'.DS.'carteiradigital'.DS.$this->item->image_front_carteira_digital)):
  $imageFront = $resize->resize(JPATH_CDN.DS.'images'.DS.'carteiradigital'.DS.$this->item->image_front_carteira_digital, 404, 256, 'cache/' . $this->item->image_front_carteira_digital, 'manterProporcao');
else:
  $imageFront = $nofile; 
endif; 

if ( !empty( $this->item->image_back_carteira_digital ) && file_exists(JPATH_CDN.DS.'images'.DS.'carteiradigital'.DS.$this->item->image_back_carteira_digital)):
  $imageBack = $resize->resize(JPATH_CDN.DS.'images'.DS.'carteiradigital'.DS.$this->item->image_back_carteira_digital, 404, 256, 'cache/' . $this->item->image_back_carteira_digital, 'manterProporcao');
else:
  $imageBack = $nofile; 
endif; 

if(!file_exists(JPATH_BASE . DS . 'cache' . DS .  'qr-code-example.png'))
  QRcode::png(  'https://portal.fgct.com.br/?view=autenticate&code=testecodigoexemplofgct', JPATH_BASE . DS . 'cache' . DS .  'qr-code-example.png', 'L', 2.3, 2);

$this->item->status_carteira_digital
?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
  <div class=" flex-grow-1">
    <div class="row">
      <!-- CARD IMAGEM USUÁRIO -->
      <div class="col-xxl-3 col-lg-4 order-0">
        <div class="card mb-4">
          <div class="card-header sticky-element d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
            <div class="row">
              <div class="col-md-12">
                <h5 class="card-title mb-sm-0 me-2">Configurações</h5>
              </div>
            </div>  
            <div class="action-btns">
              <?php if($this->item->status_carteira_digital < 0 ): ?>
                <span class="badge bg-label-secondary mt-3">
                  <span class="badge bg-label-secondary">
                      Item na Lixeira
                  <span>
                </span>
                <input name="status_carteira_digital" value="<?php echo $this->item->status_carteira_digital;?>" type="hidden">
              <?php else: ?>
              <div class="form-check form-switch mt-3">
                <input value="1" class="form-check-input status-check" name="status_carteira_digital" <?php echo $this->item->status_carteira_digital == 1 ? 'checked="checked"' :'';?> type="checkbox" id="checkStatus">
                <label class="form-check-label" for="checkStatus"><?php echo $this->item->status_carteira_digital == 1 ? 'Ativo' : 'Inativo';?></label>
              </div>
              <?php endif; ?>
            </div>              
          </div>
          
          <hr class="my-0" />
          <div class="card-body pt-12">
            <div class="user-avatar-section">
              <div class="row">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <div class="row">
                    <div class="mb-3">
                      <div class="button-wrapper">
                        <label for="uploadfront" class="btn btn-primary me-2 mb-3 upload-image-front w-100" tabindex="0">
                            <span class="d-none d-sm-block">Enviar Imagem Frente
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input
                                type="file"
                                id="uploadfront"
                                class="account-file-input"
                                data-controller="front"
                                hidden
                                name="image_front_carteira_digital_new"
                                accept="image/png, image/jpeg, image/gif"
                            />
                        </label>
                        <button type="button"  data-controller="front" class="btn btn-outline-danger me-2 mb-3 w-100 skip-upload skip-upload-front" style="display:none;">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Desistir do Envio da Frente</span>
                        </button>
                        <input type="hidden" id="imgSRCfront" value="<?php echo $imageFront; ?>" />
                      </div> 

                      <div class="button-wrapper">
                        <label for="uploadback" class="btn btn-primary me-2 mb-3 upload-image-back w-100" tabindex="0">
                            <span class="d-none d-sm-block">Enviar Imagem Verso
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input
                                type="file"
                                id="uploadback"
                                class="account-file-input"
                                data-controller="back"
                                hidden
                                name="image_back_carteira_digital_new"
                                accept="image/png, image/jpeg, image/gif"
                            />
                        </label>
                        <button type="button" data-controller="back" class="btn btn-outline-danger me-2 mb-3 w-100 skip-upload skip-upload-back" style="display:none;">
                            <i class="bx bx-reset d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Desistir do Envio do Verso</span>
                        </button>
                        <input type="hidden" id="imgSRCback" value="<?php echo $imageBack; ?>" />
                      </div> 
                    </div> 
                    <div class="mb-3">
                       <label for="color_front_carteira_digital" class="form-label">Cor da Fonte (Frente)</label>
                       <input name="color_front_carteira_digital" data-controller="front" class="form-control required color-picker" value="<?php echo !empty($this->item->color_front_carteira_digital) ? $this->item->color_front_carteira_digital : '#000';?>">
                    </div>
                    <div class="mb-3">
                       <label for="color_back_carteira_digital" class="form-label">Cor da Fonte (Verso)</label>
                       <input name="color_back_carteira_digital" data-controller="back" class="form-control required color-picker" value="<?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="id_anuidade">Anuidade</label>
                        <select id="id_anuidade" name="id_anuidade" class="select2 required form-select">
                            <option disabled selected class="default" value=""><?php echo JText::_('- Anuidades -'); ?></option>
                            <?php echo JHTML::_('select.options',  $this->anuidades, 'value', 'text',  $this->item->id_anuidade); ?>    
                        </select>    
                    </div>  
                    <div class="mb-3">
                        <label class="form-label" for="id_associado_tipo">Tipo de Filiação</label>
                        <select id="id_associado_tipo" name="id_associado_tipo" class="select2 required form-select">
                            <option disabled selected class="default" value=""><?php echo JText::_('- Tipos de Filiação -'); ?></option>
                            <?php
                              $associadoTipos[] = JHTML::_('select.option', '-1', JText::_( 'Todos' ), 'value', 'text' );
                              $associadoTipos = array_merge($associadoTipos, $this->associadoTipos); 
                              echo JHTML::_('select.options',  $associadoTipos, 'value', 'text',  $this->item->id_associado_tipo); ?>    
                        </select>    
                    </div>                     
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- FIM CARD IMAGEM USUÁRIO -->
      <!-- CARD INFOS USUÁRIO -->
      <div class="col-xxl-9 col-lg-8 order-1 mt-3 mt-lg-0">
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-4">
              <div class="card-header sticky-element d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                <div class="row">
                  <div class="col-md-12">
                    <h5 class="card-title mb-sm-0 me-2">Pré-visualização da Carteira Digital</h5>
                  </div>
                </div>                
                <div class="action-btns">
                  <button type="button" onclick="Joomla.submitbutton('save')" class="btn btn-primary">Salvar</button>
                  <button type="button" onclick="Joomla.submitbutton('cancel')" class="btn btn-outline-secondary me-1">Sair</button>
                </div>
              </div>
              <hr class="my-0" />
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-xxl-6 mb-3 d-flex justify-content-center align-items-center flex-column flex-sm-row">
                      <div class="card shadow-none border-0 text-white" style="width:404px; height:256px">
                        <img class="card-img front" src="<?php echo $imageFront; ?>" alt="Imagen Frente Carteira Digital">
                        <div class="card-img-overlay card-img-overlay-front" style="margin-top:130px">
                          <span style="font-weight: 600; color:<?php echo !empty($this->item->color_front_carteira_digital) ? $this->item->color_front_carteira_digital : '#000';?>" >NOME DO ASSOCIADO FGCT</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-xxl-6  mb-3 d-flex justify-content-center align-items-center flex-column flex-sm-row">
                      <div class="card shadow-none border-0 text-white" style="width:404px; height:256px">
                        <img class="card-img back" src="<?php echo $imageBack; ?>" alt="Imagen Verso Carteira Digital">
                        <div class="card-img-overlay card-img-overlay-back">
                            <span style="display: inline-block; line-height:20px; text-align: center; margin: 10px 14px; width:328px;  font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>">Nome do Clube do Associado</span>
                            <span style="display: inline-block; line-height:22px; text-align: center; margin: 10px 12px; width:240px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>">Cidade do Associado</span>
                            <span style="display: inline-block; line-height:22px; text-align: center; margin: 10px 0 0 8px; width:78px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>">000000</span>
                            <span style="display: inline-block; line-height:22px; text-align: center; margin: 10px 0 0 6px; width:120px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>">000.000.000-00</span>
                            <span style="display: inline-block; line-height:22px; text-align: center; margin: 10px 0 0 8px; width:120px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>">000.000.000-00</span>
                            <span style="display: inline-block; line-height:22px; text-align: center; margin: 10px 0 0 10px; width:80px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>">00/00/0000</span>
                            <img style="float:right;margin-top: 16px;margin-right: -1px;" src="/cache/qr-code-example.png" />
                        </div>
                      </div>
                    </div>
                  </div>   
                </div>   
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- FIM CARD IMAGEM USUÁRIO -->
    </div>
  </div>


  <input type="hidden" name="task" value="" />
  <input type="hidden" name="cid[]" id="cid" value="<?php echo $this->item->id_carteira_digital; ?>" />
  <input type="hidden" name="id_carteira_digital" value="<?php echo $this->item->id_carteira_digital; ?>" />
  <input type="hidden" name="image_front_carteira_digital" value="<?php echo $this->item->image_front_carteira_digital; ?>" />
  <input type="hidden" name="image_back_carteira_digital" value="<?php echo $this->item->image_back_carteira_digital; ?>" />
  <input type="hidden" name="id_user" value="<?php echo $this->item->id_user; ?>" /> 
  <input type="hidden" name="controller" value="membershipcard" />
  <input type="hidden" name="view" value="membershipcard" />
  <?php echo JHTML::_('form.token'); ?>
</form>