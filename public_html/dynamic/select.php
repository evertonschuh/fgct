<?php

include( 'joomla.inc.php' );

$obj = new EASistemasDynamicSelect();

class EASistemasDynamicSelect {
	
	var $_db = null;
	var $_app = null;
	var $_user = null;
	var $_siteOffset = null;

	function __construct()
	{	
	
		$lang = JFactory::getLanguage();
		$extension = 'tpl_system';
		$base_dir = JPATH_SITE;
		$language_tag = 'pt-BR';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
        $this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();	
		$this->_user = JFactory::getUser();
		$this->_siteOffset = $this->_app->getCfg('offset');


		$id_estado = JRequest::getVar( 'id_estado', '', 'post' );
		$id_corredor_categoria = JRequest::getVar( 'id_corredor_categoria', '', 'post' );
		if ( !empty( $id_estado ) )
		{
			$query = $this->_db->getQuery(true);		
			$query->select($this->_db->quoteName('id_cidade') .' as value, '. $this->_db->quoteName('name_cidade') . ' as text');
			$query->from($this->_db->quoteName('#__intranet_cidade'));
			$query->where($this->_db->quoteName('status_cidade') . ' = 1');
			$query->where($this->_db->quoteName('id_estado') . ' = '. $this->_db->quote( $id_estado ));
			$query->order($this->_db->quoteName('ordering'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();
			echo '<option disabled selected class="default" value="">' . JText::_('- Cidades -') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}

		if ( !empty( $id_corredor_categoria ) )
		{

			$rows = array();
			$arrayTest = array();
			if(!empty($id_corredor_categoria)) {

				$query = $this->_db->getQuery(true);
				$query->select('mes_treino');
				$query->from('#__treino');
				$query->where($this->_db->quoteName('id_corredor_categoria') . '=' . $this->_db->quote($id_corredor_categoria));
				$this->_db->setQuery($query);
				$mesesDisabled = $this->_db->loadColumn();
	
				$mesInicio = JFactory::getDate('now - 2 month', $this->_siteOffset )->toFormat('%Y-%m', true) . '-01';
				for($x = 1; $x <= 4; $x++){
					$disabled = '';
					$value = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%Y-%m-%d', true);
					$text = JFactory::getDate($mesInicio . ' + '. $x .' month', $this->_siteOffset )->toFormat('%m/%Y', true);
					if(in_array($value, $mesesDisabled)){
						$disabled  = ' disabled="disabled"';
						$text .= ' (Treino Existente)';
					}	
					$rows[] = JHTML::_('select.option', $value, $text, 'value', 'text', $disabled );
				}
			}
			echo '<option disabled selected class="default" value="">' . JText::_('- Mês do Treino -') . '</option>';
			echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
		}
		
		/*
		if ( !empty( $id_module ) )
		{
			$query = $this->_db->getQuery(true);
			$query->select('slug_module');
			$query->from('#__module');
			$query->where( $this->_db->quoteName('id_module') . '=' . $id_module );
			$this->_db->setQuery($query);
			$module = $this->_db->loadResult();

			if($module)
			{
				$query->clear();
				$query->select('id_module_'.$module.' as value, name_module_'.$module.' as text');
				$query->from('#__module_'.$module);
				$query->order($this->_db->quoteName('name_module_'.$module));
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
			
			echo '<option disabled selected class="default" value="">' . JText::_('- Item do Módulo -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}

		if ( !empty( $id_conteudo_type ) && empty( $id_conteudo ) )
		{
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
				$query->order($this->_db->quoteName('name_'.$table));
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
			
			echo '<option disabled selected class="default" value="">' . JText::_('- Conteúdo Acadêmico -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}
		
		if ( !empty( $id_conteudo_type_id ) )
		{
			$query = $this->_db->getQuery(true);
			$query->select('table_conteudo_type');
			$query->from('#__conteudo_type');
			$query->where( $this->_db->quoteName('id_conteudo_type') . '=' . $id_conteudo_type_id );
			$this->_db->setQuery($query);
			$table = $this->_db->loadResult();

			if($table)
			{
				$query->clear();
				$query->select('id_'.$table.' as value, CONCAT( id_'.$table.', \' - \', name_'.$table.') as text');
				$query->from('#__'.$table);
				if ( !empty( $id_conteudo_id ) )
					$query->where( $this->_db->quoteName('id_'.$table) . '<>' . $id_conteudo_id );
				$query->order($this->_db->quoteName('id_'.$table));
				$this->_db->setQuery($query);
				$rows = $this->_db->loadObjectList();

			}
            if ( empty( $id_conteudo_id ))
                echo '<option disabled selected class="default" value="">' . JText::_('- Conteúdo Acadêmico -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}
		
		if ( !empty( $id_conteudo )  && !empty( $id_conteudo_type ))
		{
			$query = $this->_db->getQuery(true);
			$query->select('id_turma as value, name_turma as text');
			$query->from('#__turma');
			$query->innerJoin($this->_db->quoteName('#__turma_structure').' USING ('.$this->_db->quoteName('id_turma').')');	
			$query->where( $this->_db->quoteName('id_conteudo_type') . '=' . $id_conteudo_type );
			$query->where( $this->_db->quoteName('id_conteudo') . '=' . $id_conteudo );
			$query->where( $this->_db->quoteName('status_turma') . '= 1' );

			$query->group($this->_db->quoteName('id_turma'));

			$query->order($this->_db->quoteName('name_turma'));
			$this->_db->setQuery($query);
			$rows = $this->_db->loadObjectList();

			echo '<option disabled selected class="default" value="">' . JText::_('- Turmas -') . '</option>';
			if($rows)
				echo JHTML::_('select.options',  $rows, 'value', 'text', ''); 
			
		}

		if ( !empty( $id_turma ) )
		{
			$cid = array();
			if(isset($cids) && !empty($cids) && is_array($cids) && count($cids)>0){
				foreach($cids as $key => $value)
					$cid[] = $value['value'];
			}


            $query = $this->_db->getQuery(true);			
            $query->select($this->_db->quoteName(array( 'name',
                                                        'id_inscricao',
                                                        )));
            $query->from($this->_db->quoteName('#__inscricao'));
            $query->innerJoin($this->_db->quoteName('#__users').' ON('.$this->_db->quoteName('user_id').'='.$this->_db->quoteName('id').')');


            $query->leftJoin($this->_db->quoteName('#__pagamento').' ON('.$this->_db->quoteName('#__inscricao.id_inscricao').'='.$this->_db->quoteName('#__pagamento.id_pagamento_produto')	
                                                                        . ' AND '
                                                                        . $this->_db->quoteName('#__pagamento.id_pagamento') . '=' . $this->_db->quoteName('#__inscricao.id_pagamento')	
                                                                        . ')');
            

			$query->where( $this->_db->quoteName('id_turma') . '=' . $this->_db->quote( $id_turma  ) );
            $query->where('(' .	
                '(' . 	
                    $this->_db->quoteName('status_pagamento') . '=' . $this->_db->quote( '1' ) .	
                    ' AND ' .	
                    '(' . 		
                        '(' .	
                            $this->_db->quoteName('vencimento_pagamento') . '>=' . $this->_db->quote( JFactory::getDate('now - 6 month', $this->_siteOffset )->toFormat('%Y-%m-%d',true)) . 
                        ' AND ' .	
                            $this->_db->quoteName('#__pagamento.ordering') . '>' . $this->_db->quote( '1' ) .	
                        ')' . 	
                        ' OR ' .
                        $this->_db->quoteName('confirmado_pagamento') . 'IS NOT NULL ' .
                    ')' . 
                ')' .	
                ' OR ' .
                '(' . 		
                    $this->_db->quoteName('status_pagamento') . ' IS NULL' .
                    ' AND ' .
                    $this->_db->quoteName('free_inscricao') . '>' . $this->_db->quote( '0' ) .	
                ')' . 
			')');	
			//if(count($cid)>0)
			//	$query->where( $this->_db->quoteName('id_inscricao') . 'NOT IN(' . implode(',', $cid) . ')');

            $query->where( $this->_db->quoteName('id_situation') . '=' . $this->_db->quote( '1' ) );
            $query->where( $this->_db->quoteName('status_inscricao') . '=' . $this->_db->quote( '1' ) );
            $query->group($this->_db->quoteName('id_inscricao'));	


            
           // $query->group($this->_db->quoteName('user_id'));
            $this->_db->setQuery($query);
			$alunos = $this->_db->loadObjectList();	
			//htmlResponse = '';
			if(count($alunos)>0)
				foreach($alunos as $i => $aluno)
					echo '<div itemid="itm-' .$aluno->id_inscricao.'" class="btn btn-default box-item" style="white-space: normal;" data-turma="' .$id_turma.'" data-inscricao="' .$aluno->id_inscricao.'" data-origem="'.$id_elemento.'"><table width="100%"><tr><td rowspan="2" width="100%" align="center" class="name">'. $aluno->name. '</td><td class="cont-mentor"></td></tr><tr><td class="cont-mentorado"></td></tr></table></div>';											
			else
				echo '<h4>Nenhum Aluno Localizado nesta turma</h4>';
					//echo '<div itemid="itm-' .$aluno->id_inscricao.'" class="btn btn-default box-item"><input type="hidden" value="' .$aluno->id_inscricao .'" name="id_isncricao[]">'. $aluno->name. '</div>';
			
		}	
		*/
	}
}

