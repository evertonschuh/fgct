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
	var $_class_modalidade = null;
	
	
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
		
		
		JRequest::setVar('tmpl','print');	

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
			$queryClube = $this->_db->getQuery(true);
			$queryClube->select( $this->_db->quoteName(array( 'name_etapa',
															  'id_etapa',
															  'id_campeonato',
															  'id_clube',
															  'logradouro_pj',
															  'numero_pj',
															  'name_cidade',
															  'sigla_estado',
															  'name_modalidade',
															  'data_beg_etapa',
															  'data_end_etapa',
															)));
																
			$queryClube->select( $this->_db->quoteName('id_clube') . ' AS id_local' );
			$queryClube->select( $this->_db->quoteName('name') . ' AS name_clube' );
			$queryClube->select( 'CONCAT (' . $this->_db->quoteName('ano_campeonato') . ', \' - \',' . $this->_db->quoteName('name_campeonato') . ') AS name_campeonato' );
			$queryClube->from( $this->_db->quoteName('#__users') );
			$queryClube->innerJoin( $this->_db->quoteName('#__intranet_pj') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
			$queryClube->innerJoin( $this->_db->quoteName('#__intranet_estado') . 'USING('. $this->_db->quoteName('id_estado').')' );	
			$queryClube->innerJoin( $this->_db->quoteName('#__intranet_cidade') . 'USING('. $this->_db->quoteName('id_cidade').','. $this->_db->quoteName('id_estado').')' );	
			$queryClube->innerJoin( $this->_db->quoteName('#__ranking_prova_clube_map') . 'ON('. $this->_db->quoteName('id').'='. $this->_db->quoteName('id_clube').')' );	
			$queryClube->innerJoin( $this->_db->quoteName('#__ranking_etapa_clube_map') . 'USING('. $this->_db->quoteName('id_clube').')' );	
			$queryClube->innerJoin( $this->_db->quoteName('#__ranking_etapa') . 'USING('. $this->_db->quoteName('id_etapa').')' );	
			$queryClube->innerJoin( $this->_db->quoteName('#__ranking_campeonato') . 'USING('. $this->_db->quoteName('id_campeonato').')' );
			$queryClube->innerJoin( $this->_db->quoteName('#__ranking_modalidade') . 'USING('. $this->_db->quoteName('id_modalidade').')' );
			$queryClube->where($this->_db->quoteName('finalized_campeonato') . ' = ' . $this->_db->quote( '0' ));		


			$queryAtleta = $this->_db->getQuery(true);
			$queryAtleta->select( $this->_db->quoteName(array( 	'name',
																'id',
																'id_associado',
																'cpf_pf',
																'compressed_air_pf',
																'copa_brasil_pf',
																//'logradouro_pj',
															)));
			$queryAtleta->from( $this->_db->quoteName('#__users') );
			$queryAtleta->innerJoin( $this->_db->quoteName('#__intranet_pf') . 'ON('. $this->_db->quoteName('id'). ' = '. $this->_db->quoteName('id_user').')' );
			$queryAtleta->innerJoin( $this->_db->quoteName('#__intranet_associado') . 'USING('. $this->_db->quoteName('id_user').')' );	
			$queryAtleta->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote( $this->_user->get('id') ));


			
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

														 'name_especie',
														 'name_calibre',
														 'name_marca',
														 'numero_arma',
														 'registro_arma',


														 'Etapa.name_etapa',
														 'Etapa.id_etapa',
														 'Etapa.id_campeonato',
														 'Etapa.name_campeonato',
														 'Etapa.id_clube',
														 'Etapa.name_clube',
														 'Etapa.logradouro_pj',
														 'Etapa.numero_pj',
														 'Etapa.name_cidade',
														 'Etapa.sigla_estado',
														 'Etapa.name_modalidade',
														 'Etapa.data_beg_etapa',
														 'Etapa.data_end_etapa',

														 
														)));

			$query->select('Atleta.name AS name_atleta');
			$query->select('Atleta.cpf_pf AS cpf_atleta');
			$query->select('Atleta.id_associado AS id_associado');
			$query->select('Atleta.compressed_air_pf AS compressed_air_pf');
			$query->select('Atleta.copa_brasil_pf AS copa_brasil_pf');


			
			$query->select('IF(registro_tipo_arma = 1, \'Exército (Sigma)\', IF(registro_tipo_arma = 2, \'Polícia Federal (Sinarm)\', \'\')) AS registro_name_arma');	
			
			$query->select('IF(ISNULL(id_addequipe), IF(ISNULL(id_equipe), Estado.name_estado, IF( Equipe.id = 7617, \'AVULSO\', Equipe.name)), name_addequipe) AS name_equipe');	

			$query->from( $this->_db->quoteName('#__ranking_inscricao') );
			$query->innerJoin( $this->_db->quoteName('#__ranking_genero') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_prova').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_categoria') . 'USING('. $this->_db->quoteName('id_genero').','. $this->_db->quoteName('id_categoria').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_classe') . 'USING('. $this->_db->quoteName('id_categoria') .','. $this->_db->quoteName('id_classe').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_prova') . 'USING('. $this->_db->quoteName('id_prova') .','. $this->_db->quoteName('id_campeonato').')' );
			$query->innerJoin( $this->_db->quoteName('#__ranking_inscricao_etapa')  . 'USING('. $this->_db->quoteName('id_inscricao') . ')' );												   
			$query->innerJoin( '(' . $queryClube . ') AS Etapa USING('. $this->_db->quoteName('id_etapa').','. $this->_db->quoteName('id_campeonato').','. $this->_db->quoteName('id_local').')' );
			
			$query->innerJoin( '(' . $queryAtleta . ') AS Atleta ON('. $this->_db->quoteName('#__ranking_inscricao.id_user') .'='. $this->_db->quoteName('Atleta.id'). ')' );;



			//$query->innerJoin( $this->_db->quoteName('#__users') . ' AS Atleta ON('. $this->_db->quoteName('#__ranking_inscricao.id_user') .'='. $this->_db->quoteName('Atleta.id'). ')' );
			//$query->innerJoin( $this->_db->quoteName('#__intranet_pf') . ' AS AtletaPF ON('. $this->_db->quoteName('#__ranking_inscricao.id_user') .'='. $this->_db->quoteName('AtletaPF.'). ')' );
			
			$query->leftJoin( $this->_db->quoteName('#__users') . ' AS Equipe ON('. $this->_db->quoteName('#__ranking_inscricao.id_equipe') .'='. $this->_db->quoteName('Equipe.id'). ')' );
			$query->leftJoin($this->_db->quoteName('#__intranet_estado') . ' AS Estado USING( ' . $this->_db->quoteName('id_estado') . ')' );			
			$query->leftJoin( $this->_db->quoteName('#__intranet_addequipe') . ' AS AddEquipe USING('. $this->_db->quoteName('id_addequipe') . ')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_arma') . 'USING('. $this->_db->quoteName('id_arma').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_especie') . 'USING('. $this->_db->quoteName('id_especie').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_calibre') . 'USING('. $this->_db->quoteName('id_calibre').')' );
			$query->leftJoin( $this->_db->quoteName('#__intranet_marca') . 'USING('. $this->_db->quoteName('id_marca').')' );
	

			$query->where($this->_db->quoteName('id_inscricao_etapa') . ' = ' . $this->_db->quote( $this->_id ));

			$this->_db->setQuery($query);

			$this->_data =  $this->_db->loadObject();

			//$this->_data->skin_documento_numero = '/media/assets/img/ptimbrado/' . $this->_data->skin_documento. '.jpg';

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
	
	function getTagLanguage()
	{
		if($this->_id)
		{
			if(!$this->_class_modalidade)
				$this->_class_modalidade = $this->getClassModalidade();
							
			if($this->_class_modalidade){
				$this->_tagLanguage = $this->_class_modalidade->getTagLanguage();
				return $this->_tagLanguage;
			}
		}	
		return false;
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

	function getAdditionalPrint()
	{
		if((boolean) $inscricao = $this->getItem())
		{
			$options = array();
			$options['inscricao'] = $inscricao;
			$options['agendamento'] = $this->getAgendamentosPrint();

			if(!$this->_class_modalidade)
				$this->_class_modalidade = $this->getClassModalidade();	
							
			if($this->_class_modalidade)
				return $this->_class_modalidade->getAdditionalPrint( $options );
				
		}	
	}
	

	
		
	function getClassModalidade()
	{
		if($this->_id) {
			$query	= $this->_db->getQuery(true);
			$query->select($this->_db->quoteName('file_modalidade'));	


			$query->from($this->_db->quoteName('#__ranking_inscricao_etapa'));
			$query->innerJoin($this->_db->quoteName('#__ranking_inscricao') . 'USING(' . $this->_db->quoteName('id_inscricao') . ')');		
			$query->innerJoin($this->_db->quoteName('#__ranking_campeonato') . 'USING(' . $this->_db->quoteName('id_campeonato') . ')');		
			$query->innerJoin($this->_db->quoteName('#__ranking_modalidade') . 'USING(' . $this->_db->quoteName('id_modalidade') . ')');	
			$query->where($this->_db->quoteName('id_inscricao_etapa') . ' = ' . $this->_db->quote( $this->_id ));		  
			$this->_db->setQuery($query);
	
	
	
			if( !(boolean) $fileModalidade =  $this->_db->loadObject())
				return false;

			$file =  JPATH_LIBRARIES .DS. 'classes' .DS. 'core' .DS. $fileModalidade->file_modalidade;

			if (!file_exists($file))
				return false;

			require_once($file);

			$prefix  = 'TorneiosClasses';
			$type = JFile::getName($fileModalidade->file_modalidade);
			$type = str_replace('.' . JFile::getExt($type), '', $type);
			$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
			$metodClass = $prefix . ucfirst($type);
			
			if (!class_exists($metodClass))
				return false;

			return new $metodClass();

		}	
		return false;
	}

	
}