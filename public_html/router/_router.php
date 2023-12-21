<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_banners
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @param	array	A named array
 * @return	array
 */
	
	
function EASistemasBuildRoute(&$query)
{
	$app = JFactory::getApplication();
	$sef = $app->getSef();	
	$segments = array();

	if (isset($query['view'])) {

		$encontred = false;
		if($sef > 0):
			foreach($sef as $item ):
				if (isset($query[$item->name_sef_type]) && $query[$item->name_sef_type] == $item->name_sef ):
					$segments[] = $item->alias_sef;
					$viewPage = $item->alias_sef;
					$encontred = true;
					break;
				endif;
			endforeach;
		endif;
		if(!$encontred){
			$segments[] = $query['view'];
			$viewPage = $query['view'];
		}		
		unset($query['view']);
	}
	
	if (isset($query['layout'])) {

		//$app = JFactory::getApplication();
		//$sef = $app->getSef();
		$encontred = false;
		if($sef > 0):
			foreach($sef as $item ) :
				if (isset($query[$item->name_sef_type]) && $query[$item->name_sef_type] == $item->name_sef ):
				$segments[] = $item->alias_sef;
				$encontred = true;
					break;
				endif;
			endforeach;
		endif;
		
		if(!$encontred){
			$segments[] = $query['layout'];
		}		
		unset($query['layout']);
	}

	
	if (isset($query['cid'])) {
		$segments[] = $query['cid'];
		unset($query['cid']);
	}
	

	if (isset($query['vid'])) {
		$segments[] = $query['vid'];
		unset($query['vid']);
	}
	

	if (isset($query['uid'])) {
		$segments[] = $query['uid'];
		unset($query['uid']);
	}
		
	if (isset($query['start'])) {
		//$app = JFactory::getApplication();
		
		 //'global.list.limit', JRequest::getVar('limit'), $app->getCfg('list_limit')
		$limit		= $app->getUserStateFromRequest( 
		'global.list.limit', JRequest::getVar('limit'), $app->getCfg('list_limit'), $app->getCfg('list_limit'), 'int' );		
		$segments[] = 'pag='.(($query['start'] / $limit	) +1);
		unset($query['start']);
	}
	if (isset($query['limitstart'])) {
		unset($query['limitstart']);
	}
	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/banners/task/id/Itemid
 *
 * index.php?/banners/id/Itemid
 */
function EASistemasParseRoute($segments)
{

	$app = JFactory::getApplication();
	$sef = $app->getSef();
	// view is always the first element of the array
	$count = count($segments);	
	
	$vars = array();
	$vars['limitstart'] = 0;
	
	if ($count)
	{
		$count--;
		$segment = array_shift($segments);
		$encontred = false;
		if($sef){
			foreach($sef as $item ) {	
				if ($item->name_sef_type == 'view' ) {
					if ( str_replace(':','-',$segment) === $item->alias_sef){
						$vars[$item->name_sef_type] = $item->name_sef;
						$encontred = true;
						break;
					}
				}
			}	
		}
		if(!$encontred){
			$vars['view'] = $segment;
		}	
	}
	
	if ($count)
	{
		$count--;
		$segment = array_shift($segments);
		if (is_numeric($segment)) {
			$vars['cid'] = $segment;
		}
		elseif (is_numeric(strstr($segment, ':', true))) {
			$cid = explode(':', $segment);
			$vars['cid'] = (int) $cid[0];
		}
		elseif('pag' == strstr($segment, '=', true)) {
			$start = explode('=', $segment);
			$limit		= $app->getUserStateFromRequest( 
			'com_oemprego.' . $vars['view'] . '.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );	
            if (!isset($start[1]) || !is_numeric($start[1]))
                $start[1]=1;
			$vars['limitstart'] = (int) (($start[1] - 1) * $limit);
		}
		elseif(is_numeric( strstr(base64_decode( base64_decode( strrev( '=' . $segment ) ) ), '-', true) ) ) {
			$count--;
			$vars['cid'] = $segment;
		}
		else
		{
			$encontred = false;
			if($sef){
				foreach($sef as $item ) {	
					if ( str_replace(':','-',$segment) === $item->alias_sef && $item->name_sef_type == 'layout' ){
						$vars[$item->name_sef_type] = $item->name_sef;
						$encontred = true;
						break;
					}
				}		
			}
			if(!$encontred){
				$vars['layout'] = $segment;
			}
		} 
	}

	if ($count)
	{
		
		$count--;
		$segment = array_shift($segments);
		
		if('pag' == strstr($segment, '=', true)) {
			$start = explode('=', $segment);
			$limit		= $app->getUserStateFromRequest( 
			'com_oemprego.' . $vars['view'] . '.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );	
            if (!isset($start[1]) || !is_numeric($start[1]))
                $start[1]=1;
			$vars['limitstart'] = (int) (($start[1] - 1) * $limit);
		}		
		elseif (is_numeric($segment) && empty($vars['cid']) ) {
			$vars['cid'] = $segment;
		}
		elseif (is_numeric(strstr($segment, ':', true)) && empty($vars['cid'])) {
			$cid = explode(':', $segment);
			$vars['cid'] = (int) $cid[0];
		}
		elseif(is_numeric($segment)) {
			$vid = explode(':', $segment);
			$vars['vid'] = (int) $vid[0];	
		}
		elseif (is_numeric(strstr($segment, ':', true))) {
			$cid = explode(':', $segment);
			$vars['vid'] = (int) $cid[0];
		}	
	}
		
	if ($count)
	{
		$segment = array_shift($segments);
		$count--;
		
		if('pag' == strstr($segment, '=', true)) {
			$start = explode('=', $segment);
			$limit		= $app->getUserStateFromRequest( 
			'com_oemprego.' . $vars['view'] . '.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );	
            if (!isset($start[1]) || !is_numeric($start[1]))
                $start[1]=1;
			$vars['limitstart'] = (int) (($start[1] - 1) * $limit);
		}		
		elseif (is_numeric($segment) && empty($vars['vid']) ) {
			$vars['vid'] = $segment;
		}
		elseif (is_numeric(strstr($segment, ':', true)) && empty($vars['vid'])) {
			$cid = explode(':', $segment);
			$vars['vid'] = (int) $cid[0];
		}
		elseif (is_numeric($segment)) {
			$vars['uid'] = $segment;
		}
		elseif (is_numeric(strstr($segment, ':', true))) {
			$cid = explode(':', $segment);
			$vars['uid'] = (int) $cid[0];
		}
	}
	
	if ($count)
	{
		$segment = array_shift($segments);
		$count--;
		
		if('pag' == strstr($segment, '=', true)) {
			$start = explode('=', $segment);
			$limit		= $app->getUserStateFromRequest( 
			'com_oemprego.' . $vars['view'] . '.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );	
            if (!isset($start[1]) || !is_numeric($start[1]))
                $start[1]=1;
			$vars['limitstart'] = (int) (($start[1] - 1) * $limit);
		}		
		elseif (is_numeric($segment)) {
			$vars['uid'] = $segment;
		}
		elseif (is_numeric(strstr($segment, ':', true))) {
			$cid = explode(':', $segment);
			$vars['uid'] = (int) $cid[0];
		}
	}
	return $vars;
}