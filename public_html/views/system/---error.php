<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$config = new JConfig();
$this->sitename = $config->sitename;
$this->file_logo = $config->file_logo;
$this->MetaRights  = $config->MetaRights;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
   <link rel="apple-touch-icon" href="views/system/images/apple-touch-icon.png"/>
   <link rel="shortcut icon" href="views/system/images/favicon.ico" >
   
   
  <title>ERROR</title>
  <meta name="viewport" content="initial-scale=1"/>
  <meta http-equiv="Cache-control" content="max-age=86400, public">
  <meta name="author" content="Ineje - ineje.com.br" />
  <meta name="robots" content="index, follow" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="keywords" content="cursos, greduação, EAD, escola, tributária, societária, financeira" />
  <meta name="rights" content="© 2016 ineje.com.br.  Todos os direitos reservados." />
  <meta name="description" content="O INEJE - Instituto Nacional de Estudos Jurídicos e Empresariais trabalha no desenvolvimento de estudos de alto nível, nas áreas financeira, societária e tributária" />
  <meta name="generator" content="Ineje | Library - Joomla! | By Everton Alexandre Schuh" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="content-language" content="pt-br" />
  <meta name="breadcrumb" content="Ineje" />
  <meta name="breadcrumbURL" content="/|" />
  <meta name="canonical" content="http://www.ineje.eas.br/" />
  <link rel="canonical" href="http://www.ineje.eas.br/" />
  <link rel="stylesheet" href="/views/system/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="/views/system/css/style.css" type="text/css" />
  <script src="/views/system/js/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script src="/views/system/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="/views/system/js/core.js" type="text/javascript"></script>
   
   
   
   
   
   
   
   
   
   
   
   
   
   <jdoc:include type="head" />
    <script type="text/javascript">
        $(document).ready(function(){
            $('a, button, input, label, span').tooltip({html: true});			
        });
    </script>
</head>

<body>
	<header>
    	<div class="top-links">
        	<div class="container">
                <div class="logo pull-left">
                  	<a class="navbar-brand" href="<?php echo JURI::base( true ); ?>">
						<?php //echo $this->sitename; ?>
                        <div class="logosite">
                        	<h1 class="spt-logo h-logo"><?php echo $this->title; ?></h1>
                        	<img src="<?php echo 'views/system/images/' . $this->file_logo; ?>" alt="<?php echo $this->title; ?>" />
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-default" role="navigation">
        	<div class="container">
                <div class="navbar-header">
                  	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    	<span class="sr-only">Toggle navigation</span>
                    	<span class="icon-bar"></span>
                    	<span class="icon-bar"></span>
                    	<span class="icon-bar"></span>
                  	</button>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="<?php echo JRoute::_('index.php'); ?>"><span class="glyphicon glyphicon-home hidden-xs"></span><span class="visible-xs"><?php echo JText::_('INEJE_MENU_HOME'); ?></span></a></li>
                        <li><a href="<?php echo JRoute::_('index.php?view=institucional'); ?>"><?php echo JText::_('INEJE_MENU_INSTITUCIONAL'); ?></a></li>
                        <li><a href="<?php echo JRoute::_('index.php?view=pj'); ?>"><?php echo JText::_('INEJE_MENU_PJ'); ?></a></li>
                        <li><a href="<?php echo JRoute::_('index.php?view=pf'); ?>"><?php echo JText::_('INEJE_MENU_PF'); ?></a></li>
                        <li><a href="http://www.fbtedu.com.br/loja/" target="_blank"><?php echo JText::_('INEJE_MENU_LOJA'); ?></a></li>
                        <li><a href="http://www.fbtedu.com.br/" target="_blank"><?php echo JText::_('INEJE_MENU_FBT'); ?></a></li>
                    	<?php
							$uri = JFactory::getURI();
							$user = JFactory::getUser();
							 
							if ( !$user->get('guest') )
							{
	
								$_db	= JFactory::getDBO();
								$query = $_db->getQuery(true);
								$query->select($_db->quoteName('types_id'));
								$query->from($_db->quoteName('#__user_usertypes_map'));
								$query->where($_db->quoteName('user_id') . ' = ' . $_db->quote($user->id));
								$_db->setQuery($query);
								$result = $_db->loadResultArray();
								
								$query = $_db->getQuery(true);
								$query->select($_db->quoteName('name'));
								$query->from($_db->quoteName('#__users'));
								$query->where($_db->quoteName('id') . ' = ' . $_db->quote($user->id));
								$_db->setQuery($query);
									
								$NameUser = $_db->loadObject();
								$NameUser = explode(" ", $NameUser->name);
								if ( count($NameUser)>1 ) 
									$Nome = $NameUser[0] . ' ' . end($NameUser);
								else
									$Nome =  end($NameUser);
	
						?>                      
                        <li class="dropdown login-bar">
                      		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-lock left hidden-xs"></span><?php echo $Nome; ?>&nbsp;<span class="caret"></span></a>
                            <div class="dropdown-menu">
                        		<form class="navbar-form navbar-left" role="search" method="post" >
                                    <div class="form-group text-center">
                                    	<?php echo JText::_('INEJE_GLOBAL_MENU_LOGON') ; ?>
                                    </div> 
                                    <div class="form-group dropdow-item">
                                    	<a href="<?php echo JRoute::_('index.php?view=panel'); ?>"><span class="glyphicon glyphicon-dashboard"></span>&nbsp;<?php echo JText::_('INEJE_GLOBAL_MENU_PANEL');  ?></a>
                                    </div>
                                    <div class="divider"></div>
                                    <input type="hidden" name="task" id="task" value="logoff" />
                                    <input type="hidden" name="controller" id="controller" value="login" />			
									<input type="hidden" name="view" id="view" value="login" />
                                    <input type="hidden" name="return" value="<?php echo base64_encode( JRoute::_($uri->toString(), false) ); ?>" />
									<?php echo JHTML::_('form.token'); ?>	
                                    <button type="submit" class="btn btn-ineje btn-block"><span class="glyphicon glyphicon-log-out"></span>&nbsp;<?php echo JText::_('INEJE_GLOBAL_BUTTON_EXIT') ?></button>      
                                </form>
                      		</div>
                    	</li>
						<?php
							}
							else
							{
						?>
                        <li class="login-bar"><a href="#login-modal" data-toggle="modal" data-target="#login-modal"> <span class="glyphicon glyphicon-log-in left hidden-xs"></span><?php echo JText::_('INEJE_MENU_LOGIN'); ?></a></li>
                        <?php
							}
                        ?>
                  	</ul>
                </div>
          	</div>
        </nav>
    </header>
    <section>
    	<div class="container" >
        	<div class="row">
                <div class="form-group col-xs-12 col-md-8 col-md-offset-2">
                    <jdoc:include type="message" />
                </div>
            </div>
            <jdoc:include type="component" />
        </div>
    </section>
    <footer>
        <nav>
            <ul class="nav nav-pills nav-justified">
                <li>
                    <div class="container bottom">
                        <div class="social copy">
                   
                	<p><span class="glyphicon glyphicon-map-marker"></span> &nbsp; Rua Mostardeiro, 88 - Independência - Porto Alegre - RS - Brasil</p>
                	<p><span class="glyphicon glyphicon-phone"></span> +55 (51) 3073-1001 &nbsp;|&nbsp; <span class="glyphicon glyphicon-envelope"></span> contato@ineje.com.br</p> 
                        </div>
                        <p class="copy">
                        <?php 
                        if($this->MetaRights)
                            echo $this->MetaRights;
                        else
                            echo JText::_('INEJE_GLOBAL_COPYRIGHT');
                        ?>
                        </p>
                    </div>
                </li>
            </ul>
        </nav>
    </footer>
</body>
</html>











<?php
if ( $user->get('guest') && $view != 'login' )
{
?>                      
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content login-modal">
            <div class="modal-header login-modal-header modal-header-ineje">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center"><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;<?php echo JText::_('INEJE_GLOBAL_MODALLOGIN_TITLE'); ?></h4>
            </div>
            <div class="modal-body login-modal-body">
                <div class="text-center"> 
			        <form role="form" method="post" name="buscarCep" id="buscarCep" class="form-validate">
                        <div class="form-group">
                        	<label for="usrname" class="label-modal-login text-left"><?php echo JText::_('INEJE_GLOBAL_EMAIL_LOGIN'); ?></label>
                            <div class="input-group">
                                <div class="input-group-addon">@</div>
                                <input type="email" class="form-control input-lg" id="username" name="username" required placeholder="<?php echo JText::_('INEJE_GLOBAL_EMAIL_LOGIN_PLACE'); ?>" />
                            </div>
                            <span class="help-block has-error" id="email-error"></span>
                        </div>
                        <div class="form-group">
                       		<label for="password" class="label-modal-login text-left"><?php echo JText::_('INEJE_GLOBAL_SENHA'); ?></label>
                            <div class="input-group">
                                <div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
                                <input type="password" autocomplete="off" class="form-control input-lg" id="password" name="password" required pattern="(?=.*[a-zA-Z0-9]).{6,}" placeholder="<?php echo JText::_('INEJE_GLOBAL_SENHA_PLACE'); ?>" />
                            </div>
                            <span class="help-block has-error" id="password-error"></span>
                        </div>
                        <div class="form-group modal-button">
                        		<button type="submit" id="login_btn" class="btn btn-block btn-ineje btn-lg" data-loading-text="Signing In...."><span class="glyphicon glyphicon-off"></span>&nbsp;<?php echo JText::_('INEJE_GLOBAL_BUTTON_ACCESS'); ?></button>
                        </div>
                        <input type="hidden" name="task" id="task" value="login" />
                        <input type="hidden" name="controller" id="controller" value="login" />			
                        <input type="hidden" name="view" id="view" value="login" />
                        <input type="hidden" name="return" value="<?php echo base64_encode( JRoute::_($uri->toString(), false) ); ?>" />
                        <?php echo JHTML::_('form.token'); ?>	
                        
                    </form>      
                    <div class="clearfix"></div>
                    <div class="login-modal-footer">
                        <div class="row">
                            <div class="col-xs-7 col-md-7">
                                <a href="<?php echo JRoute::_('index.php?view=remember'); ?>" class="forgetpass-tab pull-left"><?php echo JText::_('INEJE_GLOBAL_REMEMBER'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
}

/*
?>
















<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/views/system/css/error.css" type="text/css" />
	<?php if ($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/views/system/css/error_rtl.css" type="text/css" />
	<?php endif; ?>
</head>
<body>
	<div class="error">
		<div id="outline">
		<div id="errorboxoutline">
			<div id="errorboxheader"><?php echo $this->error->getCode(); ?> - <?php echo $this->error->getMessage(); ?></div>
			<div id="errorboxbody">
			<p><strong><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></strong></p>
				<ol>
					<li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
					<li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
				</ol>
			<p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>

				<ul>
					<li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></li>
				</ul>

			<p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?>.</p>
			<div id="techinfo">
			<p><?php echo $this->error->getMessage(); ?></p>
			<p>
				<?php if ($this->debug) :
					echo $this->renderBacktrace();
				endif; ?>
			</p>
			</div>
			</div>
		</div>
		</div>
	</div>
</body>
</html>

*/
?>