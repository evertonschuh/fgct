<?php

$mainframe = startJoomla();

/*

$cache = JFactory::getCache('');
$result = $cache->gc();

if ($result) {
    echo 'Expired cache purged.';
} else {
    echo 'Failed to purge expired cache.';
}*/

function startJoomla()
{
	
    define('_JEXEC', true);
    define( 'DS', DIRECTORY_SEPARATOR );


    //define('JPATH_BASE', dirname(dirname(dirname(dirname(dirname(__FILE__))))) );
	
    // load joomla libraries
    require_once dirname(dirname(__FILE__)) . DS . 'defines.php';
	require_once JPATH_INCLUDES . DS . 'framework.php';
    require_once JPATH_LIBRARIES . DS . 'loader.php';
		
    jimport('joomla.base.object');
    jimport('joomla.factory');
    jimport('joomla.filter.filterinput');
    jimport('joomla.error.error');
    jimport('joomla.event.dispatcher');
    jimport('joomla.event.plugin');
    jimport('joomla.plugin.helper');
    jimport('joomla.utilities.arrayhelper');
    jimport('joomla.environment.uri');
    jimport('joomla.environment.request');
    jimport('joomla.user.user');
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.file');
	
    // JText cannot be loaded with jimport since it's not in a file called text.php but in methods
    JLoader::register('JText', JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'methods.php');
    JLoader::register('JRoute', JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'methods.php');
	
    $mainframe = JFactory::getApplication('site');
	
    $GLOBALS['mainframe'] = $mainframe;

    return $mainframe;
}

?>
