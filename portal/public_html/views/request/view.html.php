<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once (JPATH_SITE.DS.'views'.DS.'request'.DS.'language.scrips.php');

class EASistemasViewRequest extends JView 
{
	public function display($tpl = null)
	{	


		$this->item = $this->get('Item');		
		$this->services = $this->get('Services');
		$this->serviceMaps = $this->get('ServiceMaps');

		$document 			=  JFactory::getDocument();

		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');
		$document->addScript('/assets/js-custom/request.js');
		$document->addStyleDeclaration('.wrap-line {
											white-space: pre-wrap;
											overflow-wrap: break-word;
										}');



		if(isset($this->services) && count($this->services)>0):

			$script ="jQuery(document).ready(function(){
						jQuery('#id_service_type').change(function(){

							switch(jQuery('#id_service_type').val()) {
							";
			foreach($this->services as $i => $service)
				if($service->script =='1')
					$script .="case '".$service->value."':
								jQuery('#message_service').prop('disabled', false);
								break;";

			$script .="			default:
									jQuery('#message_service').val('');
									jQuery('#message_service').prop('disabled', true);
								break;
								}
							});
						});";
						
			$document->addScriptDeclaration($script);
		endif;



		if( empty($this->item->id_service) )
			JToolBarHelper::title('<span class="text-muted fw-light">FGCT Digital / Serviços /</span> Solicitar Serviço');
		else
			JToolBarHelper::title('<span class="text-muted fw-light">FGCT Digital / Serviços /</span> Histórico Serviço - ' . $this->item->id_service);


		parent::display($tpl);	
		
		
	}

}

