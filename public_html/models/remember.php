<?php

/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');


class EASistemasModelRemember extends JModel
{

	var $_db = null;
	var $_data = null;
	var $_app = null;

	function __construct()
	{
		parent::__construct();
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
	}

	function testUserPF($id_user)
	{
		$query = $this->_db->getQuery(true);
		$query->select('*');
		$query->from('#__pf');
		$query->where($this->_db->quoteName('user_id') . ' = ' . $this->_db->quote($id_user));
		//$query->where($this->_db->quoteName('types_id') . ' = 1');
		$this->_db->setQuery($query);
		return (bool) $this->_db->loadObject();
	}

	function setData()
	{

		$email_remember	= JRequest::getVar('email_remember', '', 'post');
		$type_doc	= JRequest::getVar('type_doc', '', 'post');
		$cpf_pf	= JRequest::getVar('cpf_pf', '', 'post');
		$cnpj_pj	= JRequest::getVar('cnpj_pj', '', 'post');

		$this->_app->setUserState('__users', $email_remember);
		$this->_app->setUserState('email_remember', $email_remember);
		$this->_app->setUserState('type_doc', $type_doc);
		$this->_app->setUserState('cpf_pf', $cpf_pf);
		$this->_app->setUserState('cnpj_pj', $cnpj_pj);
	}

	function getMailResult()
	{
		return $this->_app->getUserStateFromRequest('__users');
	}

	function getMail()
	{


		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('username', 'block')));
		$query->from($this->_db->quoteName('#__users'));
		// ??? $query->innerJoin($this->_db->quoteName('#__user_usertypes_map') . ' ON (' . $this->_db->quoteName('#__user_usertypes_map.user_id') .'='. $this->_db->quoteName('#__users.id') . ')');

		if ($this->_data->type_doc == 1) {
			$query->innerJoin($this->_db->quoteName('#__user_usertypes_map') . ' ON (' . $this->_db->quoteName('#__user_usertypes_map.user_id') . '=' . $this->_db->quoteName('#__users.id') . ')');
			$query->innerJoin($this->_db->quoteName('#__pj') . ' ON (' . $this->_db->quoteName('#__user_usertypes_map.pf_pj_id') . '=' . $this->_db->quoteName('#__pj.id_pj') . ')');
			$query->where($this->_db->quoteName('#__user_usertypes_map.types_id') . ' = ' . $this->_db->quote('2'));
			$query->where($this->_db->quoteName('#__pj.cnpj_pj') . ' = ' . $this->_db->quote($this->_data->cnpj_pj));
		} else {
			$query->innerJoin($this->_db->quoteName('#__pf') . ' ON (' . $this->_db->quoteName('#__pf.user_id') . '=' . $this->_db->quoteName('#__users.id') . ')');
			//$query->where($this->_db->quoteName('#__pf.types_id') . ' = ' . $this->_db->quote('1')); 
			$query->where($this->_db->quoteName('#__pf.cpf_pf') . ' = ' . $this->_db->quote($this->_data->cpf_pf));
		}
		$this->_db->setQuery($query);
		if ((bool) $results = $this->_db->loadObjectList()) {

			$__users = array();
			foreach ($results as $result) {
				$object = new stdClass();
				$object->username 	= substr($result->username, 0, 3) . '... ...' . strstr($result->username, '@');
				$__users[] = $object;
			}
			$this->_app->setUserState('__users', $__users);

			return true;
		} else
			return false;
	}

	function testCaptcha()
	{
		$post = JRequest::get('post');
		JPluginHelper::importPlugin('captcha');
		$dispatcher = JDispatcher::getInstance();

		//$res = $dispatcher->trigger('onCheckAnswer',$post['recaptcha_response_field']);

		$res = $dispatcher->trigger('onCheckAnswer', $post['g-recaptcha-response']);

		if (!$res[0])
			return false;
		else
			return true;
	}

	function rememberUser()
	{
		$post = JRequest::get('post');

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('username', 'block')));
		$query->from($this->_db->quoteName('#__users'));
		$query->where($this->_db->quoteName('username') . ' = ' . $this->_db->quote($post['username']));
		$this->_db->setQuery($query);
		if ((bool) $result = $this->_db->loadObject())
			return $result;
		else
			return false;
	}

	function activatorMail()
	{

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('username', 'activation', 'email', 'name')));
		$query->from($this->_db->quoteName('#__users'));
		$query->where($this->_db->quoteName('username') . ' = ' . $this->_db->quote($this->_data->email_remember));
		$query->where($this->_db->quoteName('block') . ' = ' . $this->_db->quote('1'));
		$query->where($this->_db->quoteName('activation') . '<>' . $this->_db->quote(''));
		$query->where($this->_db->quoteName('params') . ' = ' . $this->_db->quote('{"activate":1}'));
		
		$this->_db->setQuery($query);
		if (!(bool) $user_result = $this->_db->loadObject())
			return false;

		$mailer = JFactory::getMailer();

		$userId = $user_result->activation;

		//$user = JUser::getInstance($userId);
		
		//$token = JApplication::getHash(JUserHelper::genRandomPassword());
		//$salt = JUserHelper::getSalt('crypt-md5');
		//$hashedToken = md5($token . $salt) . ':' . $salt;

		//$activation = $hashedToken;

		// Save the user to the database.
		//if (!$user->save(true)) {

		//	return new JException(JText::sprintf('COM_USERS_USER_SAVE_FAILED', $user->getError()), 500);
		//}
		$uri = JURI::getInstance();
		$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
		$linkActivate = $base . JRoute::_('index.php?view=registration&task=activate&token=' . $user_result->activation, false);
		$linkActivate = str_replace('/admin','', $linkActivate);
		
		//Email Manual		
		$recipient = $user_result->email;
		//$recipient = 'everton-schuh@hotmail.com';
		$subject = JText::sprintf(
			'OEMPREGO_MODEL_REMENBER_SENDMAIL_ACTIVATION_DETAILS',
			$user_result->name
		);

		$emailBody = JText::sprintf(
			'OEMPREGO_MODEL_REMENBER_SENDMAIL_ACTIVATION_BODY',
			$user_result->name,
			$linkActivate,
			$linkActivate,
			$user_result->username
		);

		$fromname	= $this->_app->getCfg('fromname');
		$mailfrom	= $this->_app->getCfg('mailfrom');

		$mailer = JFactory::getMailer();

		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';

		$mailer->setSender(array($mailfrom, $fromname));
		$mailer->addRecipient($recipient);
		$mailer->setSubject($subject);
		$mailer->setBody($emailBody);
		$send = $mailer->Send();


		return true;


	}

	function getInfoUser($username = null)
	{

		if(empty($username))
			return false;

		$query	= $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('username', 'id', 'email', 'name', 'token_remember')));
		$query->from($this->_db->quoteName('#__users'));
		$query->where($this->_db->quoteName('username') . ' = ' . $this->_db->quote($username));
		$this->_db->setQuery($query);

		if(!(boolean) $userLoad = $this->_db->loadObject())
			return false;

		return $userLoad;
	}

	function sendMail()
	{

		$post = JRequest::get('post');

		if( !(boolean) $userLoad = $this->getInfoUser($post['username']))
			return false;
		
		$user = JUser::getInstance($userLoad->id);

		// Set the confirmation token.
		$token = JApplication::getHash(JUserHelper::genRandomPassword());
		$salt = JUserHelper::getSalt('crypt-md5');
		$hashedToken = md5($token . $salt) . ':' . $salt;

		$user->activation = $hashedToken;

		// Save the user to the database.
		if (!$user->save(true)) {
			return new JException(JText::sprintf('COM_USERS_USER_SAVE_FAILED', $user->getError()), 500);
		}

		$options['id_mailmessage_occurrence']='1';
		$automaticMessages = $this->getAutomaticMessage($options);	

		if(!count($automaticMessages)>0 )
			return false;

		$uri = JURI::getInstance();
		$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));




		$data = array();
		$data['NOME_CLIENTE'] = $userLoad->name;                                                 
		$data['CODIGO_ATIVACAO'] = $hashedToken;
		$data['LINK_ATIVACAO'] =  $base . JRoute::_('index.php?view=remember&layout=confirm&c='.$hashedToken.'&u='.$userLoad->username, false);

		if(count($automaticMessages)>0):
			foreach($automaticMessages as $automaticMessage):
				$mailer = JFactory::getMailer(); 
	
				$mailer->isHTML(true);
				$mailer->Encoding = 'base64';

				if(!empty($automaticMessage->replyto_mailmessage))
					$mailer->addReplyTo($automaticMessage->replyto_mailmessage);

				$emailBody = $automaticMessage->theme_mailmessage_theme;
					
				preg_match_all('/<img[^>]+>/i',$emailBody, $result); 
				$result = $result[0];
				if(count($result)>0) 
				{

					$imgs = array();
					foreach( $result as $img_tag)
					{
						preg_match_all('/(src)=("[^"]*")/i',$img_tag, $imgs[$img_tag]);
					}
					
					if( count($imgs)>0 )
					{
						$i = 0;
						foreach( $imgs as $key => $img)
						{
						$imgUrl = str_replace('"', '', $img[2][0]);
							
						$mailer->addEmbeddedImage( JPATH_BASE .DS. str_replace('/', DS, $imgUrl) , 'image'.$i, 'image'.$i.'.jpg', 'base64', 'image/jpg' );                   
						$newkey = str_replace($imgUrl, 'cid:image'.$i, $key);
						$emailBody =  str_replace($key, $newkey, $emailBody);
						}  
					}  
					
				}
				
				$bodyMessage = $automaticMessage->mensagem_mailmessage;

				if (preg_match_all("/{{SE}}(.*?){{\/SE}}/", $bodyMessage, $m)) {
					foreach ($m[1] as $i => $varname) {
						if (preg_match_all("/{{(.*?)}}/", $varname, $g)) {
							foreach ($g[1] as $y => $varname1) {
								$SeText ='';
								if(!empty($data[$varname1]))
									$SeText = str_replace($g[0][$y], sprintf($data[$varname1], $varname1), $varname);

								$bodyMessage = str_replace($m[0][$i], $SeText, $bodyMessage);
							}
						}
					}
				}
				
				if (preg_match_all("/{{(.*?)}}/", $bodyMessage, $m)) {
					foreach ($m[1] as $i => $varname) {
						$bodyMessage = str_replace($m[0][$i], sprintf($data[$varname], $varname), $bodyMessage);
					}
				}
				
				$emailBody = str_replace('{{MENSAGEM}}', $bodyMessage, $emailBody);

				$mailer->setBody($emailBody);
				
				$subject = $automaticMessage->subject_mailmessage;
				$mailer->setSubject($subject);
				
				$recipient = $userLoad->email;
				$mailer->addRecipient($recipient);
				
				if($automaticMessage->account_mailmessage && $automaticMessage->password_mailmessage): 
					$mailer->useSMTP('1', 'smtp.gmail.com', $automaticMessage->account_mailmessage, $automaticMessage->password_mailmessage, 'ssl', '465');
					$mailer->setSender( array( $automaticMessage->account_mailmessage, $automaticMessage->name_account_mailmessage) );
				else:
					$fromname	= $this->_app->getCfg('fromname');
					$mailfrom	= $this->_app->getCfg('mailfrom');
					$mailer->setSender( array( $mailfrom, $fromname ) );
				endif;	


				//$Bcc[] = 'everton-schuh@hotmail.com';		
				//$mailer->addBcc($Bcc);

				$send = $mailer->Send();
			endforeach;
		endif;	



		
		if ($send !== true)
			return false;
		else
			return true;




/*
		$name = $user->name;
		$username = $user->username;
		$fromname	= $this->_app->getCfg('fromname');
		$mailfrom	= $this->_app->getCfg('mailfrom');
		$sitename	= $this->_app->getCfg('sitename');
		$siteurl	= JUri::root();

		$uri = JURI::getInstance();

		$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));

		$activate =  $hashedToken;

		$link =   $base . JRoute::_('index.php?view=remember&layout=confirm', false);

		$recipient = $userLoad->email;
		$subject = 'Pedido de redefinição de senha para ' . $username;

		$emailBody = JText::sprintf(
			'OEMPREGO_MODEL_REMEMBER_SENDMAIL_BODY',
			$name,
			$siteurl,
			$sitename,
			$activate,
			$link,
			$link,
			$sitename,
			$siteurl
		);


		$mailer->isHTML(true);

		$mailer->setSender(array($mailfrom, $fromname));
		$mailer->addRecipient($recipient);
		$mailer->setSubject($subject);
		$mailer->setBody($emailBody);
		$send = $mailer->Send();
		if ($send !== true)
			return false;
		else
			return true;*/
	}


	

	function confirmCod()
	{

		$post = JRequest::get('post');

		if( !(boolean) $userLoad = $this->getInfoUser($post['username']))
			return false;
		$token_remember	= JRequest::getVar('token_remember', '', 'post');


		//
		//if( empty($this->_data->token_remember) )


		if (empty($token_remember))
			$token_remember = $this->_data->token_remember;
		else
			$this->_app->setUserState('token_remember', NULL);

		$query = $this->_db->getQuery(true);
		$query->select($this->_db->quoteName(array('username', 'block')));
		$query->from($this->_db->quoteName('#__users'));
		$query->where($this->_db->quoteName('username') . ' = ' . $this->_db->quote($this->_data->email_remember));
		$query->where($this->_db->quoteName('activation') . ' = ' . $this->_db->quote($token_remember));
		$this->_db->setQuery($query);
		if ((bool) $this->_db->loadObject()) {
			$this->_app->setUserState('token_remember', $token_remember);
			return true;
		} else
			return false;
	}

	function resetPassword()
	{
		if (empty($this->_data))
			$this->getData();

		$password	= JRequest::getVar('password', '', 'post');
		$password2	= JRequest::getVar('password2', '', 'post');

		$mailer = JFactory::getMailer();

		$query	= $this->_db->getQuery(true);
		$query->select('id');
		$query->from($this->_db->quoteName('#__users'));
		$query->where($this->_db->quoteName('username') . ' = ' . $this->_db->quote($this->_data->email_remember));

		$this->_db->setQuery((string) $query);
		$userId = $this->_db->loadResult();

		$this->_app->setUserState('email_remember', NULL);
		$this->_app->setUserState('token_remember', NULL);

		if ($userId) {
			$user = JUser::getInstance($userId);

			$password = JUserHelper::hashPassword($password);

			// Update the user object.
			$user->password			= $password;
			$user->activation		= '';
			$user->password_clear	= $password2;

			// Save the user to the database.
			if (!$user->save(true)) {
				return false;
			}


			return true;
		} else
			return false;



		//$this->setPass
	}



	function getAutomaticMessage( $options = array() )
	{	
	
		$query = $this->_db->getQuery(true);	
		$query->select('*');	
		$query->from($this->_db->quoteName('#__intranet_mailmessage'));
		$query->leftJoin($this->_db->quoteName('#__intranet_mailmessage_theme') . ' USING ('.$this->_db->quoteName('id_mailmessage_theme').')');
		$query->leftJoin($this->_db->quoteName('#__intranet_mailmessage_occurrence') . ' USING ('.$this->_db->quoteName('id_mailmessage_occurrence').')');
		$query->where( $this->_db->quoteName('id_mailmessage_occurrence') . '=' . $this->_db->quote( $options['id_mailmessage_occurrence'] ) );

		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}


}
