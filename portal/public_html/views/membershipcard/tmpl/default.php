<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_LIBRARIES .DS. 'qrcode' .DS. 'qrlib.php');

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');

JHtml::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');


jimport('joomla.image.resize');
$resize = new JResize();

if(isset($this->item->name)):
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
  
  $namecode = JFactory::getDate($this->item->validate_associado, $siteOffset)->toFormat('%Y',true) .  $this->item->id_associado;
  $code = str_replace('=', '', strrev( base64_encode(base64_encode('00000' . $namecode) ) ) );
  QRcode::png(  'https://portal.fgct.com.br/?view=autenticate&code=' . $code , JPATH_BASE . DS . 'cache' . DS . 'qrcode-' . $namecode .  '.png', 'L', 2.3, 2);
endif;

?>

<form method="post" name="adminForm" enctype="multipart/form-data" class="form-validate">
  <div class="row">
    <div class="col-12">
      <?php if(isset($this->item->name)): ?>
      <div class="card mb-4">
        <div class="card-header sticky-element d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
          <div class="row">
            <div class="col-md-12">
              <h5 class="card-title mb-sm-0 me-2">Pré-visualização da Carteira Digital</h5>
            </div>
          </div>                
          <div class="action-btns">
            <a  class="btn btn-outline-secondary" target="_blank" href="<?php echo JRoute::_('index.php?view=membershipcard&layout=print', false); ?>">Imprimir</a>
          </div>
        </div>
        <hr class="my-0" />
        <div class="col-12 p-5" >
          <div class="row">
            <div class="col-xxl-6 mb-3 d-flex justify-content-center justify-content-xxl-end align-items-center flex-column flex-sm-row" style="min-width:404px;">
              <div class="card shadow-none border-0 text-white" style="min-width:404px; height:256px">
                <img class="card-img front" src="<?php echo $imageFront; ?>" alt="Imagen Frente Carteira Digital">
                <div class="card-img-overlay card-img-overlay-front" style="margin-top:130px">
                  <span style="font-weight: 600; color:<?php echo !empty($this->item->color_front_carteira_digital) ? $this->item->color_front_carteira_digital : '#000';?>" ><?php echo strtoupper( $this->item->name);?></span>
                </div>
              </div>
            </div>
            <div class="col-xxl-6  mb-3 d-flex justify-content-center justify-content-xxl-start align-items-center flex-column flex-sm-row"  style="min-width:404px;">
              <div class="card shadow-none border-0 text-white" style="min-width:404px; height:256px">
                <img class="card-img back" src="<?php echo $imageBack; ?>" alt="Imagen Verso Carteira Digital">
                <div class="card-img-overlay card-img-overlay-back">
                  <span style="display:inline-block; line-height:32px; text-align:center; margin:0; margin-top:6px; width:355px; font-size:0.7375rem; color:<?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo !empty($this->item->clube) ? $this->item->clube : '-' ;?></span>
                  <span style="display:inline-block; line-height:32px; text-align:center; margin:0; margin-top:8px; width:260px; font-size:0.7375rem; color:<?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->name_cidade . '/' . $this->item->sigla_estado;?></span>
                  <span style="display:inline-block; line-height:32px; text-align:center; margin:0; margin-top:8px; margin-left:10px; width:82px; font-size:0.7375rem; color:<?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->id_associado;?></span>
                  <span style="display:inline-block; line-height:32px; text-align:center; margin:0; margin-top:8px; width:122px; font-size:0.7375rem; color:<?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo !empty($this->item->numcr_pf) ? $this->_data['values']->numcr_pf : '-' ;?></span>
                  <span style="display:inline-block; line-height:32px; text-align:center; margin:0; margin-top:8px; margin-left:10px; width:124px; font-size:0.7375rem; color:<?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->cpf_pf;?></span>
                  <span style="display:inline-block; line-height:32px; text-align:center; margin:0; margin-top:8px; margin-left:10px; width:82px; font-size:0.7375rem; color:<?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo JHtml::date(JFactory::getDate($this->item->validate_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT', true);?></span>
                  <img style="float:right; margin-top:10px; margin-right:-1px;" src="/cache/<?php echo 'qrcode-' . $namecode.  '.png';?>" />
                </div>
              </div>
            </div>   
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="card mb-4">
        <div class="card-header sticky-element d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
          <div class="row">
            <div class="col-md-12">
              <h5 class="card-title mb-sm-0 me-2">Sua carteira digital não está disponível</h5>
            </div>
          </div>                
        </div>
      </div>  
      <?php endif; ?>  
    </div>
  </div>

  <input type="hidden" name="controller" value="membershipcard" />
  <input type="hidden" name="view" value="membershipcard" />
  <?php echo JHTML::_('form.token'); ?>
</form>