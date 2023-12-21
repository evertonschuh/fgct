<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Renders a standard button
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
class JButtonModal extends JButton
{
	/**
	 * Button type
	 *
	 * @var    string
	 */
	protected $_name = 'Modal';

	/**
	 * Fetch the HTML for the button
	 *
	 * @param   string   $type  Unused string.
	 * @param   string   $name  The name of the button icon class.
	 * @param   string   $text  Button text.
	 * @param   string   $task  Task associated with the button.
	 * @param   boolean  $list  True to allow lists
	 *
	 * @return  string  HTML string for the button
	 *
	 * @since   11.1
	 */
	public function fetchButton($type = 'Modal', $icon = '', $task = '', $text = '', $list = false, $classBtn='')
	{
		
		

		$i18n_text = JText::_($text);
		$class = $this->fetchIconClass($icon);

		//$doTask = $this->_getCommand($text, $task, $list);
		$checkSelect = "";
		if($list):
			$message = JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');
			$message = addslashes($message);

			$html = "<a href=\"javascript:void(0);\" onclick=\"if (document.adminForm.boxchecked.value==0){alert('$message');} else{ $('#$task').modal('show');}\" class=\"btn btn-primary shadow btn-circle btn-lg\" title=\"$i18n_text\">\n";
		else:
			$html = "<a href=\"#$task\"$checkSelect data-toggle=\"modal\" data-target=\"#$task\" class=\"btn btn-$classBtn shadow btn-circle btn-lg\" title=\"$i18n_text\">\n";
		endif;
		
		
		

		$html .= "<i class=\"fas $class\"></i>\n";
		//$html .= "<span>$i18n_text</span>\n";
		//$html .= "<span class=\"label label-default\">2</span>\n";
		$html .= "</a>\n";
		
		
		
		
		
		//$html = "<a href=\"javascript:void(0);\" onclick=\"$doTask\" class=\"btn btn-primary shadow btn-circle btn-lg\" title=\"$i18n_text\">\n";
		//$html .= "<i class=\"fas $class\"></i>\n";
		//$html .= "</a>\n";
			
		
		
		
		
		
		
		
		
		
		return $html;
	}

	/**
	 * Get the button CSS Id
	 *
	 * @param   string   $type      Unused string.
	 * @param   string   $name      Name to be used as apart of the id
	 * @param   string   $text      Button text
	 * @param   string   $task      The task associated with the button
	 * @param   boolean  $list      True to allow use of lists
	 * @param   boolean  $hideMenu  True to hide the menu on click
	 *
	 * @return  string  Button CSS Id
	 *
	 * @since   11.1
	 */
	public function fetchId($type = 'Modal', $name = '', $text = '', $task = '', $list = true, $hideMenu = false)
	{
		return $this->_parent->getName() . '-' . $name;
	}
	
	protected function _getCommand($name, $task, $list)
	{
		JHtml::_('behavior.framework');
		$message = JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');
		$message = addslashes($message);

		if ($list)
		{
			$cmd = "if (document.adminForm.boxchecked.value==0){alert('$message');}else{ Joomla.submitbutton('$task')}";
		}
		else
		{
			$cmd = "Joomla.submitbutton('$task')";
		}

		return $cmd;
	}
	
}
