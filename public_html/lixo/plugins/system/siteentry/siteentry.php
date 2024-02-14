<?php
/**
 * @version 2.0.0
 * @package Siteentry
 * @subpackage plg_siteentry
 * @copyright Copyright (C) 2010 webconstruction.ch. All rights reserved.
 * @license GNU/GPL, see LICENSE.txt
 * @author Milton Pfenninger
 * @contact info@webconstruction.ch
 * @website www.webconstruction.ch
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_THEMES_NATIVE .DS. 'includes' .DS. 'trataimagem.php');

jimport( 'joomla.plugin.plugin' );

class plgSystemSiteentry extends JPlugin
{
	var $_render = false;

	function plgSystemSiteentry(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}
	
	function onAfterDispatch()
	{
		$mainframe = JFactory::getApplication();

		if($mainframe->isAdmin()) {
			return;
		}

		$type = intval($this->params->get('type',0));
		$cookietime = intval($this->params->get('cookietime',0));
		
		$session =JFactory::getSession();
				
		$par = array();
		
		$preview = JRequest::getVar('siteentrypreview',false);
		$session =JFactory::getSession();
		if ($preview=='true'){
			$preview = true;
			$session->set('modaldone','true','plg_siteentry');
			setcookie('modaldone', 'true',time() + 300*3600,'/');
		}else{
			$preview = false;
		}
		
		
		$time = $cookietime;
		if ($cookietime>0){
			$time = time()+($cookietime*3600);
		}
		
		if (!$preview){
			if ($type==0){
				$value = $session->get('modaldone',null,'plg_siteentry');
				$session->set('modaldone','true','plg_siteentry');
				setcookie('modaldone', 'false',$time,'/'); 
				if ($value=='true'){
					return;
				}
			}else{
				if (isset($_COOKIE['modaldone'])){
					$value = $_COOKIE['modaldone'];
				}else{
					$value = 'false';
				}
				setcookie('modaldone', 'true',$time,'/'); 
				if ($value=='true'){
					return;
				}
			}
		}
		
		$this->_render = true;

		$document = JFactory::getDocument();
		
		$closeafter = intval($this->params->get('closeafterseconds',0));
		if ($closeafter>0){
			$document->addScriptDeclaration('setTimeout(\'$("#siteentry").modal("hide");\','.($closeafter*1000).');');
		}
		
		$document->addScriptDeclaration($this->_wrapJS($globals));

		if ($preview){
			$session->set('modaldone','true','plg_siteentry');
			setcookie('modaldone', 'true', time() + (100 * 60 * 60),JURI::base(),'/');
		}
		
		$document->addStyleSheet( JURI::root().'plugins/system/siteentry/css/siteentry.css', 'text/css', null, array() );
		

	}
	
	function _wrapJS( $globals='')
	{
	 	return $globals.'$(window).load(function(){
				$("#siteentry").modal("show");
			});';
	}
	
	function onAfterRender()
	{
		if ($this->_render):
		
			$image = $this->params->get('image','');
			if (trim($image!='')) :
					
				$imageTMP = getimagesize(JPATH_MEDIA .DS. 'images' .DS. 'store' .DS. $image );
				
				if(!$imageTMP )
				return false;
				
				$redimeWidth = $imageTMP[0];
				$redimeHeight = $imageTMP[1];
				
				$trataimagem = new TrataImagem();
				$session = JFactory::getSession();
				$idSession = $session->getId();

				$popupWidth = intval($this->params->get('popupwidth',0));
				$popupHeight = intval($this->params->get('popupheight',0));
		
				if (trim($popupWidth)!=''){
					if( is_numeric($popupWidth) &&  $popupWidth > 0 )	
					$redimeWidth = $popupWidth;
				}
				if (trim($popupHeight)!=''){
					if( is_numeric($popupHeight) &&  $popupHeight > 0 )	
					$redimeHeight = $popupHeight;
				}
				
				$closeButton = '';
				if (intval($this->params->get('closebutton',1))==1){
					$closeButton = '<a id="sbox-btn-close" href="#" role="button" data-dismiss="modal" aria-hidden="true"></a>';
				}
					
				$backGroundTransparent = '';
				if (intval($this->params->get('tbackground',0))==1){
					$backGroundTransparent = ' modal-transparent';
				}
					
				$content = '<img id="image_article"  src="'. $trataimagem->resize(JPATH_MEDIA .DS. 'images' .DS. 'store' .DS. $image, $redimeWidth, $redimeHeight, 'cache/' . $image, 'manterProporcao') . '" style="width:100%" />';
				
				$target='';
				$linktarget = intval($this->params->get('linktarget',0));
				if($linktarget==1)
					$target=' target="_blank"';
					
				$link = $this->params->get('linkofimage','');
				if( !empty($link) ) {
			
					$_db	= JFactory::getDBO();
					$query = $_db->getQuery(true);
					$query->select($_db->quoteName('extension_id'));
					$query->from($_db->quoteName('#__extensions'));
					$query->where($_db->quoteName('type') . ' = ' . $_db->quote('plugin') );
					$query->where($_db->quoteName('folder') . ' = ' . $_db->quote('system') );		
					$query->where($_db->quoteName('element') . ' = ' . $_db->quote('siteentry') );			
					$_db->setQuery($query);
					$IDplugin = $_db->loadResult();
					
					
					$content = '<a '.$target.' href="'.$link.'">' . $content  .'</a>';
				}
				
				$html = JResponse::getBody();
				
				$html = str_replace('</body>',
									'<div class="modal fade" id="siteentry" tabindex="-1" role="dialog" >
										<div class="modal-dialog" style="width:80%; max-width:'.intval($redimeWidth).'px; max-height:'.intval($redimeHeight) . 'px;" >
											' . $closeButton . '
											<div class="modal-content'.$backGroundTransparent.'">
												' . $content . '
											</div>
										</div>
									</div>
									</body>',
									$html);
				
				$html = JResponse::setBody($html);

			endif;
		endif;
	}
}
