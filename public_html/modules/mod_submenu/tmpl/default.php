<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	mod_submenu
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$hide = JRequest::getInt('hidemainmenu');
?>
<div class="col-lg-12">
    <div class="row page-toolbar-admin">
        <div class="col-lg-12">
            <ul class="nav nav-tabs nav-justified" >
                <?php 
                foreach ($list as $item) : 
                    if ($hide) :
                        if (isset ($item[2]) && $item[2] == 1) :
                            ?><li class="nolink active" ><span><?php echo $item[0]; ?></span></li><?php
                        else :
                            ?><li class="nolink"><span class="nolink"><?php echo $item[0]; ?></span></li><?php
                        endif;
                    else :
                        if(strlen($item[1])) :
                            if (isset ($item[2]) && $item[2] == 1) :
                                ?><li  role="presentation" class="active" ><a><?php echo $item[0]; ?></a></li><?php
                            else :
                                ?><li><a href="<?php echo JFilterOutput::ampReplace($item[1]); ?>"><?php echo $item[0]; ?></a></li><?php
                            endif;
                        else :
                            ?><?php echo $item[0]; ?><?php
                        endif;
                    endif;
                endforeach;
                ?>
            </ul>
		</div>
	</div>
</div>