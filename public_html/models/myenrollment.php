<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );
require_once(JPATH_LIBRARIES .DS. 'qrcode' .DS. 'qrlib.php');

jimport( 'joomla.application.component.model' );

class EASistemasModelMyEnrollment extends JModel {

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
			$query->select( $this->_db->quoteName(array( 'id_prova',
														 'name_prova',
														 'name_genero',
														 'name_categoria',
														 'name_classe',
														 'params_inscricao_etapa',
														 'quantidade_inscricao_etapa',
														 'id_inscricao_etapa',
														 'date_register_inscricao_etapa',
														 'inscricao_bateria_prova',
														 'inscricao_turma_prova',
														)));
			$query->select('Atleta.name AS name_atleta');
			$query->select('IF(ISNULL(id_addequipe), IF(ISNULL(id_equipe), Estado.name_estado, IF( Equipe.id = 7617, \'AVULSO\', Equipe.name)), name_addequipe) AS name_equipe');	
														
			$query->from( $this->_db->quoteName('#__ranking_inscricao') );
			$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_prova').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . 'USING('. $this->_db->quoteName('id_categoria') .','. $this->_db->quoteName('id_classe').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova') .','. $this->_db->quoteName('id_campeonato').')' );
			
			$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' AS Estado USING( ' . $this->_db->quoteName('id_estado') . ')' );		
			$query->leftJoin( $this->_db->quoteName('#__ranking_inscricao_etapa')  . 'USING('. $this->_db->quoteName('id_inscricao') . ')' );
																						   
			$query->leftJoin( $this->_db->quoteName('#__users') . ' AS Atleta ON('. $this->_db->quoteName('#__ranking_inscricao.id_user') .'='. $this->_db->quoteName('Atleta.id'). ')' );
			$query->leftJoin( $this->_db->quoteName('#__users') . ' AS Equipe ON('. $this->_db->quoteName('#__ranking_inscricao.id_equipe') .'='. $this->_db->quoteName('Equipe.id'). ')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_addequipe') . ' AS AddEquipe USING('. $this->_db->quoteName('id_addequipe') . ')' );
			$query->where($this->_db->quoteName('id_inscricao_etapa') . ' = ' . $this->_db->quote( $this->_id ));
			
			$this->_db->setQuery($query);

			$this->_data =  $this->_db->loadObject();

			//$this->_data->skin_documento_numero = '/media/images/ptimbrado/' . $this->_data->skin_documento. '.jpg';

		}
		return $this->_data;
	}


	
	function getAgendamentosPrint()
	{
		$query = $this->_db->getQuery(true);
		$query->select( $this->_db->quoteName(array( 'date_inscricao_agenda',
													 'bateria_inscricao_agenda',
													 'turma_inscricao_agenda',
													 'posto_inscricao_agenda',
													)));
	
		$query->from( $this->_db->quoteName('#__ranking_inscricao_agenda') );
		$query->where($this->_db->quoteName('id_inscricao_etapa') . ' = ' . $this->_db->quote( $this->_id ));
	
	
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
		
	}
	

	
	function getMyInfo()
	{

		$query = $this->_db->getQuery(true);
		$query->select('*' );	
		$query->from( $this->_db->quoteName('#__users') );
		$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_associado') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->leftJoin( $this->_db->quoteName('#__intranet_conveniado') . 'USING('. $this->_db->quoteName('id_user').')' );
		$query->where( $this->_db->quoteName('id') .  '=' . $this->_db->quote( $this->_user->get('id') ) );		
		$this->_db->setQuery($query);
		return $this->_db->loadObject();

	}

	
}