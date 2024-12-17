<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );

jimport( 'joomla.application.component.model' );

class EASistemasModelMemberShipCard extends JModel {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	
	function __construct()
	{
        parent::__construct();
		
		$this->_db	= JFactory::getDBO();		
		$this->_app	 = JFactory::getApplication(); 
		$this->_user = JFactory::getUser();
		$this->_id_user = $this->_user->get('id');

		$this->_siteOffset = $this->_app->getCfg('offset');

		$array  	= JRequest::getVar( 'cid', array(0), '', 'array');

		JRequest::setVar( 'cid', $array[0] );
		$this->setId( (int) $array[0] );

	}
	
	function setId( $id) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getItem()
	{
		if (empty($this->_data))
		{			
			$query = $this->_db->getQuery(true);	
			$query->select($this->_db->quoteName(array('id_associado', 
														'cpf_pf', 
														'numcr_pf', 
														'name_cidade',
														'sigla_estado',
														'socio.name',
														'compressed_air_pf', 
														'copa_brasil_pf',
														'validate_associado' 
													)));	

			$query->select('clube.name AS clube');
			$query->from($this->_db->quoteName('#__intranet_associado'));
			$query->innerJoin($this->_db->quoteName('#__intranet_pf') . 'USING('.$this->_db->quoteName('id_user').')');
			$query->innerJoin($this->_db->quoteName('#__intranet_estado') . 'USING('.$this->_db->quoteName('id_estado').')');
			$query->innerJoin($this->_db->quoteName('#__intranet_cidade') . 'USING('.$this->_db->quoteName('id_estado').','.$this->_db->quoteName('id_cidade').')');
			$query->innerJoin($this->_db->quoteName('#__users') . ' AS socio ON('.$this->_db->quoteName('id_user').'='.$this->_db->quoteName('id').')');
			$query->leftJoin($this->_db->quoteName('#__users') . ' AS clube ON('.$this->_db->quoteName('id_clube').'='.$this->_db->quoteName('clube.id').')');
			$query->where( $this->_db->quoteName('id_user') . '=' . $this->_db->quote( $this->_user->get('id') ) );
			$query->where( $this->_db->quoteName('status_associado') . '=' . $this->_db->quote( '1' ) );
			$query->where( $this->_db->quoteName('status_pf') . '=' . $this->_db->quote( '1' ) );
			$query->where( $this->_db->quoteName('block_pf') . '=' . $this->_db->quote( '0' ) );
			$this->_db->setQuery($query);

			$dadosAssociado = $this->_db->loadObject();	

			switch(true) {

				case ($dadosAssociado->compressed_air_pf == '0' && $dadosAssociado->copa_brasil_pf == '0'): 
					$id_tipo_associado = '1'; // Associado Padrão
				break;
				case ($dadosAssociado->compressed_air_pf == '1' && $dadosAssociado->copa_brasil_pf == '0'): 
					$id_tipo_associado = '2'; // Associado Padrão
				break;				
				case ($dadosAssociado->compressed_air_pf == '0'&& $dadosAssociado->copa_brasil_pf == '1'): 
					$id_tipo_associado = '3'; // Associado Padrão
				break;
			}


			$query = $this->_db->getQuery(true);	
			$query->select($this->_db->quoteName(array(	'image_front_carteira_digital', 
														'image_back_carteira_digital', 
														'color_front_carteira_digital', 
														'color_back_carteira_digital',
													)));	
			$query->from($this->_db->quoteName('#__intranet_carteira_digital'));
			$query->innerJoin($this->_db->quoteName('#__intranet_anuidade').' USING ('.$this->_db->quoteName('id_anuidade').')');
			$query->where( $this->_db->quoteName('status_carteira_digital') . '=' . $this->_db->quote( '1' ) );
			$query->where( $this->_db->quoteName('validate_anuidade') . '=' . $this->_db->quote( $dadosAssociado->validate_associado ) );
			$query->where( $this->_db->quoteName('id_associado_tipo') . '=' . $this->_db->quote( $id_tipo_associado ) );
			$this->_db->setQuery($query);
			if((boolean) $this->_data = $this->_db->loadObject()){
				$this->_data->id_associado = $dadosAssociado->id_associado;
				$this->_data->validate_associado = $dadosAssociado->validate_associado;
				$this->_data->cpf_pf = $dadosAssociado->cpf_pf;
				$this->_data->numcr_pf = $dadosAssociado->numcr_pf;
				$this->_data->name = $dadosAssociado->name;
				$this->_data->clube = $dadosAssociado->clube;
				$this->_data->sigla_estado = $dadosAssociado->sigla_estado;
				$this->_data->name_cidade = $dadosAssociado->name_cidade;
			}
		}
		
		return $this->_data;
	}

	
}