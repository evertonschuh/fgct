<?php

/**
 * @package		Joomla.Site
 * @subpackage	Application
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Joomla! Application define.
 */

//Global definitions.
//Joomla framework path definitions.
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);

//Defines.
define('JPATH_ROOT',            implode(DIRECTORY_SEPARATOR, $parts));
define('JPATH_ROOT_ABSOLUT',	dirname(dirname(dirname(dirname(__FILE__)))));
define('JPATH_CDN',             JPATH_ROOT_ABSOLUT . '/fgct_intranet/public_html/media');
define('JPATH_SITE',            JPATH_ROOT);
define('JPATH_CONFIGURATION',   JPATH_ROOT);
define('JPATH_ADMINISTRATOR',   JPATH_ROOT);
define('JPATH_LIBRARIES',       JPATH_ROOT . DS .'libraries');
define('JPATH_PLUGINS',         JPATH_ROOT . DS .'plugins');
define('JPATH_INSTALLATION',    JPATH_ROOT . DS .'installation');
define('JPATH_THEMES',          JPATH_BASE . DS .'views');
define('JPATH_THEMES_NATIVE',   JPATH_BASE . DS .'views/system');
define('JPATH_CACHE',           JPATH_BASE . DS .'cache');
//define('JPATH_MANIFESTS',       JPATH_ADMINISTRATOR . DS .'manifests');
define('JPATH_COMPONENT',       JPATH_BASE);
define('JPATH_IMAGES',          JPATH_BASE . DS .'images');
define('JPATH_MEDIA',           JPATH_BASE . DS .'midias');
define('JPATH_MODULE',          JPATH_BASE . DS .'modules');
define('JPATH_MODULE_TMPL',     JPATH_BASE . DS .'modules');