<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of tracks.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @since		1.6
 */
class IntranetViewFinPagamentos extends JViewLegacy
{
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		
		$contents		= $this->get('Cnab');
		$basename 		= $this->get('CnabNameFile');
		$filetype		= 'rem';
		$mimetype		= 'application/text; charset=utf-8';

		$fp = fopen('php://output', 'w');
		
		// Escreve $conteudo no nosso arquivo aberto.
		if (fwrite($fp, "$contents") === FALSE) 
			erro("Não foi possível escrever no arquivo ($filename)");
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$document = JFactory::getDocument();
		$document->setMimeEncoding($mimetype);
		JResponse::setHeader('Content-disposition', 'attachment; filename="'.$basename.'.'.$filetype.'"; creation-date="'.JFactory::getDate()->toRFC822().'"', true);

	}
	

	
}
