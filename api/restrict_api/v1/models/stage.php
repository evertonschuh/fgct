<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class EASistemasModelProfile extends JModel
{
	public static $ICorredor = array(	'id_corredor',
										'status_corredor',
										'name_corredor',
										'email_corredor',
										'cpf_corredor',
										'sexo_corredor',
										'data_nascimento_corredor',
										'tel_celular_corredor',
										'id_estado',
										'sigla_estado',
										'id_cidade',
										'name_cidade',
										'cep_corredor',
										'bairro_corredor',
										'logradouro_corredor',
										'numero_corredor',
										'complemento_corredor',
										'image_corredor',
										'id_corredor_categoria',
										'id_corredor_grupo',
										'name_corredor_categoria',
										'name_corredor_grupo',);
	var $_db = null;
	var $_app = null;	
	var $_total = null;	
	var $_data = null;
	var $_dbClient = null;

	function __construct()
	{		
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
		$this->_dbClient = JFactory::getDboClient();
		parent::__construct();
	}

	public function getItem()
	{

		if($this->_db){
			$get = JRequest::get( 'get' );
			
			if(isset($get['query_rest'][2]) && !empty($get['query_rest'][2]) && is_numeric($get['query_rest'][2]) ){

				$query = $this->_db->getQuery(true);
				$query->select($this->_db->quoteName(array( 'id_meeting',
															'status_meeting',
															'ordering_meeting',
															'type_meeting',
															'local_meeting',
															'date_meeting',
															'duration_meeting',
															'subject_meeting',
															'relatory_meeting',
															'annotation_mentorado_meeting',
															'annotation_mentor_meeting',
															'lessons_meeting',
															'homework_meeting',
															'user_register',
															'date_register',
															'user_update',
															'date_update'
														)));

				$query->from($this->_db->quoteName('#__meeting'));	
				
				$query->where( $this->_db->quoteName('id_meeting') . '=' . $this->_db->quote($get['query_rest'][2]) );	
				$this->_db->setQuery($query);
				return $this->_db->loadObject();

			}
		}
		return false;

	}
	
	public function createItem()
	{	

		$Meeting = JRequest::getVar('Meeting', '', 'POST');
		if($this->_db){

			$Meeting['ordering_meeting'] = $this->getNextOrder($Meeting['id_mentory']);

			$query = $this->_db->getQuery(true);

			$query->insert( $this->_db->quoteName('#__meeting') );
			$query->columns($this->_db->quoteName(array(	'id_mentory',
																'status_meeting',
																'date_meeting',
																'type_meeting',
																'local_meeting',
																'duration_meeting',
																'subject_meeting',
																'ordering_meeting',
																'user_register',
																'date_register',
																)));	
		

			$values = array($this->_db->quote($Meeting['id_mentory']),
							$this->_db->quote($Meeting['status_meeting']),
							$this->_db->quote(JFactory::getDate($Meeting['date_meeting'], $this->_siteOffset)->toISO8601(true)),
							$this->_db->quote($Meeting['type_meeting']),
							$this->_db->quote($Meeting['local_meeting']),
							$this->_db->quote($Meeting['duration_meeting']),
							$this->_db->quote($Meeting['subject_meeting']),
							$this->_db->quote($this->getNextOrder($Meeting['id_mentory'])),
							$this->_db->quote($Meeting['user_register']),
							$this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toISO8601(true)),
						);

			$query->values(implode(',', $values));

			$this->_db->setQuery($query);
			if( !(boolean) $this->_db->execute())
				return false;
		
			$Meeting['id_meeting'] = $this->_db->insertid();

			return $Meeting['id_meeting'];
				
		}

		return false;
	}


	public function deleteItem()
	{
		if($this->_db) {
			$query_rest = JRequest::getVar('query_rest', array());
			if(isset($query_rest[2])) {

				$options = $this->getOptionsIDs($query_rest[2]);
				$options['ordering_meeting'] = 0;
				$query = $this->_db->getQuery(true);
				$conditions = array( $this->_db->quoteName('id_meeting') . '=' . $this->_db->quote($query_rest[2]) );			
				
				$query->delete( $this->_db->quoteName('#__meeting') );
				$query->where($conditions);
				$this->_db->setQuery($query);
				if ( !$this->_db->query() ) {
					return false;
				}
			
				$this->reorder($options, 'ASC');
	
				return $query_rest[2];
			}
		}
		return false;
		
	}


	public function updateItem() {

		if($this->_db) {
			$post = JRequest::get( 'post' );
			if(count($post)>0) {
				
				$query	= $this->_db->getQuery(true);
				$execute = false;
				foreach($post as $key => $value) {
					if(in_array($key, self::$IMeeting) && $key != 'id_meeting') {
						if($key == 'date_meeting')
							$query->set($this->_db->quoteName($key) . '=' . $this->_db->quote(JFactory::getDate($value, $this->_siteOffset)->toISO8601(true)) );
						else
							$query->set($this->_db->quoteName($key) . '=' . $this->_db->quote($value));
						$execute = true;
					}
				}

				$query->set($this->_db->quoteName('date_update') . '=' . $this->_db->quote(JFactory::getDate('now', $this->_siteOffset)->toISO8601(true)));

				if(!$execute)
					return false;

				$query->update($this->_db->quoteName('#__meeting'));
				$query->where($this->_db->quoteName('id_meeting') . '=' . $this->_db->quote($post['id_meeting']));
	
				$this->_db->setQuery($query);
	
				if (!$this->_db->query()) 
					return false;

				return $post['id_meeting'];
			}
		}
		return false;
	}
}