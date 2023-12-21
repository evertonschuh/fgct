<?php 
/**
 * @version 1.1.0
 * @package Joomla
 * @subpackage Site Entry
 * @copyright Copyright (C) 2010 webconstruction.ch. All rights reserved.
 * @license GNU/GPL, see LICENSE.txt
 * @contact info@webconstruction.ch
 * @website www.webconstruction.ch
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

// Check to ensure this file is included in Joomla!

require_once(JPATH_THEMES_NATIVE .DS. 'includes' .DS. 'trataimagem.php');

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

class JFormFieldImagePopUp extends JFormField
{
	protected $type = 'imagepopup';

	protected function getInput()
	{
		
		$html .= 	'
			<div class="row">
				<div class="col-md-12 open-midia">
					<div class="clearfix"></div>
		';
		if ( !empty( $this->value ) ) :
		$html .=	'<img class="img-responsive img-thumbnail" src="/media/images/store/' .  $this->value . '" />';
		else:
		$html .=	'<img class="img-responsive img-thumbnail" src="/admin/views/system/images/no_image.jpg" />';
		endif; 
		$html .=	'<div class="clearfix"></div> 
					<input type="hidden" name="'. $this->name . '" id="'. $this->element['name'] . '" value="'. $this->value . '" >
					<button type="button" class="btn btn-sm btn-default open-midia-mananger">Selecionar Imagem</button>     
					<div class="clearfix"></div>
				</div>
			</div>
			<div tabindex="-1" class="modal fade" id="midiaModal" role="dialog">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
							<h5 class="modal-title">Gerenciar Imagem</h5>
						</div>
						<div class="modal-body modal-items">
							<div class="row" id="loadImg">
								<div class="col-xs-12 text-center">
								
									<p>Carregando...</p>
									<img src="views/system/images/bx-slider/bx_loader.gif" />
								</div>
							</div>
							<iframe id="includeItem" src="" width="99.6%" height="100%" frameborder="0"></iframe>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
							<button type="button" class="btn btn-primary" id="insertImg" >Inserir</button>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
			jQuery(document).on(\'click\', \'.open-midia-mananger\', function(){
				buttonImg = this;
				jQuery(\'#includeItem\').hide();
				jQuery(\'#loadImg\').show();
				jQuery(\'#includeItem\').attr("src","index.php?view=imgmanager&layout=modal&tmpl=modal");
				jQuery(\'#midiaModal\').modal({show:true})
				jQuery(\'#includeItem\').on(\'load\', function() {
					jQuery(\'#includeItem\').show();
					jQuery(\'#includeItem\').height( jQuery(\'#includeItem\').contents().find("body").height()+ 30);
					jQuery(\'#loadImg\').hide();
				});
			});
			
			jQuery(\'#insertImg\').on(\'click\', function() {
				var img = jQuery(\'#includeItem\').contents().find(\'.imgLayerSelect:checked\').val();
				if(img)	{
					jQuery(\'#midiaModal\').modal(\'toggle\');
					var item = jQuery(\'#'. $this->element['name'] . '\');
					item.val(img);
					item.parent().find(\'.img-thumbnail\').attr(\'src\', \'/media/images/store/\'+img);
				}
				else
				{
					alert( \'É necessário selecionar alguma imgem para inserir\');
				}
			});
			</script>
			';
		
	/*	
		
		$html .= 	'
						<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">  
							<input type="file" name="image_popup"  id="image_popup" accept=".png, .jpg, .jpeg" />                                
							<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '" />
						</div>
					</div>
					</div>';
									
		$html .=	'<div class="form-group col-xs-12 col-sm-12 col-md-10 col-lg-8">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">&nbsp;</div>
							<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
								<div class="image-popup">';
						
		if ( !empty( $this->value ) ) 
		{	 
			$html .=	'<img id="image_article"  src="'. $trataimagem->resize(JPATH_MIDIAS .DS. 'banners' .DS. $this->value, 500, 500, 'cache/' . $idSession . '/' . $this->value, 'manterProporcao') . '" />';
		}
		else 
		{
			$html .=	'<img src="'. $this->trataimagem->resize(JPATH_PLUGINS .DS. 'system' .DS. 'siteentry' .DS. 'images' .DS. 'popupno.jpg' , 500, 500, 'cache/logo100px.png', 'manterProporcao') . '" />';
		}
			$html .=			'</div> 
							</div>
						';
		
*/
		
		
		return $html;
		
		
		
		
		
		
		
	}
}