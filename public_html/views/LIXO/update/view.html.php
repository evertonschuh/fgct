<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once(JPATH_SITE . DS . 'views' . DS . 'associado' . DS . 'language.scrips.php');

class EASistemasViewAssociado extends JView
{
	public function display($tpl = null)
	{
		//// Set the toolbar
		$this->addToolBar();

		$document = JFactory::getDocument();


		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');

		$document->addStyleSheet('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.js');




		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.css');
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.js');

		$document->addScript('/assets/js/jquery.mask.js');

		//$document->addstylesheet('/assets/css/bootstrap-datetimepicker.css');
		//$document->addScript('/assets/js/moment-with-locales.js');
		//$document->addScript('/assets/js/bootstrap-datetimepicker.js');

		//$document->addScript('/assets/js-custom/corredor.js');
		$document->addScript('/assets/js-custom/associado.js');
	


		$document->addScriptDeclaration('window.Helpers.initCustomOptionCheck();');

		$this->item = $this->get('Item');
		$this->estados = $this->get('Estados');
		$this->cidades = $this->get('Cidades');





		if (!empty($this->item->name_pf)) :
		//$document->addStyleSheet('views/system/css/jquery.fileupload.css');
		//$document->addStyleSheet('views/system/css/jquery.fileupload-ui.css');
		//$document->addScript('views/system/js/jquery.ui.widget.js');
		//$document->addStyleSheet('views/system/app-assets/css/pages/page-account-settings.css');
		//$document->addStyleSheet('views/system/app-assets/css/pages/page-contact.css');
		//$document->addScript('views/system/app-assets/js/scripts/page-account-settings.js');
		//$document->addStyleSheet('views/system/app-assets/vendors/select2/select2.min.css');
		//$document->addStyleSheet('views/system/app-assets/vendors/select2/select2-materialize.css');
		//$document->addScript('views/system/app-assets/js/scripts/page-account-settings.js');
		//$document->addScript('views/system/app-assets/vendors/select2/select2.full.min.js');
		//$document->addScript('views/system/js/tmpl.min.js');
		//$document->addScript('views/system/js/jquery.iframe-transport.js');
		//$document->addScript('views/system/js/jquery.fileupload.js');
		//$document->addScript('views/system/js/jquery.fileupload-process.js');

		//$document->addScript('views/system/js/jquery.fileupload-ui.js');
		//$document->addScript('views/system/js/pf-upload.js');
		endif;

		if( empty($this->item->name) )
			JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / Associados / </span> ' . JText::_('Novo Associado'));
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / Associados / </span> ' . $this->item->name);


		// Display the template*/
		parent::display($tpl);
	}

	protected function addToolBar()
	{

		JToolBarHelper::apply();
		//JToolBarHelper::divider();	
		JToolBarHelper::save();
		//JToolBarHelper::divider();										   
		JToolBarHelper::cancel();
	}
}