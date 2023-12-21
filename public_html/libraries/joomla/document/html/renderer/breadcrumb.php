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
class JDocumentRendererBreadcrumb extends JDocumentRenderer
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
		
		//$Breadcrumb = $this->sitename;
		$BreadcrumbURL = '/|';
		if ($view != 'fbt' &&
			$view != '' &&
			$layout != 'course' &&
			$layout != 'coursep' &&
			$layout != 'coursee'&&
			$layout != 'freeregister'&&
			$layout != 'webinar'
			
			
			):
			$layoutBreadcrumb = '';
			$viewBreadcrumb = $this->getView($view);
			$Breadcrumb = '';
			if($layout)
				$layoutBreadcrumb = $this->getView($layout);
				
				$slug = JRequest::getVar('slug');
				$cid = JRequest::getVar('cid');


							$metaPosition = 2;
							if($viewBreadcrumb):
								if($layoutBreadcrumb):								
									if($slug)
										$cidLink = '&cid=' . $cid .':'. JFilterOutput::stringURLSafe(  $cname );
									echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view='.$view . $cidLink) .'"><span itemprop="name">'.$viewBreadcrumb->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>';
									$Breadcrumb .= ' | ' . $viewBreadcrumb->title_sef;
									$parent_sef = $layoutBreadcrumb->parent_sef;
									if($parent_sef > 0):
										$breadcrumbs = array();
										$i = 3;
										while($parent_sef > 0) {
											$i--;
											$cidLink = '';
											$vidLink = '';
											$uidLink = '';
											$layoutBreadcrumb2 = $this->getLayout(null,$parent_sef);
											/*
											$this->_db	= JFactory::getDBO();
											$query = $this->_db->getQuery(true);
											$query->select($this->_db->quoteName('name_sef'));
											$query->select($this->_db->quoteName('title_sef'));
											$query->select($this->_db->quoteName('parent_sef'));
											$query->from($this->_db->quoteName('#__sef'));
											$query->where($this->_db->quoteName('id_sef') . ' = ' . $this->_db->quote($parent_sef));
											$query->where($this->_db->quoteName('id_sef_type') . ' = 2 ');
											$query->where($this->_db->quoteName('status_sef') . ' = 1 ');
											$this->_db->setQuery($query);
											$layoutBreadcrumb2 = $this->_db->loadObject();
											*/
											if(count($breadcrumbs)>0):
												if($cid && $i>0)
													$cidLink = '&cid=' . $cid .':'. JFilterOutput::stringURLSafe(  $cname );	
												if($vid && $i>1)
													$vidLink = '&vid=' . $vid .':'. JFilterOutput::stringURLSafe(  $vname );
												if($uid && $i>2)
													$uidLink = '&uid=' . $uid .':'. JFilterOutput::stringURLSafe(  $uname );
																						
												array_unshift($breadcrumbs, '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view='.$view. '&layout=' . $layoutBreadcrumb2->name_sef . $cidLink . $vidLink . $uidLink ).'"><span itemprop="name">'.$layoutBreadcrumb2->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>');
												$Breadcrumb .= ' | ' . $layoutBreadcrumb2->title_sef;
											else:
												if($cid && $i>0)
													$cidLink = '&cid=' . $cid .':'. JFilterOutput::stringURLSafe(  $cname );	
												if($vid && $i>1)
													$vidLink = '&vid=' . $vid .':'. JFilterOutput::stringURLSafe(  $vname );
												if($uid && $i>2)
													$uidLink = '&uid=' . $uid .':'. JFilterOutput::stringURLSafe(  $uname );
													
												$breadcrumbs[] = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view='.$view. '&layout=' . $layoutBreadcrumb2->name_sef . $cidLink . $vidLink . $uidLink ).'"><span itemprop="name">'.$layoutBreadcrumb2->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>';
												$Breadcrumb .= ' | ' . $layoutBreadcrumb2->title_sef;
											endif;

											$parent_sef = $layoutBreadcrumb2->parent_sef;
										}
										foreach($breadcrumbs as $breadcrumb)
											echo $breadcrumb;
											
										if($valorid):
											echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view='.$view. '&layout=' . $layout).'"><span itemprop="name">'.$layoutBreadcrumb->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>';
										else:
											$titlePage = $layoutBreadcrumb->title_sef;
											$iconPage = $layoutBreadcrumb->icon_sef;
											echo '<li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="item"><span itemprop="name">'.$layoutBreadcrumb->title_sef.'</span></span><meta itemprop="position" content="'.$metaPosition++.'"></li>';
											$Breadcrumb .= ' | ' . $layoutBreadcrumb->title_sef;
										endif;
									else:
										$titlePage = $layoutBreadcrumb->title_sef;
										$iconPage = $layoutBreadcrumb->icon_sef;
										echo '<li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">'.$layoutBreadcrumb->title_sef.'</span></span><meta itemprop="position" content="'.$metaPosition++.'"></li>';
										$Breadcrumb .= ' | ' . $layoutBreadcrumb->title_sef;
									endif;	
								else:
									$parent_sef = $viewBreadcrumb->parent_sef;
									if($parent_sef > 0):
										$breadcrumbs = array();
										while($parent_sef > 0) {
											$i--;
											$cidLink = '';
											$vidLink = '';
											$uidLink = '';
											$viewBreadcrumb2 = $this->getView(null,$parent_sef);
											/*
											$this->_db	= JFactory::getDBO();
											$query = $this->_db->getQuery(true);
											$query->select($this->_db->quoteName('name_sef'));
											$query->select($this->_db->quoteName('title_sef'));
											$query->select($this->_db->quoteName('parent_sef'));
											$query->from($this->_db->quoteName('#__sef'));
											$query->where($this->_db->quoteName('id_sef') . ' = ' . $this->_db->quote($parent_sef));
											$query->where($this->_db->quoteName('id_sef_type') . ' = 1 ');
											$query->where($this->_db->quoteName('status_sef') . ' = 1 ');
											$this->_db->setQuery($query);
											$viewBreadcrumb2 = $this->_db->loadObject();
											*/
											if(count($breadcrumbs)>0):
												if($cid && $i>0)
													$cidLink = '&cid=' . $cid .':'. JFilterOutput::stringURLSafe(  $cname );	
												if($vid && $i>1)
													$vidLink = '&vid=' . $vid .':'. JFilterOutput::stringURLSafe(  $vname );
												if($uid && $i>2)
													$uidLink = '&uid=' . $uid .':'. JFilterOutput::stringURLSafe(  $uname );
																						
												array_unshift($breadcrumbs, '<li 1 itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view='. $viewBreadcrumb2->name_sef . $cidLink . $vidLink . $uidLink ).'"><span itemprop="name">'.$viewBreadcrumb2->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>');
												$Breadcrumb .= ' | ' . $viewBreadcrumb2->title_sef;
											else:
												if($cid && $i>0)
													$cidLink = '&cid=' . $cid .':'. JFilterOutput::stringURLSafe(  $cname );	
												if($vid && $i>1)
													$vidLink = '&vid=' . $vid .':'. JFilterOutput::stringURLSafe(  $vname );
												if($uid && $i>2)
													$uidLink = '&uid=' . $uid .':'. JFilterOutput::stringURLSafe(  $uname );
													
												$breadcrumbs[] = '<li 2 itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.JRoute::_('index.php?view=' . $viewBreadcrumb2->name_sef . $cidLink . $vidLink . $uidLink ).'"><span itemprop="name">'.$viewBreadcrumb2->title_sef.'</span></a><meta itemprop="position" content="'.$metaPosition++.'"></li>';
												$Breadcrumb .= ' | ' . $viewBreadcrumb2->title_sef;
											endif;
	
											$parent_sef = $viewBreadcrumb2->parent_sef;
										}
										foreach($breadcrumbs as $breadcrumb)
											echo $breadcrumb;

										if($slug):
											$Breadcrumb .= '<li><a href="'.JRoute::_('index.php?view='.$view. '&layout=' . $layout).'" class="pathway">'.$viewBreadcrumb->title_sef.'</a></li>';
										else:
											$titlePage = $viewBreadcrumb->title_sef;
											$iconPage = $viewBreadcrumb->icon_sef;
											echo '<li 4 class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="item"><span itemprop="name">'.$viewBreadcrumb->title_sef.'</span></span><meta itemprop="position" content="'.$metaPosition++.'"></li>';
											$Breadcrumb .= ' | ' . $viewBreadcrumb->title_sef;						
										endif;	
									else:
									
										if($slug || $cid):
											$iconPage = $viewBreadcrumb->icon_sef;
											$Breadcrumb .= '<li><a href="'.JRoute::_('index.php?view='.$view).'" class="pathway">'.$viewBreadcrumb->title_sef.'</a></li>';
											if($cid)
												$slug = $cid;
											$titlePage = $this->getTitle($slug, $view);
											$Breadcrumb .= '<li class="active">'.$titlePage.'</span></li>';
										else:
											$titlePage = $viewBreadcrumb->title_sef;
											$iconPage = $viewBreadcrumb->icon_sef;
											$Breadcrumb .= '<li class="active">'.$viewBreadcrumb->title_sef.'</span></li>';
										endif;
									endif;
								endif;
							endif;
							//$BreadcrumbURL .=  str_replace($this->base,'',JURI::current());


			$buffer .= "\n<section id=\"sp-page-title\">";
			$buffer .= "\n<div class=\"row\">";
			$buffer .= "\n<div id=\"sp-title\" class=\"col-sm-12 col-md-12\">";
			$buffer .= "\n<div class=\"sp-column\">";
			$buffer .= "\n<div class=\"sp-page-title sp-page-breadcrumb\">";
			$buffer .= "\n<div class=\"container\">";
			
			
			$buffer .= "\n<h2>" . $titlePage . "</h2>";
			$buffer .= "\n<ol class=\"breadcrumb\">";
			$buffer .= "\n<span>Você está aqui:&nbsp;</span>";
			$buffer .= "\n<li><a href=\"/\" class=\"pathway\">Home</a></li>";
			$buffer .= "\n" . $Breadcrumb;
			$buffer .= "\n</ol>";
			$buffer .= "\n</div>";
			$buffer .= "\n</div>";
			$buffer .= "\n</div>";
			$buffer .= "\n</div>";
			$buffer .= "\n</div>";
			$buffer .= "\n</section>";

			$this->title = 'FBT | ' . $titlePage;
		endif;
			//$document = JFactory::getDocument();			
			//$document->setTitle('FBT | ' . $this->_teste);	
			//$this->_doc->setTitle('FBT | ' . $titlePage);
			//$document->setBreadcrumb($Breadcrumb);
			//$document->setBreadcrumbURL($BreadcrumbURL);	
		return $buffer;
	}
	
	function getView($view = null, $parent_sef = null)
	{

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName('title_sef'));
		$query->select($this->_db->quoteName('icon_sef'));
		$query->select($this->_db->quoteName('parent_sef'));
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
		$query->select($this->_db->quoteName('title_sef'));
		$query->select($this->_db->quoteName('icon_sef'));
		$query->select($this->_db->quoteName('parent_sef'));
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
