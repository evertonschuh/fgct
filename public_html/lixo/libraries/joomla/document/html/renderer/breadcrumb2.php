<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JDocument Module renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererBreadcrumb2 extends JDocumentRenderer
{
	/**
	 * Renders a module script and returns the results as a string
	 *
	 * @param   string  $module   The name of the module to render
	 * @param   array   $attribs  Associative array of values
	 * @param   string  $content  If present, module information from the buffer will be used
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 */
	 
	var $_db;
	
	public function __construct($config = array()) {   
		$this->_db	= JFactory::getDBO();
	}

	public function render($name, $params = array (), $content = null)
	{
		// Initialise variables.
		$buffer = null;
		$view = JRequest::getCmd('view');
		$layout = JRequest::getCmd('layout');
		$Breadcrumb = '';
		//$BreadcrumbURL = '';
		//$Breadcrumb = $this->;sitename;
		$BreadcrumbURL = '/|';
		if ($view != 'home' &&
			$view != '' &&
			$layout != 'course' &&
			$layout != 'coursep' &&
			$layout != 'coursee'
			
			
			):
		
			$viewBreadcrumb = $this->getView($view);
			if($layout)
				$layoutBreadcrumb = $this->getLayout($layout);
			
			$slug = JRequest::getVar('slug');
			$cid = JRequest::getVar('cid');
			
			$parentsBreadcrumb = $this->getParents($viewBreadcrumb->parent_sef);
			if($parentsBreadcrumb)	
				$Breadcrumb .= $parentsBreadcrumb;		
				
			$metaPosition = 2;
			if($viewBreadcrumb):
				if(isset($layoutBreadcrumb)):	
					if($cid)
						$cidLink = '&cid=' . $cid ;
					$Breadcrumb .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view='.$view . $cidLink) .'"><span itemprop="name">'.$viewBreadcrumb->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>';
						
					$titlePage = $layoutBreadcrumb->title_sef;
					$iconPage = $layoutBreadcrumb->icon_sef;
					$Breadcrumb .=  '<li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'.$layoutBreadcrumb->title_sef.'</span></span><meta itemprop="position" content="'.$metaPosition++.'"></li>';
				else:
					$titlePage = $viewBreadcrumb->title_sef;
					$iconPage = $viewBreadcrumb->icon_sef;
					$Breadcrumb .= '<li 4 class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="item"><span itemprop="name">'.$viewBreadcrumb->title_sef.'</span></span><meta itemprop="position" content="'.$metaPosition++.'"></li>';
				endif;
			endif;
			$BreadcrumbURL .=  JURI::current();


			$buffer .= "\n<section class=\"content-header\">";
			//$buffer .= "\n<div class=\"row\">";
			//$buffer .= "\n<div id=\"sp-title\" class=\"col-sm-12 col-md-12\">";
			//$buffer .= "\n<div class=\"sp-column\">";
		//	$buffer .= "\n<div class=\"sp-page-title\">";
		//	$buffer .= "\n<div class=\"container\">";
			$icon = "";
			if($iconPage)
				$icon = "<i class=\"fa ".$iconPage."\"></i>";
				
			$buffer .= "\n<h1>" .$icon . " " . $titlePage . "</h1>";
			$buffer .= "\n<ol class=\"breadcrumb\">";
			//$buffer .= "\n<span>Você está aqui:&nbsp;</span>";
			$buffer .= "\n<li> <i class=\"fa fa-dashboard\"></i> <a href=\"/minha-fbt\" >Home</a></li>";
			$buffer .= "\n" . $Breadcrumb;
			$buffer .= "\n</ol>";
			//$buffer .= "\n</div>";
			////$buffer .= "\n</div>";
			//$buffer .= "\n</div>";
			//$buffer .= "\n</div>";
			//$buffer .= "\n</div>";
			$buffer .= "\n</section>";








		endif;
		$this->title = 'FBT | ' . $titlePage;
			//$document = JFactory::getDocument();			
			//$document->setTitle('FBT | ' . $this->_teste);	
			//$this->_doc->setTitle('FBT | ' . $titlePage);
			//$document->setBreadcrumb($Breadcrumb);
			//$document->setBreadcrumbURL($BreadcrumbURL);	
		return $buffer;
	}
	
	function getType($id_sef = null)
	{
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('id_sef_type'));
		$query->from($this->_db->quoteName('#__sef'));
		$query->where($this->_db->quoteName('status_sef') . ' = 1 ');

		$query->where($this->_db->quoteName('id_sef') . ' = ' . $this->_db->quote($id_sef));
		
		$this->_db->setQuery($query);
		return $this->_db->loadResult();
		
	}
	
	
	function getParents($parent_sef = null)
	{
		//$i = 0;
		
		$type= $this->getType($parent_sef);
		
		$breadcrumbsReturn = '';
		$metaPosition = 0;
		if($parent_sef > 0):
			$breadcrumbs = array();
			while($parent_sef > 0) {
				//$i--;
				//echo $i;
				$cidLink = '';
				$vidLink = '';
				$uidLink = '';
				if($type==1)
					$viewBreadcrumb2 = $this->getView(null,$parent_sef);	
				else
					$viewBreadcrumb2 = $this->getLayout(null,$parent_sef);

				
				
				if(count($breadcrumbs)>0):
					//if($cid && $i>0)
				//	if($cid)
				//		$cidLink = '&cid=' . $cid .':'. JFilterOutput::stringURLSafe(  $cname );	
					array_unshift($breadcrumbs, '<li 1 itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view='. $viewBreadcrumb2->name_sef . $cidLink ).'"><span itemprop="name">'.$viewBreadcrumb2->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>');
				//	$Breadcrumb .= '<li class="active"></li>' . $viewBreadcrumb2->title_sef;
				else:
					//if($cid && $i>0)
				//	if($cid)
				//		$cidLink = '&cid=' . $cid .':'. JFilterOutput::stringURLSafe(  $cname );							
					$breadcrumbs[] = '<li 2 itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view=' . $viewBreadcrumb2->name_sef . $cidLink . $vidLink . $uidLink ).'"><span itemprop="name">'.$viewBreadcrumb2->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>';
					//$Breadcrumb .= ' | ' . $viewBreadcrumb2->title_sef;
				endif;
	
				$parent_sef = $viewBreadcrumb2->parent_sef;
			}
			foreach($breadcrumbs as $breadcrumb)
				$breadcrumbsReturn .= $breadcrumb;
				
			return $breadcrumbsReturn;
	
		else:
			return false;
		endif;
		
		
		
	}
	
	
	
	
	function getView($view = null, $parent_sef = null)
	{

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('title_sef',
												   'icon_sef',
												   'parent_sef',
												   'name_sef',
													)));
		$query->from($this->_db->quoteName('#__sef'));
		$query->where($this->_db->quoteName('id_sef_type') . ' = 1 ');
		$query->where($this->_db->quoteName('status_sef') . ' = 1 ');
		
		if($view)
			$query->where($this->_db->quoteName('name_sef') . ' = ' . $this->_db->quote($view));
		if($parent_sef)
			$query->where($this->_db->quoteName('id_sef') . ' = ' . $this->_db->quote($parent_sef));
				
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
		
	}
	
	function getLayout($layout = null, $parent_sef = null)
	{
		
		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('title_sef',
												   'icon_sef',
												   'parent_sef',
												   'name_sef',
													)));
		$query->from($this->_db->quoteName('#__sef'));
		$query->where($this->_db->quoteName('id_sef_type') . ' = 2 ');
		$query->where($this->_db->quoteName('status_sef') . ' = 1 ');
		
		if($layout)
			$query->where($this->_db->quoteName('name_sef') . ' = ' .  $this->_db->quote($layout));
		if($parent_sef)
			$query->where($this->_db->quoteName('id_sef') . ' = ' . $this->_db->quote($parent_sef));
		
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
		
	}
	
	function getTitle($slug = null, $view = null)
	{
		$title = $slug;
		$query = $this->_db->getQuery(true);
		
		switch ($view)
		{
			case 'blogs' :
				$query->select($this->_db->quoteName('name_article_category'));
				$query->from($this->_db->quoteName('#__article_category'));
				$query->where($this->_db->quoteName('slug_article_category') . ' = ' . $this->_db->quote($slug));
				$this->_db->setQuery($query);
				if((boolean) !$title = $this->_db->loadResult()) :
					$query->clear();
					$query->select($this->_db->quoteName('title_article'));
					$query->from($this->_db->quoteName('#__article'));
					$query->where($this->_db->quoteName('slug_article') . ' = ' . $this->_db->quote($slug));
				endif;
			break;
			case 'news' :
				$query->select($this->_db->quoteName('title_new'));
				$query->from($this->_db->quoteName('#__new'));
				$query->where($this->_db->quoteName('slug_new') . ' = ' . $this->_db->quote($slug));
			break;
			
			case 'teachers' :
				$query->select($this->_db->quoteName('name'));
				$query->from($this->_db->quoteName('#__users'));
				$query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($slug));
			break;
			
			
		}
		$this->_db->setQuery($query);
		$title = $this->_db->loadResult();
		return $title;
	}

}
