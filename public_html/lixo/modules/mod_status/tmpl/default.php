<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	mod_status
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$hideLinks	= JRequest::getBool('hidemainmenu');
$output = array();
$output[] = '<div class="pull-right status-bar-right-button col-xs-3 col-sm-2 col-md-2 col-lg-2">
				<form method="post" id="form-login">
					<input type="hidden" name="task" id="task" value="logout" />
					<input type="hidden" name="controller" id="controller" value="login" />			
					<input type="hidden" name="view" id="view" value="login" />'. JHTML::_('form.token') . '	
					<button type="submit" class="btn btn-success">' . JText::_('MOD_STATUS_LOGOUT'). '</button>
				</form>
			</div>';

// Print the back-end logged in users.
if ($params->get('show_loggedin_users_admin', 1)) :
	//$output[] = '<span class="backloggedin-users">'.JText::plural('MOD_STATUS_BACKEND_USERS', $count).'</span>';
	$output[] = '<div class="pull-right status-bar-item status-log-users col-xs-5 col-sm-5 col-md-5 col-lg-5"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;'.JText::plural('MOD_STATUS_BACKEND_USERS', $count).'</div>';
endif;

// Print the logged in users.
if ($params->get('show_loggedin_users', 1)) :
	//$output[] = '<span class="loggedin-users">'.JText::plural('MOD_STATUS_USERS', $online_num).'</span>';
	//$output[] = '<span class="loggedin-users">'.JText::plural('MOD_STATUS_USERS', $online_num).'</span>';
	$output[] = '<div class="pull-right status-bar-item col-xs-4 col-sm-5 col-md-5 col-lg-5"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;'.JText::plural('MOD_STATUS_USERS', $online_num).'</div>';
endif;




/*
//  Print the inbox message.
if ($params->get('show_messages', 1)) :
	$output[] = '<span class="'.$inboxClass.'">'.
			($hideLinks ? '' : '<a href="'.$inboxLink.'">').
			JText::plural('MOD_STATUS_MESSAGES', $unread).
			($hideLinks ? '' : '</a>').
			'</span>';
endif;
*/
// Output the items.
?>
<div class="pull-right status-bar-right status-bar col-xs-12 col-sm-12 col-md-5 col-lg-5">	
<?php
foreach ($output as $item) :
	echo $item;
endforeach;
?>
</div>