<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once(JPATH_SITE . DS . 'views' . DS . 'profile' . DS . 'language.scrips.php');

class EASistemasViewprofile extends JView
{
	public function display($tpl = null)
	{
		//// Set the toolbar

		$document = JFactory::getDocument();


		$document->addStyleSheet('/assets/vendor/libs/select2/select2.css');
		$document->addScript('/assets/vendor/libs/select2/select2.js');

		$document->addStyleSheet('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js');
		$document->addScript('/assets/vendor/libs/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.js');
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.css');
		$document->addStyleSheet('/assets/vendor/libs/bootstrap-select/bootstrap-select.js');

		$document->addScript('/assets/js/jquery.mask.js');
		$document->addScript('/assets/js-custom/profile.js');
	


		$document->addScriptDeclaration('window.Helpers.initCustomOptionCheck();');

		$this->item = $this->get('Item');
		$this->estados = $this->get('Estados');
		$this->cidades = $this->get('Cidades');
		$this->ufs = $this->get('Ufs');

		if($this->item->id_tipo==0): 
			$this->estadoCivil = $this->get('EstadoCivil');
			$this->cidadesNasceu = $this->get('CidadesNasceu');
			$this->clubes = $this->get('Clubes');

		endif;


		JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / Meu Perfil / </span> ' . $this->item->name);


		// Display the template*/
		parent::display($tpl);
	}


}