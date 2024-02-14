<?php


include( 'joomla.inc.php' );

$obj = new EASistemasAppLogin();

class EASistemasAppLogin
{
	var $_db;
	var $_app;
	
	function __construct()
	{
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();

		$credentials['username'] = JRequest::getVar( 'username', '', 'POST' );
		$credentials['password'] = JRequest::getVar( 'password', '', 'POST');

		$response = new stdClass();
		$response->response = 'error';
		$response->message = 'nenhum processo executado';

		if(empty($credentials['username']) || empty($credentials['password']) ):
			$response->message = 'Usuário ou Senha não recebidos.';
		else:
			$options = array();
			$options['silent'] = true;
			$options['forecelogon'] = true;

		
			if( $this->_app->login($credentials, $options) ):
			
				
				$user = JFactory::getUser();
				//$params =  JComponentHelper::getParams('com_intranet');
				$response->response = 'success';
				$response->message = 'Login realizado com sucesso.';
				
				$NameUser = explode(" ", $user->get('name'));
				if ( count($NameUser)>1 ) 
					$Nome = $NameUser[0] . ' ' . end($NameUser);
				else
					$Nome =  end($NameUser);
				
				$response->name = $Nome;
				$response->userid =  $user->get('id');
				$response->token =  JFactory::getSession()->getId();				
/*
				$query = $this->_db->getQuery(true);
				$query->select('IF(ISNULL(id_pf), IF(logo_pj="", NULL, CONCAT(\'logos/\', logo_pj)), CONCAT(\'avatar/\', image_pf)) AS image');
				$query->from($this->_db->quoteName('#__users'));
				$query->leftJoin( $this->_db->quoteName('#__pf') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('#__intranet_pf.id_user').')' );
				$query->leftJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('#__intranet_pj.id_user').')' );
				$query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote( $response->id ));
				$this->_db->setQuery($query);
				$item = $this->_db->loadObject();
				
				if(!empty($item->image)):
					$response->image = 'https://intranet.fgct.com.br/media/images/'. $item->image;
				else:
					$response->image = 'https://intranet.fgct.com.br/views/system/images/nophoto.png';
				endif;
*/
			else:
				$response->message = 'Nome de Usuário ou a Senha informada não estão corretos.';
			endif;	
		endif;
	
		header('Content-Type: application/json');
		if($response)
			echo json_encode($response);
		
	}
}
?>
