<?php
defined('_JEXEC') or die('Restricted access'); 

$config   = JFactory::getConfig();
$siteOffset = $config->getValue('offset');
?>

<div class="lock"></div>
<div class="message">
  <h1>Access to this page is restricted</h1>
  <p>CDN EASistemas.</p>
</div>
