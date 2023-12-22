<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class IntranetModelPanel extends JModel
{

	var $_db = null;	
	
	function __construct()
	{	
		parent::__construct();
	//	$this->_db	= JFactory::getDBO();	
			
		
		//$date = JFactory::getDate();
		//$now = $date->toSql();
		//$nullDate = $db->getNullDate();
		//$query->where('(m.publish_up = ' . $db->Quote($nullDate) . ' OR m.publish_up <= ' . $db->Quote($now) . ')');
	}
	
	
	function getUserList()
	{
		// Initialise variables
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query = $db->getQuery(true);

		$query->select('s.time, s.client_id, u.id, u.name, u.username');
		$query->from('#__session AS s');
		$query->leftJoin('#__users AS u ON s.userid = u.id');
		$query->where('s.guest = 0');
		$db->setQuery($query, 0,100);
		$results = $db->loadObjectList();



		// Check for database errors
		if ($error = $db->getErrorMsg()) {
			JError::raiseError(500, $error);
			return false;
		};

		foreach($results as $k => $result)
		{
			$results[$k]->logoutLink = '';

			//if($user->authorise('core.manage', 'com_users'))
			//{
				$results[$k]->editLink = JRoute::_('index.php?view=login&task=user.edit&id='.$result->id);
				$results[$k]->logoutLink = JRoute::_('index.php?view=login&task=logout&uid='.$result->id .'&'. JSession::getFormToken() .'=1');
			//}
			//if($params->get('name', 1) == 0) {
				$results[$k]->name = $results[$k]->username;
			//}
		}

		return $results;
	}
	
}
