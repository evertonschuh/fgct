<?php
defined('_JEXEC') or die('Restricted access');

ob_start();

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



            <div class="row">
              <div class="col-xxl-6 mb-3 d-flex justify-content-center align-items-center flex-column flex-sm-row">
                <div class="card shadow-none border-0 text-white" style="width:404px; height:256px">
                  <img class="card-img front" src="<?php echo $imageFront; ?>" alt="Imagen Frente Carteira Digital">
                  <div class="card-img-overlay card-img-overlay-front" style="margin-top:130px">
                    <span style="font-weight: 600; color:<?php echo !empty($this->item->color_front_carteira_digital) ? $this->item->color_front_carteira_digital : '#000';?>" ><?php echo strtoupper( $this->item->name);?></span>
                  </div>
                </div>
              </div>
              <div class="col-xxl-6  mb-3 d-flex justify-content-center align-items-center flex-column flex-sm-row">
                <div class="card shadow-none border-0 text-white" style="width:404px; height:256px">
                  <img class="card-img back" src="<?php echo $imageBack; ?>" alt="Imagen Verso Carteira Digital">
                  <div class="card-img-overlay card-img-overlay-back">
                      <span style="display: inline-block; line-height:20px; text-align: center; margin: 10px 14px; width:328px;  font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->clube;?></span>
                      <span style="display: inline-block; line-height:22px; text-align: center; margin: 10px 12px; width:240px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->name_cidade . '/' . $this->item->sigla_estado;?></span>
                      <span style="display: inline-block; line-height:22px; text-align: center; margin: 10px 0 0 8px; width:78px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->id_associado;?></span>
                      <span style="display: inline-block; line-height:22px; text-align: center; margin: 9px 0 0 4px; width:120px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->numcr_pf;?></span>
                      <span style="display: inline-block; line-height:22px; text-align: center; margin: 9px 0 0 8px; width:120px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo $this->item->cpf_pf;?></span>
                      <span style="display: inline-block; line-height:22px; text-align: center; margin: 9px 0 0 10px; width:80px; font-size: 0.7375rem; color: <?php echo !empty($this->item->color_back_carteira_digital) ? $this->item->color_back_carteira_digital : '#000';?>"><?php echo JHtml::date(JFactory::getDate($this->item->validate_associado, $siteOffset)->toISO8601(), 'DATE_FORMAT', true);?></span>
                      <img style="float:right;margin-top: 16px;margin-right: -1px;" src="/cache/<?php echo 'qrcode-' . $namecode.  '.png';?>" />
                  </div>
                </div>
              </div>   
            </div>

<?php $this->returnPdfVar = ob_get_clean(); 

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Dompdf.php');
require_once(JPATH_LIBRARIES .DS. 'dompdf' .DS. 'vendor' .DS.'autoload.php');

$dompdf = new Dompdf();

$dompdf->loadHtml(mb_convert_encoding($this->returnPdfVar, 'HTML-ENTITIES', 'UTF-8'));

$dompdf->render();

$pdfdata = $dompdf->output();


header('Content-type: application/pdf');
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header('Content-Disposition: inline; filename="'.$this->item->name.'.pdf"');
header("Content-length: ".strlen(base64_decode($this->returnPdfVar)));
die(base64_decode($this->returnPdfVar));








?>