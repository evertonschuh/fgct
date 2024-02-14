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
define('JPATH_ADMIN',           JPATH_ROOT_ABSOLUT .DS. 'fgct_admin' .DS. 'public_html');
define('JPATH_INTRANET',        JPATH_ROOT_ABSOLUT .DS. 'fgct_intranet' .DS. 'public_html');

define('JPATH_SYSTEM',	        JPATH_INTRANET .DS. 'admin' .DS. 'classes');
define('JPATH_CDN',             JPATH_INTRANET .DS. 'media');

define('JPATH_LIBRARIES',       JPATH_ADMIN .DS. 'libraries');
define('JPATH_PLUGINS',         JPATH_ADMIN .DS. 'plugins');
define('JPATH_INCLUDES',        JPATH_ADMIN .DS. 'includes');
define('JPATH_DYNAMIC',         JPATH_ADMIN .DS. 'dynamic');
define('JPATH_MODULE',          JPATH_ADMIN .DS. 'modules');
define('JPATH_MODULE_TMPL',     JPATH_ADMIN .DS. 'modules');

define('JPATH_ADMINISTRATOR',   JPATH_ADMIN);


define('JPATH_SITE',            JPATH_ROOT);
define('JPATH_CONFIGURATION',   JPATH_ROOT);
define('JPATH_INSTALLATION',    JPATH_ROOT .DS. 'installation');

define('JPATH_COMPONENT',       JPATH_BASE);
define('JPATH_THEMES',          JPATH_BASE .DS. 'views');
define('JPATH_THEMES_NATIVE',   JPATH_BASE .DS. 'views/system');
define('JPATH_CACHE',           JPATH_BASE .DS. 'cache');
//define('JPATH_MANIFESTS',       JPATH_ADMINISTRATOR . DS .'manifests');
define('JPATH_IMAGES',          JPATH_BASE .DS. 'images');
define('JPATH_MEDIA',           JPATH_BASE .DS. 'midias');