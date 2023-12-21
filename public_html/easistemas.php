<?php
/**
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license    GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the base controller

require_once( JPATH_COMPONENT.DS.'controller.php' );
$nomecontroller = JRequest::getCmd('view');

$user	= JFactory::getUser();
if ( $user->get('guest') && !empty($nomecontroller) ) {
	$nomecontroller = 'login';
	JRequest::setVar( 'view', $nomecontroller );	
}

$path = JPATH_COMPONENT.DS.'controllers'.DS.$nomecontroller.'.php';
if (file_exists($path)) {
	require_once $path;
} else {
	$nomecontroller = '';
}

			
// Create the controller
$classname	= 'EASistemasController'.$nomecontroller;
$controller	= new $classname();

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );

// Redirect if set by the controller
$controller->redirect();

?>