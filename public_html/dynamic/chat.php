<?php

include( 'joomla.inc.php' );

$obj = new FbtDynamicSelect();

class FbtDynamicSelect {
	
	var $_db = null;
	var $_data = null;
	
	function __construct()
	{	
	
		$lang = JFactory::getLanguage();
		$extension = 'tpl_system';
		$base_dir = JPATH_SITE;
		$language_tag = 'pt-BR';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);




		$type_data_chart = JRequest::getVar( 'type_data_chart', '', 'post' );
		$execute = JRequest::getVar( 'execute', '', 'post' );
		

		if ( !empty( $type_data_chart ) || $type_data_chart==='0')
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);
			if($type_data_chart=='0' || $type_data_chart=='1')
				$table = 'question_group';
			elseif($type_data_chart=='2')
				$table = 'question_factor';
			else{
				echo '<option disabled selected class="default" value="">' . JText::_('- Erro ao Obter Dados -') . '</option>';
				return;
			}

				//$query->select('id_'.$table.' as value, name_'.$table.' as text');
				$query->select('id_'.$table.' as value, CONCAT(id_'.$table.', \' - \', name_'.$table.', IF(status_'.$table.'=0, \' (Desativado)\', \'\')) as text');
				$query->from('#__'.$table);

				$query->order($this->_db->quoteName('name_'.$table));
				
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

				echo '<option disabled selected class="default" value="">' . JText::_('- Selecione -') . '</option>';
				if($rows)
					echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 


		}
		
		if(!empty( $execute ) && $execute = 'getCharts')
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);			
			$query->select('id_chart as value, CONCAT(id_chart, \' - \', name_chart) as text');
			$query->from('#__chart');		
			$query->order('text');
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();

			if($rows)
				echo json_encode($rows); 	

		}

		//echo '<option disabled selected class="default" value="">' . JText::_($type_data_chart) . '</option>';


/*



		$id_estado = JRequest::getVar( 'id_estado', '', 'post' );
		$estado_buscacep = JRequest::getVar( 'estado_buscacep', '', 'post' );
		$polos = JRequest::getVar( 'polos', '', 'post' );
		$subject = JRequest::getVar( 'subject', '', 'post' );
		$id_convenio = JRequest::getVar( 'id_convenio', '', 'post' );
		$type_course = JRequest::getVar( 'type_course', '', 'post' );
		$id_estado_polo = JRequest::getVar( 'id_estado_polo', '', 'post' );
		$id_cidade_polo = JRequest::getVar( 'id_cidade_polo', '', 'post' );
		$calc_parcelas = JRequest::getVar( 'calc_parcelas', '', 'post' );
		$plots_evento = JRequest::getVar( 'plots_evento', '', 'post' );
		$id_conteudo_type = JRequest::getVar( 'id_conteudo_type', '', 'post' );

		$id_professor = JRequest::getVar( 'id_professor', '', 'post' );
		$condition_search = JRequest::getVar( 'condition_search', '', 'post' );
		
		if ( !empty( $id_professor ))
		{
			$this->_db	= JFactory::getDBO();
			$_user = JFactory::getUser();
				
			$query = $this->_db->getQuery(true);
			$query->select(array('id_conteudo_type', 
								 'table_conteudo_type',
								 'parent_conteudo_type',
								 ));
			$query->from('#__conteudo_type');
			$query->where( $this->_db->quoteName('status_conteudo_type') . ' = ' . $this->_db->quote( '1' ) );
			$query->order('table_conteudo_type ASC');
			$this->_db->setQuery($query);
			$typesArray = $this->_db->loadObjectList();	
		
		
			$query->clear();
			$query->select('CONCAT(id_conteudo_type, \'-\', id_conteudo ) as value');
			
			$query->from('#__turma_structure');
			
			$selectNameBeg='';
			$selectNameEnd='';
			foreach($typesArray as $type):
				$selectNameBeg .= 'IF(ISNULL(#__' . $type->table_conteudo_type. '.name_'. $type->table_conteudo_type . '), ';
				$selectNameEnd = ', #__' . $type->table_conteudo_type. '.name_'. $type->table_conteudo_type . ') ' . $selectNameEnd;				
			
				$query->leftJoin($this->_db->quoteName('#__' . $type->table_conteudo_type ) . ' ON(' 
														. $this->_db->quoteName('#__' . $type->table_conteudo_type. '.id_' . $type->table_conteudo_type) . '=' . $this->_db->quoteName('#__turma_structure.id_conteudo')
														. ' AND '
														. $this->_db->quoteName('#__turma_structure.id_conteudo_type') . '=' . $this->_db->quote($type->id_conteudo_type)
														. ')');	
	
				$query->where( '('
								. $this->_db->quoteName('#__' . $type->table_conteudo_type. '.status_' . $type->table_conteudo_type).'='.$this->_db->quote('1') 
								. ' OR'
								. $this->_db->quoteName('#__' . $type->table_conteudo_type. '.status_' . $type->table_conteudo_type).'IS NULL' 
								. ')'
								);
			endforeach;
			
			$query->select( $selectNameBeg . ' NULL ' . $selectNameEnd  . ' AS text');
	
			if( !$_user->authorize('core.academico', 'com_fbt') ):
				$query->where( $this->_db->quoteName('id_professor_structure') . '=' . $this->_db->quote( $_user->get('id') ) );
			else:
				$query->where( $this->_db->quoteName('id_professor_structure') . '=' . $this->_db->quote( $id_professor) );
			endif;
			
			if ( !empty( $condition_search ) )	
				$query->where( $this->_db->quoteName($condition_search).'='.$this->_db->quote('1') );
				
			$query->group($this->_db->quoteName('id_conteudo_type'));
			$query->group($this->_db->quoteName('id_conteudo'));
		
			$query->order($this->_db->quoteName('text'));
			
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();

			if($rows)
				echo JHTML::_('select.checkboxlist', $rows, 'group_conteudos','class="group-checkboxes required"', 'value', 'text' );
			else
				echo '<label>Nenhum Conteúdo encontrado pra o professor selecionado<input type="checkbox" class="group-checkboxes required hidden"></label>';
			
		}
		
		if ( !empty( $id_conteudo_type ) && empty( $id_turma ) )
		{
			$this->_db	= JFactory::getDBO();
			$_user = JFactory::getUser();
			
			$query = $this->_db->getQuery(true);
			$query->select('table_conteudo_type');
			$query->from('#__conteudo_type');
			$query->where( $this->_db->quoteName('id_conteudo_type') . '=' . $id_conteudo_type );
			$this->_db->setQuery($query);
			$table = $this->_db->loadResult();

			if($table)
			{
				$query->clear();
				$query->select('id_'.$table.' as value, name_'.$table.' as text');
				$query->from('#__'.$table);
				if( !$_user->authorize('core.academico', 'com_fbt') )
				$query->innerJoin($this->_db->quoteName('#__turma_structure') . ' ON(' 	. $this->_db->quoteName('id_conteudo'). '=' . $this->_db->quoteName('id_'.$table) 
																			  			. '	AND ' 
																						. $this->_db->quoteName('id_conteudo_type'). '=' . $this->_db->quote($id_conteudo_type)
																					//	. '	AND ' 
																					//	. $this->_db->quoteName('id_turma'). '=' . $this->_db->quote($id_turma)
																						. ')');

	
			//	if ( !empty( $condition_search ) )	
			//		$query->where( $this->_db->quoteName($condition_search).'='.$this->_db->quote('1') );
					
				if( !$_user->authorize('core.academico', 'com_fbt') )
					$query->where( $this->_db->quoteName('id_professor_structure') . '=' . $this->_db->quote( $_user->get('id') ) );
				
				$query->where($this->_db->quoteName('status_'.$table).'='.$this->_db->quote('1') );
				$query->group($this->_db->quoteName('id_'.$table));
				$query->order($this->_db->quoteName('name_'.$table));
				
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
			
			echo '<option disabled selected class="default" value="">' . JText::_('- Grupo Acadêmico -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}

		
		if ( !empty( $id_conteudo_type ) && !empty( $id_turma ) )
		{
			$this->_db	= JFactory::getDBO();
			$_user = JFactory::getUser();
			
			$query = $this->_db->getQuery(true);
			$query->select('table_conteudo_type');
			$query->from('#__conteudo_type');
			$query->where( $this->_db->quoteName('id_conteudo_type') . '=' . $id_conteudo_type );
			$this->_db->setQuery($query);
			$table = $this->_db->loadResult();

			if($table)
			{
				$query->clear();
				$query->select('id_'.$table.' as value, name_'.$table.' as text');
				$query->from('#__'.$table);
				$query->innerJoin($this->_db->quoteName('#__turma_structure') . ' ON(' 	. $this->_db->quoteName('id_conteudo'). '=' . $this->_db->quoteName('id_'.$table) 
																			  			. '	AND ' 
																						. $this->_db->quoteName('id_conteudo_type'). '=' . $this->_db->quote($id_conteudo_type)
																						. '	AND ' 
																						. $this->_db->quoteName('id_turma'). '=' . $this->_db->quote($id_turma)
																						. ')');

	
				if ( !empty( $condition_search ) )	
					$query->where( $this->_db->quoteName($condition_search).'='.$this->_db->quote('1') );
					
				if( !$_user->authorize('core.academico', 'com_fbt') )
					$query->where( $this->_db->quoteName('id_professor_structure') . '=' . $this->_db->quote( $_user->get('id') ) );
				
				$query->where($this->_db->quoteName('status_'.$table).'='.$this->_db->quote('1') );
				$query->group($this->_db->quoteName('id_'.$table));
				$query->order($this->_db->quoteName('name_'.$table));
				
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
			
			echo '<option disabled selected class="default" value="">' . JText::_('- Grupo Acadêmico -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}

		if ( !empty( $calc_parcelas ) )
		{
			$parcelas = array();
			for ($i = '1'; $i <= $plots_evento; $i++) {
				$value =  number_format(round($calc_parcelas/$i,2), 2, ',', '.');
				$parcelas[] = JHTML::_('select.option', $i, JText::_( str_replace(' ','&ensp;',substr( ' '.$i , -2). ' x de R$ ') ). $value , 'value', 'text' );
			}
			$parcelas = array_reverse($parcelas); 
			echo '<option disabled selected class="default" value="">' . JText::_('- Selecione -') . '</option>';
			echo JHTML::_('select.options', $parcelas, 'value', 'text');
		}


		if ( !empty( $id_convenio ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);
			if($type_course=='3')
				$query->select('percent_ext_convenio');
			else
				$query->select('percent_convenio');
				
			$query->from('#__convenio');
			$query->where('id_convenio = '. $this->_db->quote( $id_convenio ));
			$query->where('status_convenio = 1');
			$this->_db->setQuery($query);
			echo $this->_db->loadResult();
		}


		if ( !empty( $id_estado_polo ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);
			$query->select('id_cidade as value, name_cidade as text');
			$query->from('#__cidade');
			$query->innerJoin('#__polo USING('.$this->_db->quoteName('id_cidade').','.$this->_db->quoteName('id_estado').')');
			$query->where('id_estado = '. $this->_db->quote( $id_estado_polo ));
			$query->where('status_cidade = 1');
			$query->where('status_polo = 1');
			$query->group('id_cidade');
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo '<option disabled selected class="default" value="">' . JText::_('FBT_GLOBAL_CIDADE_SELECT') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text');
		}
		
		
		if ( !empty( $id_cidade_polo ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);
			$query->select('id_polo as value, CONCAT (name_polo, \' | \', logradouro_polo, \', \', numero_polo) AS text');
			$query->from('#__polo');
			$query->where('id_cidade = '. $this->_db->quote( $id_cidade_polo ));
			$query->where('status_polo = 1');
			$query->order($this->_db->quoteName('name_polo'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo JHTML::_('select.radiolist',  $rows, 'id_polo','class="required"', 'value', 'text' ); 
		}

		
		if ( !empty( $subject ) )
		{			
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName(array( 'file_contact',
														'description_contact'
														)));
			$query->from($this->_db->quoteName('#__contact'));
			$query->where( $this->_db->quoteName('status_contact').'='.$this->_db->quote('1') );
			$query->where( $this->_db->quoteName('id_contact').'='.$this->_db->quote($subject) );

			$this->_db->setQuery($query);
			$item = $this->_db->loadObject();
			
			if ($item->description_contact || $this->item->file_contact)
			{
				if ($item->description_contact)
				{
					echo '
					<div class="sppb-col-md-12">
						<div class="form-group">
							<div class="sppb-row">
								<div class="sppb-col-md-12">' . 
								$item->description_contact . '
								</div>
							</div>
						</div>
					</div>';
				}

				if ($item->file_contact)
				{
					echo '	
					<div class="sppb-col-md-12">
						<div class="form-group">
							<div class="sppb-row">
								<div class="sppb-col-md-6">
									<label for="subject"><strong>Enviar Arquivo Anexo:</strong></label>
									<!-- image-preview-filename input [CUT FROM HERE]-->
									<div class="input-group image-preview">
										<input type="text" class="form-control image-preview-filename" disabled="disabled">
										<span class="input-group-btn">
											<div class="btn btn-default image-preview-input">
												<span class="glyphicon glyphicon-folder-open"></span>
												<span class="image-preview-input-title">Localizar</span>
												<input type="file" name="file_contact"/> <!-- rename it -->
											</div>
										</span>
									</div><!-- /input-group image-preview [TO HERE]--> 
								</div>
							</div>
						</div>
					</div>';
				}
			}
			else
				echo '';
		}
		
		
		if ( !empty( $id_estado ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);		
			$query->select($this->_db->quoteName('id_cidade') .' as value, '. $this->_db->quoteName('name_cidade') . ' as text');
			$query->from($this->_db->quoteName('#__cidade'));
			$query->where($this->_db->quoteName('status_cidade') . ' = 1');
			$query->where($this->_db->quoteName('id_estado') . ' = '. $this->_db->quote( $id_estado ));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo '<option disabled selected class="default" value="">' . JText::_('FBT_GLOBAL_CIDADE_SELECT') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}
		
		
		if ( !empty( $estado_buscacep ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);		
			$query->select($this->_db->quoteName('id_cidade') .' as value, '. $this->_db->quoteName('name_cidade') . ' as text');
			$query->from($this->_db->quoteName('#__cidade'));
			$query->where($this->_db->quoteName('status_cidade') . ' = 1');
			$query->where($this->_db->quoteName('id_estado') . ' = '. $this->_db->quote( $estado_buscacep ));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo '<option disabled selected class="default" value="">' . JText::_('FBT_GLOBAL_CIDADE_SELECT') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}
		
		
		if ( !empty( $polos ) )
		{
			$this->_db	= JFactory::getDBO();
			$query = $this->_db->getQuery(true);
			
			
			
			$query = $this->_db->getQuery(true);
			
			$query->select($this->_db->quoteName(array(	'sigla_estado',
														'name_estado',
														'id_cidade',
														'name_cidade',
														'name_polo',
														'logradouro_polo',
														'numero_polo')));
			
			$query->from($this->_db->quoteName('#__polo'));
			
			$query->innerJoin($this->_db->quoteName('#__cidade') . ' USING(' . $this->_db->quoteName('id_cidade'). ', ' . $this->_db->quoteName('id_estado'). ' )');
			$query->innerJoin($this->_db->quoteName('#__estado') . ' USING(' . $this->_db->quoteName('id_estado'). ' )');
			$query->where( $this->_db->quoteName('sigla_estado').'='.$this->_db->quote($polos) );
			$query->where( $this->_db->quoteName('status_polo').'='.$this->_db->quote('1') );
			$query->order( $this->_db->quoteName('name_cidade') );
			$this->_db->setQuery($query);
			
			$polosList = $this->_db->loadObjectList();
			
			
			
			$returnpolos = '<div class="space"></div><span class="title-2"><strong>'.$polosList[0]->name_estado.'</strong></span>';
			$returnpolos .= '<div class="panel-group" id="accordion">';
			

			
			//$heading = '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"></h4></div>';
			
			//$returnpolos = '<div class="space"></div><h4><strong>'.$polosList[0]->name_estado.'</strong></h4><ul class="list-group">';
			
			foreach($polosList as $i => $polo)
			{
				
				if($polosList[$i -1]->name_cidade != $polo->name_cidade)
				{
					$polosTotal = 0;
					$heading = '<div class="panel panel-default accordion">
								<div class="panel-heading " data-toggle="collapse" data-parent="#accordion" href="#collapse'.$polo->id_cidade.'">
								<span class="panel-title">' . $polo->name_cidade;
					$items = '<div id="collapse'.$polo->id_cidade.'" class="panel-collapse collapse">
							  <ul class="list-group">';
                
				}
				
				$polosTotal++;
				$namePolo = $polo->name_polo;
				
				$items .= '<li class="list-group-item"><div class="row"><div class="col-md-4 text-left">'.$polo->name_polo.'</div><div class="col-md-8 text-left">'.$polo->logradouro_polo.','.$polo->numero_polo.'</div></div></li>';
			
			
			
				if($polosList[$i+1]->name_cidade != $polo->name_cidade)
				{
					if($polosTotal == 1)
						$heading .= '&nbsp;-&nbsp;'.$namePolo.'<i class="accordion-toggle fa fa-plus-square-o pull-right" ></i></div>' ;
					else
						$heading .= '&nbsp;-&nbsp;'.$polosTotal.'&nbsp;Polos<i class="accordion-toggle fa fa-plus-square-o pull-right" ></i></div>' ;
						
					$returnpolos .= $heading . $items . '</ul></div></div>';	
						
				}
			
			
			
			
			}
			$returnpolos .= '</div>';

			echo $returnpolos; 
		}
*/

		
	}
}

