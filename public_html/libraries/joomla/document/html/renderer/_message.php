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
			$document->addScript( 'views/system/assets/js/bootstrap-show-toast.js' );
			$notify = '';
			foreach ($lists as $type => $msgs) {
				if (count($msgs)) {
					foreach ($msgs as $msg) {
						$notify .= 'bootstrap.showToast({
										header: "",
										headerSmall: "",
										body: "asdas", 
										closeButton: true, 
										closeButtonLabel: "close",
										closeButtonClass: "", 
										toastClass: "",
										animation: true,
										delay: 5000,
										position: "top-0 end-0", 
										direction: "append",
										ariaLive: "assertive",
									});';
					}
					$document->addscriptdeclaration('jQuery(document).ready('.$notify.')');
				}
			}
			//print_r($notify);
			//exit;

		}
		
		return $buffer;
	}
}
