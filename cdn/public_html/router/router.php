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

	$segments = array();
	if (isset($query['view'])) {
		$segments[] = $query['view'];	
		unset($query['view']);
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

	$count = count($segments);

	while($count>0){
		$count--;
		$vars['query_rest'][] = array_shift($segments);
		
	}

	return $vars;
		
}