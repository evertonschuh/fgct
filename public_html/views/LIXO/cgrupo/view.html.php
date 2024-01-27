<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

include_once(JPATH_SITE . DS . 'views' . DS . 'cgrupo' . DS . 'language.scrips.php');

class EASistemasViewCGrupo extends JView
{
	public function display($tpl = null)
	{
		//// Set the toolbar
		$this->addToolBar();

		$document = JFactory::getDocument();


		$document->addScriptDeclaration("jQuery(document).ready(function(){
											jQuery('.form-switch .form-check-input').click(function(){
												if(jQuery(this).is(':checked')){
													jQuery(this).next('.form-check-label').html('Ativo');
												}
												else{
													jQuery(this).next('.form-check-label').html('Inativo');
												}
											}); 
										});");


		$document->addScriptDeclaration('window.Helpers.initCustomOptionCheck();');

		$this->item = $this->get('Item');
		$this->atividades = $this->get('Atividades');

		if( empty($this->item->id_corredor_grupo) )
			JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / Grupos de Corrida / </span> ' . JText::_('Novo Grupo'));
		else
			JToolBarHelper::title('<span class="text-muted fw-light">CADASTRO / Grupos de Corrida / </span> ' . $this->item->name_corredor_grupo);

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