<?php
/**
 * @version 1.0
 * @package Joomla
 * @subpackage SWFContent (Module)
 * @author Milton Pfenninger <info@webconstruction.ch>
 * @copyright Copyright (C) 2009 Milton Pfenninger. All rights reserved.
 * @license GNU/GPL
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

class JFormFieldPreview extends JFormField
{
	protected $type = 'preview';

	protected function getInput()
	{
		$html = '<div>
		<a class="btn btn-success" href="../index.php?siteentrypreview=true" target="_blank">Pré-visualizar (Salve Primeiro)</a>
		</div>';
		return $html;
	}
}
