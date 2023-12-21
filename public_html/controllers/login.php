<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class EASistemasControllerLogin extends JController
{
    public function display($cachable = false, $urlparams = array())
    {
        $user    = JFactory::getUser();
        if ($user->get('guest'))
            parent::display();
        else {
            $this->setRedirect(JRoute::_('index.php?view=dashboard' . $view, false));
            return;
        }
    }

    function logoff()
    {

        JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

        $app = JFactory::getApplication();
        // Perform the log in.
        $error = $app->logout();

        // Check if the log out succeeded.
        if (!($error instanceof Exception)) {
            // Get the return url from the request and validate that it is internal.
            $return = JRequest::getVar('return', '', 'method', 'base64');
            $return = base64_decode($return);
            if (!JURI::isInternal($return)) {
                $return = '';
            }

            // Redirect the user.
            $this->setRedirect(JRoute::_('index.php', false), $msg, 'danger');
        } else {
            $this->setRedirect(JRoute::_('index.php', false), $msg, 'danger');
        }
    }

    function logofff()
    {
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));



        $userid = JRequest::getInt('uid', null);
        $session_id = JRequest::getVar('session_id', null);
        //$this->setRedirect(JRoute::_('index.php', false), $userid, 'danger');

        if ($userid && $session_id) :
            $app = JFactory::getApplication();
            $options = array(
                'clientid' => ($userid) ? 0 : 1,
                'session_id' => ($session_id)
            );

            $result = $app->logout($userid, $options);

            if (!($result instanceof Exception)) {

                $msg = 'A sessão ativa foi encerrada com sucesso!.';
                //$model 	= $this->getModel('login');
                //$return = $model->getState('return');
                $app->redirect($return, $msg, 'success');
            }


        //$this->setRedirect(JRoute::_('index.php?view=login', false), $msg, 'danger');



        endif;
    }

    function login()
    {

        JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

        $model = $this->getModel('login');

        $urlReturn = JRequest::getVar('return', '', 'GET', 'base64');

        
        //if ( empty( $urlReturn ) )
        //{
        //	$urlReturn = JRequest::getVar('return', '', 'POST', 'base64');
        //}
        $msg = '';
        if ($model->getLogin()) {
            //if( $this->testParceiro() )
            //	{
            if (!empty($urlReturn)) {
                $urlReturn = base64_decode($urlReturn);
                $this->setRedirect(JRoute::_($urlReturn, false), $msg, $tipo);
            } else {
                $this->setRedirect(JRoute::_('index.php?view=dashboard', false), $msg, $tipo);
            }
            /*	}
			else
			{
				$this->logoff();
				if ( !empty( $urlReturn ) )
				{
					$link = '&return=' . $urlReturn;
				}
				$this->setRedirect(JRoute::_('index.php?view=login' . $link, false), JText::_('Você foi desconectado pois não é um parceiro cadastrado para acessar esta área.'), 'danger');
			}*/
        } else {
            $link = '';
            if (!empty($urlReturn)) {
                $link = '&return=' . $urlReturn;
            }
            echo $msg  . 'ere';
            //exit;
            $this->setRedirect(JRoute::_('index.php?view=login' . $link, false), $msg, 'danger');
        }
    }
}