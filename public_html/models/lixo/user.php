<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class IntranetModelUser extends JModel {

	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;
	var $_pagination = null;
	
	function __construct()
	{
		parent::__construct();

		$this->_db		= JFactory::getDBO();
		$this->_user	= JFactory::getUser();
		
		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id');
				
		//echo JPATH_ADMINISTRATOR;
		if( JRequest::getVar( 'task' )  != 'add')
		{
			$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
			JRequest::setVar( 'cid', $array[0] );
			$this->setId( (int) $array[0] );
		/*
			if (!$this->isCheckedOut() )
			{
				$this->checkout();		
			}
			else
			{
				$tipo = 'alert-warning';
				$msg = JText::_( 'JGLOBAL_CONTROLLER_CHECKIN_ITEM' );
				$link = 'index.php?view=upfs';
				$this->_app->redirect($link, $msg, $tipo);
			}
		*/
		}
		
	}

	function setId( $id ) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getItem()
	{
		if (empty($this->_data))
		{			
			$query = $this->_db->getQuery(true);			
			$query->select('*');
			$query->from('#__users');	
			$query->where( $this->_db->quoteName('id') . '=' . $this->_db->escape( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()) {
				$this->_data = JUser::getInstance(0);
			}
		}
		return $this->_data;
	}

	function store() 
	{
		$data = JRequest::get('post');     
		$data['groups'] = $data['jform']['groups'];
		$data['username'] = $data['username'];
		$data['email'] = $data['email'];
		/*
		if(empty($test))
			return false;
	
		$dataUser	= new stdClass();
		$dataUser->name			= $this->_app->getUserStateFromRequest( 'name' );
		$dataUser->username		= $this->_app->getUserStateFromRequest( 'email' );
		$dataUser->email		= $this->_app->getUserStateFromRequest( 'email' );
		$dataUser->password		= $this->_app->getUserStateFromRequest( 'password' );
		$dataUser->activation	= JApplication::getHash(JUserHelper::genRandomPassword());
		$dataUser->block		= 1;
		$dataUser->sendEmail	= 1;
		$dataUser->groups 		= array();
		$dataUser->groups[] 	= 2;
		
		$user = new JUser;
		$data = (array)$dataUser;

		if (!$user->bind($data)) {
			return false;
		}

		$user->setParam('activate', 1);
		
		if (!$user->save()) {
			return false;
		}
		unset($data);
		*/

		$pk			= (!empty($data['id'])) ? $data['id'] : 0;
		$user		= JUser::getInstance($pk);

		$my = JFactory::getUser();

		if ($data['block'] && $pk == $my->id && !$my->block)
		{
			$this->setError(JText::_('COM_USERS_USERS_ERROR_CANNOT_BLOCK_SELF'));
			return false;
		}
/*		
		// Make sure that we are not removing ourself from Super Admin group
		$iAmSuperAdmin = $my->authorise('core.admin');
		
		if ($iAmSuperAdmin && $my->get('id') == $pk)
		{
			// Check that at least one of our new groups is Super Admin
			$stillSuperAdmin = false;
			 
			$myNewGroups = $jform['groups'];

			foreach ($myNewGroups as $group)
			{
				echo ' - ' . $group;
				
				$stillSuperAdmin = ($stillSuperAdmin) ? ($stillSuperAdmin) : JAccess::checkGroup($group, 'core.admin');
			}
			if (!$stillSuperAdmin)
			{
				$this->setError(JText::_('COM_USERS_USERS_ERROR_CANNOT_DEMOTE_SELF'));
				return false;
			}
		}
		*/
		if (!$user->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}

		if (!$user->save())
		{
			$this->setError($user->getError());
			return false;
		}
		
		jimport('joomla.log.log');
		if($this->_id):
			JLog::addLogger(array( 'text_file' => 'log.user.php'));
			JLog::add($this->_user->get('id') . JText::_('		Usuário editado - IdUser('.$this->_id. ')'), JLog::INFO, 'user');
		else:
			$this->setId( $user->get('id') ); 	
			JLog::addLogger(array( 'text_file' => 'log.user.php'));
			JLog::add($this->_user->get('id') . JText::_('		Usuário cadastrado - IdUser('.$this->_id. ')'), JLog::INFO, 'user');
		endif;
		
		JRequest::setVar( 'cid', $this->_id );
				
		return true;
	}
	


	public function getAssignedGroups($userId = null)
	{
		return JUserHelper::getUserGroups($this->_id);
	}


	
}