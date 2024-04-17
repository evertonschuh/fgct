<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );


class EASistemasModelConnect extends JModel
{

	var $_db = null;
	var $_app = null;	
	var $_total = null;	
	var $_data = null;
	var $_dbClient = null;

	

	function __construct()
	{		
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
		parent::__construct();
	}

	
	public function getConnect()
	{	
		
		if((boolean) $session = $this->getNewAppSession()):
			$response = new stdClass();

			$jwt = JResponse::getTokenJWT();
			
			if(!$jwt)
				$jwt = JResponse::setTokenJWT($session);

			$response->connectToken = $jwt;
			return $response;
		endif;

		return false;
	}

	
	protected function getNewAppSession(){


		$session = new stdClass();
		$session->id = JRequest::getVar('token', '', 'POST');
		$session->userid = 0;
		$session->guest = 0;
		$session->data = '{}';
		return $session;

/*
		$client_id = JRequest::getVar('client_id', '', 'POST');
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('name_client', 'id_client')));											
		$query->from($this->_db->quoteName('#__client'));
		$query->where( $this->_db->quoteName('token_client') . '=' . $this->_db->quote( $client_id ) );
		$this->_db->setQuery($query);
		if( !(boolean) $result = $this->_db->loadObject())
			return false;
		else
			return $result;*/
	}

}
