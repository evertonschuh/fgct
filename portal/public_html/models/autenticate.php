<?php
defined('_JEXEC') or die('Restricted access');

//require_once(JPATH_COMPONENT_ADMINISTRATOR .DS. 'includes' .DS. 'images.php');
//require_once(JPATH_COMPONENT_ADMINISTRATOR .DS. 'includes' .DS. 'qrcode' .DS. 'qrlib.php'); 

jimport( 'joomla.application.component.model' );

class EASistemasModelAutenticate extends JModel
{

	var $_db = null;
	var $_id = null;	
	var $_data = null;
	var $_app = null;
	var $_user = null;
	var $_idProva = null;
	var $_pagination = null;
	var $_total = null;	
	var $_trataImagem = null;
	var $_session_id = null;
	var $_id_modalidade = null;
	var $_special_arguments = null;
	var $_class_modalidade = null;
	var $_tag_class_modalidade = null;
	var $_siteOffset = null;
	//var $_access = null;
	
 	public function __construct($config = array()) {   

        parent::__construct($config);
		$this->_db	= JFactory::getDBO();		
		$this->_app	 = JFactory::getApplication(); 
		$this->_user = JFactory::getUser();

		$this->_siteOffset = $this->_app->getCfg('offset');

	}


	function getAutenticateCode() 
	{

		$code  	= JRequest::getVar( 'code');
		if(empty($code))
			return true;
		//echo str_replace('=', '', strrev( base64_encode(base64_encode('53894' ) ) ) );
			

		$this->_id = base64_decode( base64_decode( strrev( '=' . $code ) ) );
		//echo $this->_id ;
		if(!is_numeric($this->_id))
			return false;


		if(empty($this->_data))
			$this->getItem();

		if(empty($this->_data))
			return false;

		return true;


	}	

	function getItem()
	{
		if (empty($this->_data))
		{			
		


			if(substr($this->_id,0,5) == '00000'):




				$query = $this->_db->getQuery(true);	
				$query->select($this->_db->quoteName(array(	'id_associado', 
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
				$query->innerJoin($this->_db->quoteName('#__users') . ' AS clube ON('.$this->_db->quoteName('id_clube').'='.$this->_db->quoteName('clube.id').')');
				$query->where( $this->_db->quoteName('id_associado') . '=' . $this->_db->quote( substr($this->_id,9) ) );
				$query->where( $this->_db->quoteName('validate_associado') . '=' . $this->_db->quote( JFactory::getDate(substr($this->_id,5,4) . '-12-31', $this->_siteOffset)->toFormat('%Y-%m-%d',true) ) );
				$query->where( $this->_db->quoteName('status_associado') . '=' . $this->_db->quote( '1' ) );
				$query->where( $this->_db->quoteName('status_pf') . '=' . $this->_db->quote( '1' ) );
				$query->where( $this->_db->quoteName('block_pf') . '=' . $this->_db->quote( '0' ) );
				$this->_db->setQuery($query);

				if(!(boolean) $this->_data = $this->_db->loadObject()):
					$this->_data = false;
				endif;
					
				
										

			else:
				$query = $this->_db->getQuery(true);
				$query->select( $this->_db->quoteName(array('id_documento_numero',
															'register_documento_numero',
															'name',
															'#__intranet_documento_numero.name_documento',
															)));
				//$query->select('name AS name_local');
				$query->select('CONCAT(ano_documento_numero, \'/\', numero_documento_numero) AS numero_documento_numero');		
				$query->from($this->_db->quoteName('#__intranet_documento_numero'));
				$query->innerJoin($this->_db->quoteName('#__intranet_documento') . 'USING(' . $this->_db->quoteName('id_documento') . ')');
				$query->innerJoin($this->_db->quoteName('#__intranet_pf') . 'USING(' . $this->_db->quoteName('id_user') . ')');
				$query->innerJoin($this->_db->quoteName('#__users') . 'ON(' . $this->_db->quoteName('id_user') .  '=' . $this->_db->quoteName('id') .  ')');		
				
				$query->where($this->_db->quoteName('id_documento_numero') . ' = ' . $this->_db->quote($this->_id));	

				$this->_db->setQuery($query);	

				$this->_db->setQuery($query);
				$this->_data = $this->_db->loadObject(); 

			endif;
					   
		}
		
		return $this->_data;
	}
	
	
}