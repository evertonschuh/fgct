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
 * JDocument system message renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererMessage extends JDocumentRenderer
{
	/**
	 * Renders the error stack and returns the results as a string
	 *
	 * @param   string  $name     Not used.
	 * @param   array   $params   Associative array of values
	 * @param   string  $content  Not used.
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 */
	public function render($name, $params = array (), $content = null)
	{
		// Initialise variables.
		$buffer = null;
		$lists = null;

		// Get the message queue
		$messages = JFactory::getApplication()->getMessageQueue();

		// Build the sorted message list
		if (is_array($messages) && !empty($messages))
		{
			foreach ($messages as $msg)
			{
				if (isset($msg['type']) && isset($msg['message']))
				{
					$lists[$msg['type']][] = $msg['message'];
				}
			}
		}

		// If messages exist render them
		if (is_array($lists))
		{

			$document 	=  JFactory::getDocument();
			$document->addScript( '/assets/vendor/libs/toast-notification/toast-notification.js' );

			$buffer .= "\n<div class=\"toast-notification active\">";
				$buffer .= "\n<div class=\"toast-notification-content\">";
				foreach ($lists as $type => $msgs)
				{
					if (count($msgs))
					{
						switch(strtolower($type)){
							case 'danger': $icon = 'bx-x'; $title = 'Erro'; break;
							case 'success': $icon = 'bx-check'; $title = 'Sucesso'; break;
							case 'warning': $icon = 'bx-check'; $title = 'Atenção'; break;
							case 'primary': $icon = 'bx-check'; $title = 'Aviso'; break;
							case 'info': $icon = 'bx-check'; $title = 'Dica'; break;
							case 'dafault': $icon = 'bx-check'; $title = 'Aviso'; break;

						}

					$buffer .= "\n<i class=\"bx " . $icon . " icon " . strtolower($type) . "\"></i>";
					$buffer .= "\n<div class=\"message\">";
						$buffer .= "\n<span class=\"text text-1\">" . $title . "</span>";
						foreach ($msgs as $msg)
						{
							$buffer .= "\n<span class=\"texst text-2\">" . $msg . "</span>";
						}
					$buffer .= "\n</div>";
					}
				}					
				$buffer .= "\n</div>";
				$buffer .= "\n<i class=\"bx bx-x close\"></i>";
				$buffer .= "\n<div class=\"progress " . strtolower($type) . " active\"></div>";
			$buffer .= "\n</div>";

		}

		//$buffer .= "\n</div>";
		return $buffer;
	}
}
