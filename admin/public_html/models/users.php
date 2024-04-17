<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.modellist' );


class IntranetModelUsers extends JModelList
{
	var $_db = null;
	var $_user = null;
	var $_app = null;	
	var $_pagination = null;
	var $_total = null;	
	var $_data = null;

	
	function __construct()
	{	
		parent::__construct();

		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
	}

	
    protected function populateState($ordering = null, $direction = null) {

		$limit		= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.limit', 'limit', $this->_app->getCfg('list_limit'), 'int' );
		
		$limitstart	= $this->_app->getUserStateFromRequest( 
		$this->context . '.list.start', 'start', 0, 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	   	$this->setState('list.limit', $limit); 
	  	$this->setState('list.start', $limitstart); 

		$groupId = $this->getUserStateFromRequest(
		$this->context.'.filter.group', 'filter_group_id', null, 'int');
		$this->setState('filter.group_id', $groupId);

        $status = $this->_app->getUserStateFromRequest(
        $this->context .'filter.status', 'status', null,'string');
        $this->setState('filter.status', $status);

        $search = $this->_app->getUserStateFromRequest(
        $this->context .'filter.search', 'search', null,'string');
        $this->setState('filter.search', $search);  	

		$ordering = $this->_app->getUserStateFromRequest(
        $this->context .'list.ordering','filter_order', 'id', 'string');
		$this->setState('list.ordering', $ordering); 

 		$direction = $this->_app->getUserStateFromRequest(
        $this->context .'list.direction','filter_order_Dir', 'DESC','string');
		$this->setState('list.direction', $direction); 

	   parent::populateState($ordering, $direction );
		
    }

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.active');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.group_id');
		$id .= ':'.$this->getState('filter.range');

		return parent::getStoreId($id);
	}

	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (empty($this->cache[$store]))
		{
			
			
			$groups = $this->getState('filter.groups');
			$groupId = $this->getState('filter.group_id');
			if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups)))
			{
				$items = array();
			}
			else
			{
				$items = parent::getItems();
			}

			// Bail out on an error or empty list.
			if (empty($items))
			{
				$this->cache[$store] = $items;

				return $items;
			}

			// Joining the groups with the main query is a performance hog.
			// Find the information only on the result set.

			// First pass: get list of the user id's and reset the counts.
			$userIds = array();
			foreach ($items as $item)
			{
				$userIds[] = (int) $item->id;
				$item->group_count = 0;
				$item->group_names = '';
				$item->note_count = 0;
			}

			// Get the counts from the database only for the users in the list.
			$query = $this->_db->getQuery(true);

			// Join over the group mapping table.
			$query->select('map.user_id, COUNT(map.group_id) AS group_count')
				->from('#__user_usergroup_map AS map')
				->where('map.user_id IN ('.implode(',', $userIds).')')
				->group('map.user_id')
				// Join over the user groups table.
				->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

			$this->_db->setQuery($query);

			// Load the counts into an array indexed on the user id field.
			$userGroups = $this->_db->loadObjectList('user_id');

			
			$error = $this->_db->getErrorMsg();
			if ($error)
			{
				$this->setError($error);

				return false;
			}
/*
			$query->clear()
				->select('n.user_id, COUNT(n.id) As note_count')
				->from('#__user_notes AS n')
				->where('n.user_id IN ('.implode(',', $userIds).')')
				->where('n.state >= 0')
				->group('n.user_id');

			$this->_db->setQuery((string) $query);

			// Load the counts into an array indexed on the aro.value field (the user id).
			$userNotes = $this->_db->loadObjectList('user_id');
			print_r($userNotes );
			break ;
			$error = $this->_db->getErrorMsg();
			if ($error)
			{
				$this->setError($error);

				return false;
			}
*/
			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
				if (isset($userGroups[$item->id]))
				{
					$item->group_count = $userGroups[$item->id]->group_count;
					//Group_concat in other databases is not supported
					$item->group_names = $this->_getUserDisplayedGroups($item->id);
				}
/*
				if (isset($userNotes[$item->id]))
				{
					$item->note_count = $userNotes[$item->id]->note_count;
				}*/
			}

			// Add the items to the internal cache.
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
	}



    protected function getListQuery() {
		
		$query = $this->_db->getQuery(true);
		
		
		$query->select(
			$this->getState(
				'list.select',
				'*'
			)
		);

		$query->from($this->_db->quoteName('#__users'));

		// If the model is set to check item state, add to the query.
		$state = $this->getState('filter.status');

		if (is_numeric($state))
		{
			$query->where('block = '.(int) $state);
		}

		// If the model is set to check the activated state, add to the query.
		$active = $this->getState('filter.active');

		if (is_numeric($active))
		{
			if ($active == '0')
			{
				$query->where('activation = '.$this->_db->quote(''));
			}
			elseif ($active == '1')
			{
				$query->where($query->length('activation').' = 32');
			}
		}

		// Filter the items over the group id if set.
		$groupId = $this->getState('filter.group_id');
		$groups = $this->getState('filter.groups');

		if ($groupId || isset($groups))
		{
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = id');
			$query->group('id,name,username,password,usertype,block,sendEmail,registerDate,lastvisitDate,activation,params,email');

			if ($groupId)
			{
				$query->where('map2.group_id = '.(int) $groupId);
			}

			if (isset($groups))
			{
				$query->where('map2.group_id IN ('.implode(',', $groups).')');
			}
		}

		// Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '')
		{
			// Escape the search token.
			$token	= $this->_db->Quote('%'.$this->_db->escape($this->getState('filter.search')).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name LIKE '.$token;
			$searches[]	= 'username LIKE '.$token;
			$searches[]	= 'email LIKE '.$token;

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}




		// Add the list ordering clause.
		$query->order($this->_db->escape($this->getState('list.ordering', 'name')).' '.$this->_db->escape($this->getState('list.direction', 'ASC')));

		return $query;

		
		
		
		
		
		
			
		/*
		$query->select($this->_db->quoteName(  array('id_parceiro_midia',
													 'status_parceiro_midia',
													 'featured_parceiro_midia',
													 'title_parceiro_midia',
													 'url_parceiro_midia',
													 'img_news_parceiro_midia',
													 '#__parceiro_midia.ordering',
													 '#__parceiro_midia.checked_out',
													 '#__parceiro_midia.checked_out_time',
													 
													 )));							 
		$query->select('COUNT(id_parceiro_link) as count_parceiro_link');									 

		$query->from($this->_db->quoteName('#__parceiro_midia'));
		$query->leftJoin($this->_db->quoteName('#__parceiro_link') . ' USING(' . $this->_db->quoteName('id_parceiro_midia') . ')' );
		
		$query->group($this->_db->quoteName('id_parceiro_midia'));

		
		$status = $this->getState('filter.status');
        if ($status!='')
			 $query->where( $this->_db->quoteName('status_parceiro_midia') . '=' . $this->_db->escape( $status ) );
			
		$search = $this->getState('filter.search');
        if ($search!='')
			 $query->where( $this->_db->quoteName('title_parceiro_midia') . ' LIKE \'%' . $this->_db->escape($search) . '%\'' );
		
		$ordering = $this->getState('list.ordering');
		$direction = $this->getState('list.direction');
		$query->order($this->_db->quoteName($ordering) . ' ' . $this->_db->escape($direction) );
		*/
        return $query;
    }
	
	
	
	function getExport()
	{
		
		$query = $this->_db->getQuery(true);
		
		
		$query->select(
			array(
				'#__users.name',
				'#__users.email'
			)
		);

		$query->from($this->_db->quoteName('#__users'));

		// If the model is set to check item state, add to the query.
		$state = $this->getState('filter.status');

		if (is_numeric($state))
		{
			$query->where('block = '.(int) $state);
		}

		// If the model is set to check the activated state, add to the query.
		$active = $this->getState('filter.active');

		if (is_numeric($active))
		{
			if ($active == '0')
			{
				$query->where('activation = '.$this->_db->quote(''));
			}
			elseif ($active == '1')
			{
				$query->where($query->length('activation').' = 32');
			}
		}

		// Filter the items over the group id if set.
		$groupId = $this->getState('filter.group_id');
		$groups = $this->getState('filter.groups');

		if ($groupId || isset($groups))
		{
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = id');
			$query->group('id,name,username,password,usertype,block,sendEmail,registerDate,lastvisitDate,activation,params,email');

			if ($groupId)
			{
				$query->where('map2.group_id = '.(int) $groupId);
			}

			if (isset($groups))
			{
				$query->where('map2.group_id IN ('.implode(',', $groups).')');
			}
		}

		// Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '')
		{
			// Escape the search token.
			$token	= $this->_db->Quote('%'.$this->_db->escape($this->getState('filter.search')).'%');

			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'name LIKE '.$token;
			$searches[]	= 'username LIKE '.$token;
			$searches[]	= 'email LIKE '.$token;

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}




		// Add the list ordering clause.
		$query->order($this->_db->escape($this->getState('list.ordering', 'name')).' '.$this->_db->escape($this->getState('list.direction', 'ASC')));

		$this->_db->setQuery($query);
		return $this->_db->loadRowList();
		
		
	}
	
	
	function _getUserDisplayedGroups($user_id)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT title FROM ".$db->quoteName('#__usergroups')." ug left join ".
				$db->quoteName('#__user_usergroup_map')." map on (ug.id = map.group_id)".
				" WHERE map.user_id=".$user_id;

		$db->setQuery($sql);
		$result = $db->loadColumn();
		return implode("\n", $result);
	}
	
	static function getGroups()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
			' FROM #__usergroups AS a' .
			' LEFT JOIN '.$db->quoteName('#__usergroups').' AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' GROUP BY a.id, a.title, a.lft, a.rgt' .
			' ORDER BY a.lft ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option)
		{
			$option->text = str_repeat('- ', $option->level).$option->text;
		}

		return $options;
	}
	
	function block_user()
	{
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setBlock($cid, '1'))
			return true;
		else
			return false;

	}
	
	function unblock_user()
	{	
		JRequest::checkToken() or jexit( 'JINVALID_TOKEN' );
		$cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
		JArrayHelper::toInteger($cid);
		if ($this->setBlock($cid, '0'))
			return true;
		else
			return false;
	}
	
	function setBlock(  $cid, $status ){
		if (count( $cid )) 
		{
			$cids = implode( ',', $cid );
			$query = $this->_db->getQuery(true);
			
			$fields = array( $this->_db->quoteName('block') . ' = ' . $this->_db->quote( $status ) );

			$conditions = array( $this->_db->quoteName('id') . ' IN (' . $cids . ')' );
			
			$query->update($this->_db->quoteName('#__users'))->set($fields)->where($conditions);
			
			$this->_db->setQuery($query);
						
			if ( !$this->_db->query() ) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
			$statusBlock='desbloqueado';
			if($status==1)
				$statusBlock=='bloqueado';
				
			jimport('joomla.log.log');
			JLog::addLogger(array( 'text_file' => 'log.user.php'));
			JLog::add($this->_user->get('id') . JText::_('		Usuário(s) '.$statusBlock.'(s) - IdUsers('.$cids. ')'), JLog::INFO, 'users');

			
			
			return true;
		}	
	}
	
	
	
	
	
	function importfit()
	{

		$filecsv = JRequest::getVar( 'filecsv', '', 'files', 'array' );
		$file = JPATH_BASE .DS. 'cache' . DS . md5(uniqid()) . '.csv';

		if (!JFile::upload($filecsv['tmp_name'], $file))
			return false;

		if(!JFile::exists($file))
			return false;
			
		if (($handle = fopen($file, "r")) !== FALSE) {
			
			$cortesias = array();
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				$data = array_map("utf8_encode", $data); 
				$Obj = new stdClass();
				$Obj->nome =  $data[0];
				$Obj->email =  $data[1];
				$Obj->empresa =  $data[2];
				$Obj->id_evento =  $data[3];	
				$cortesias[] = $Obj;

			}
			fclose($handle);
		}

		$this->addTablePath(JPATH_BASE.'/tables');
		if(count($cortesias)>0):
			foreach($cortesias as $cortesia):
				$data = array();
				$data['email'] = strtolower(trim($cortesia->email)); 	
				$data['password'] = '2fit2017';				
				$data['name'] = trim($cortesia->nome);
				$data['empresa_pf'] = $cortesia->empresa;
				$data['id_evento'] = $cortesia->id_evento;
				$data['username'] = $data['email'];
				$data['block'] = '0';
				$data['sendEmail'] = '1';
				$data['status_pf'] = '1';
				$data['groups'] =  array(2,4);					
				
				$query = $this->_db->getQuery(true);
				$query->select('id');											
				$query->from($this->_db->quoteName('#__users'));
				$query->where( $this->_db->quoteName('email') . '=' . $this->_db->quote( $data['email'] ) );
				$this->_db->setQuery($query);
				if( !(boolean) $user_id = $this->_db->loadResult()):	
					$user = new JUser;
					if (!$user->bind($data))
						return true;
					if (!$user->save())
						return false;
					$user_id = $user->get('id');
				endif;
				
				$data['user_id'] = $user_id;
				
				$query->clear();
				$query->select(array('id_pf','empresa_pf'));											
				$query->from($this->_db->quoteName('#__pf'));
				$query->where( $this->_db->quoteName('user_id') . '=' . $this->_db->quote( $data['user_id'] ) );
				$this->_db->setQuery($query);
				if( !(boolean) $pf = $this->_db->loadObject() ):
					$row = $this->getTable('pf');
					if ( !$row->bind($data)) :
						return false;	
					endif;
					if ( !$row->check($data)): 
						return false;	
					endif;
					if ( !$row->store() ) :
						return false;
					endif;	
				elseif( empty( $pf->empresa_pf ) ):
					$row = $this->getTable('pf');
					$row->load($pf->id_pf);
					$row->empresa_pf = $data['empresa_pf'];
					if ( !$row->store() ) :
						return false;
					endif;
				endif;
				$query->clear();
				$query->select(array('id_inscricao','free_inscricao'));											
				$query->from($this->_db->quoteName('#__inscricao'));
				$query->where( $this->_db->quoteName('user_id') . '=' . $this->_db->quote( $data['user_id'] ) );
				$query->where( $this->_db->quoteName('id_evento') . '=' . $this->_db->quote( $data['id_evento'] ) );
				$this->_db->setQuery($query);
				if( !(boolean) $inscricao = $this->_db->loadObject() ):
	
					$dataNow = JFactory::getDate('now', $siteOffset)->toISO8601(true);
					$new_inscricao = array();
					$new_inscricao['status_inscricao'] = 1;
					$new_inscricao['approval_inscricao'] = 2 ;
					$new_inscricao['validate_approval_inscricao'] = $dataNow;
					$new_inscricao['user_id'] = $data['user_id'];
					$new_inscricao['id_evento'] = $data['id_evento'];
					$new_inscricao['id_situation'] = 1;
					$new_inscricao['registered_inscricao'] = $dataNow;
					$new_inscricao['enroll_inscricao'] = $dataNow;
					$new_inscricao['free_inscricao'] = 1;
				
					$row = $this->getTable('inscricao');	
					if ( !$row->bind($new_inscricao)) :
						return false;	
					endif;
					if ( !$row->check($new_inscricao)) :
						return false;	
					endif;
					if ( !$row->store() ) :
						return false;
					endif;
						
					//$id_inscricao = $row->get('id_inscricao');	
					/*
					id_inscricao`,6868 
					user_approval_inscricao`,0
					date_approval_inscricao`,0000-00-00 00:00:00
					
					`id_polo`, `0
					id_turma`, null
					
					`lock_inscricao`,  '0000-00-00 00:00:00',
					`original_value_inscricao`, 0
					`value_inscricao`, 0
					`plots_inscricao`, `0
					date_first_plots_inscricao`,0000-00-00
					`id_parceiro`, 0
					`id_convenio`, 0
					`name_cupom`, ''
					`descount_type`, 0
					`descount_inscricao`, NULL
					`contract_inscricao`, ''
					`note_inscricao`, ''
					`ip_inscricao`, ''
					`id_pagamento`, NULL 
					`id_alunos_cursos`, NULL,
					`cc_id`, NULL,
					`checked_out`, NULL,
					`checked_out_time`NULL,
					*/
	
				elseif( $inscricao->free_inscricao==0 ):
					$row = $this->getTable('inscricao');	
					$row->load($inscricao->id_inscricao);
					$row->free_inscricao = 1;
					if ( !$row->store() ) :
						return false;
					endif;
				
				endif;	
					
	
			endforeach;

			JFile::delete( $file  );
		
			return true;
		endif;	
		return false;
	}
	
}
