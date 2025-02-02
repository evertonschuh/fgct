<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.modellist' );
jimport('joomla.image.resize');

class EASistemasModelClubes extends JModelList
{

	var $_db = null;
	var $_app = null;	
	var $_total = null;	
	var $_data = null;
	var $_siteOffset = null;
	var $_resize = null;
	var $_path_logo = null;

	function __construct()
	{		
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
		$this->_siteOffset = $this->_app->getCfg('offset');
		$this->_resize = new JResize();
		$this->_path_logo = JPATH_CDN .DS. 'images' .DS. 'logos' . DS;

		parent::__construct();
	}

	
    protected function populateState($ordering = null, $direction = null) {

		$limit = JRequest::getVar( 'limit','10', 'GET' );
		$page = JRequest::getVar( 'page','1', 'GET' );

		$this->setState('list.limit', $limit); 

		$limitstart = ($limit != 0 ? (($page-1) * $limit) : 0);
		
		$this->setState('list.start', $limitstart); 
		$this->setState('list.limitstart', $limitstart); 

    }

	protected function getListQuery()
	{

		if($this->_db){

			$query = $this->_db->getQuery(true);
			$query->select( $this->_db->quoteName(array('id',
														'name',
														'logo_pj',
														'name_cidade',
														'name_estado',
														'cep_pj',
														'logradouro_pj',
														'numero_pj',
														'complemento_pj',
														'bairro_pj',
														'telefone_pj',
														'celular_pj',
														'site_pj'
														)));				 
			

			$query->from( $this->_db->quoteName('#__users') );
			$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id').' = '. $this->_db->quoteName('id_user').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').', '. $this->_db->quoteName('id_estado').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado'). ')' );
			
			$query->where( $this->_db->quoteName('block') . ' = ' . $this->_db->escape('0'));
			$query->where( $this->_db->quoteName('status_pj') . ' = ' . $this->_db->escape('1'));
			$query->where( $this->_db->quoteName('id') . '<>' . $this->_db->escape('7617'));

			
			$query->order($this->_db->quoteName('name') . ' ASC' );

			return $query;	
		}
		return false;

	}


	public function getItems()
	{
		//$data = $this->getListQuery();
		parent::setDbo($this->_db);
		$data = parent::getItems();


		$image_default = $this->_resize->resize(JPATH_IMAGES .DS. 'noclube.png' , 350, 350, 'cache/noclube350x350.png', false, 2);
		foreach($data as &$item){
			if(!empty($item->logo_pj)){

				$image_path = $this->_resize->resize($this->_path_logo. $item->logo_pj, 350, 359, 'cache/' .  $item->logo_pj .'350x350.png', false, 2);			
				$item->full_image = 'https://intranet.fgct.com.br/media/images/logos/' . $item->logo_pj;
			}		
			else{
				$image_path = $image_default;
				$item->full_image = 'https://cnd.fgct.com.br/assets/img/noclube.png';

			}
		
			$image_contents = file_get_contents($image_path) ;
			$type = pathinfo($image_path, PATHINFO_EXTENSION);
			$image_base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_contents);
			$item->image_base64 = $image_base64;
		}


		$total = parent::getTotal();

		$limit = JRequest::getVar( 'limit','10', 'GET' );
		$page = JRequest::getVar( 'page','1', 'GET' );
		
		$response = new stdClass();
		$response->page = $page;
		$response->per_page = $limit;
		$response->total = $total;
		$response->total_pages = ceil($total/$limit);
		$response->data = $data;
		return $response;

	}


	public function getItem()
	{
		if($this->_db) {
			$get = JRequest::get( 'get' );
			if(isset($get['query_rest'][2]) && !empty($get['query_rest'][2]) && is_numeric($get['query_rest'][2])) {

				$query = $this->_db->getQuery(true);
				$query->select( $this->_db->quoteName(array( 'id',
															'name',
															'email',
															'numcr_pj',
															'cadastro_associado',
															'id_associado',
															'name_cidade',
															'name_estado',
															'logradouro_pj',
															'numero_pj',
															'complemento_pj',
															'bairro_pj',
															'telefone_pj',
															'logo_pj',

															)));
				
				$query->from( $this->_db->quoteName('#__users') );
				$query->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
				$query->innerJoin( $this->_db->quoteName('#__intranet_associado') . 'USING('. $this->_db->quoteName('id_user').')' );
				$query->leftJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').', '. $this->_db->quoteName('id_estado').')' );
				$query->leftJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado'). ')' );
				
				$query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote( $get['query_rest'][2] ));		
		
				$this->_db->setQuery($query);
				$result = $this->_db->loadObject();

				if(!empty($result->logo_pj))
					$image_path = $this->_resize->resize($this->_path_logo. $result->logo_pj, 350, 359, 'cache/' .  $result->logo_pj .'350x350.png', false, 2);
				else
					$image_path = $this->_resize->resize(JPATH_IMAGES .DS. 'noclube.png' , 350, 350, 'cache/noclube350x350.png', false, 2);
			
				$image_contents = file_get_contents($image_path) ;
				$type = pathinfo($image_path, PATHINFO_EXTENSION);
				$image_base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_contents);
				$result->image_base64 = $image_base64;



				return $result;

			}
		}
		return false;
	}


}