<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link rel="shortcut icon" href="views/system/img/favicon.ico" >
        <jdoc:include type="head" />
        <link href="views/system/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="views/system/css/sb-admin-2.min.css" rel="stylesheet">    
        <link href="views/system/css/custom.css" rel="stylesheet">        
        <link href="views/system/css/metisMenu.min.css" rel="stylesheet">   
        <link href="views/system/css/sweetalert.css" rel="stylesheet">   
    </head>
    
	<body id="page-top" >
        <div class="wrapper">
            <div id="content-wrapper" class="d-flex flex-column">
                <div class="col-12">
                    <br/>
            	    <jdoc:include type="message" />
                    <jdoc:include type="component" />
                </div>
            </div>
            
        </div>
    </body>
    <script src="views/system/js/jquery.min.js"></script>
    <script src="views/system/js/bootstrap.bundle.min.js"></script>
    <script src="views/system/js/jquery.easing.min.js"></script>
    <script src="views/system/js/sb-admin-2.min.js"></script>
	<script src="views/system/js/metisMenu.min.js"></script>
    <script src="views/system/js/main.js"></script>
    <script src="views/system/js/sweetalert.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('a, button, input, label, span').tooltip({html: true});			
        });
    </script>
    <jdoc:include type="footer" />
</html>

