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

	public function getCorredor()
	{	

		$query_rest = JRequest::getVar('query_rest', array());

		if(isset($query_rest[2])) {
			if($this->_dbClient) {
				$query = $this->_dbClient->getQuery(true);
				$query->select('#__corredor.*');
				$query->select('#__corredor_categoria.name_corredor_categoria');
				$query->select('#__corredor_grupo.name_corredor_grupo');
				$query->select('#__cidade.name_cidade');
				$query->select('#__estado.sigla_estado');
				$query->from($this->_dbClient->quoteName('#__corredor'));
				$query->leftJoin($this->_db->quoteName('#__corredor_categoria').' USING ('.$this->_db->quoteName('id_corredor_categoria').')');
				$query->leftJoin($this->_db->quoteName('#__corredor_grupo').' USING ('.$this->_db->quoteName('id_corredor_grupo').')');
				$query->leftJoin($this->_db->quoteName('#__estado').' USING ('.$this->_db->quoteName('id_estado').')');
				$query->leftJoin($this->_db->quoteName('#__cidade').' USING ('.$this->_db->quoteName('id_cidade').','.$this->_db->quoteName('id_estado').')');

				$query->where( $this->_dbClient->quoteName('id_corredor') . '=' . $this->_dbClient->quote( $query_rest[2]) );			
				$query->where( $this->_dbClient->quoteName('status_corredor') . '=' . $this->_dbClient->quote( '1' ) );

				$this->_dbClient->setQuery($query);
				if( !(boolean) $result = $this->_dbClient->loadObject())
					return false;

				unset($result->password_corredor);
				unset($result->activation_corredor);
		
				return $result;
			}
		}

		return false;
	}

	public function updateCorredor() {

		if($this->_dbClient) {
			$post = JRequest::get( 'post' );
			if(count($post)>0) {
				
				$query	= $this->_dbClient->getQuery(true);
				$execute = false;
				foreach($post as $key => $value) {
					if(in_array($key, self::$ICorredor) && $key != 'id_corredor') {
						if($key == 'date_task_plan')
							$query->set($this->_dbClient->quoteName($key) . '=' . $this->_dbClient->quote(JFactory::getDate(date('Y-m-d', strtotime(str_replace('/', '-', $value))), $this->_siteOffset)->toFormat('%Y-%m-%d', true)));
						else
							$query->set($this->_dbClient->quoteName($key) . '=' . $this->_dbClient->quote($value));
						$execute = true;
					}
				}
				if(!$execute)
					return false;

				$query->update($this->_dbClient->quoteName('#__corredor'));
				$query->where($this->_dbClient->quoteName('id_corredor') . '=' . $this->_dbClient->quote($post['id_corredor']));
	
				$this->_dbClient->setQuery($query);
	
				if (!$this->_dbClient->query()) 
					return false;

				return $post['id_corredor'];
			}
		}
		return false;
	}
}

