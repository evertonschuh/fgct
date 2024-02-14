<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Captcha
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.environment.browser');

/**
 * Recaptcha Plugin.
 * Based on the official recaptcha library( https://developers.google.com/recaptcha/docs/php )
 *
 * @package     Joomla.Plugin
 * @subpackage  Captcha
 * @since       2.5
 */
class plgCaptchaRecaptcha extends JPlugin
{
	const RECAPTCHA_API_SERVER = "http://www.google.com/recaptcha/api";
	const RECAPTCHA_API_SECURE_SERVER = "https://www.google.com/recaptcha/api";
	const RECAPTCHA_VERIFY_SERVER = "www.google.com";

	public function __construct($subject, $config)
	{
		
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Initialise the captcha
	 *
	 * @param	string	$id	The id of the field.
	 *
	 * @return	Boolean	True on success, false otherwise
	 *
	 * @since  2.5
	 */
/*	public function onInit($id = null)
	{
		$app = JFactory::getApplication();	
		// Initialise variables
		$lang		= $this->_getLanguage();
		$pubkey		= $this->params->get('public_key', $app->getCfg('public_key')); //'6LdsMv4SAAAAADuPo1utMukc_XU-l9rr6AFPCmWB');
		//echo $config->public_key;
		$theme		= $this->params->get('theme', 'clean');


		if ($pubkey == null || $pubkey == '')
		{
			throw new Exception(JText::_('PLG_RECAPTCHA_ERROR_NO_PUBLIC_KEY'));
		}

		$server = self::RECAPTCHA_API_SERVER;
		if (JBrowser::getInstance()->isSSLConnection())
		{
			$server = self::RECAPTCHA_API_SECURE_SERVER;
		}


		$server = self::RECAPTCHA_API_SECURE_SERVER;
		
		JHtml::_('script', $server.'/js/recaptcha_ajax.js');
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('$(document).ready(function(){
			Recaptcha.create("'.$pubkey.'", "dynamic_recaptcha_1", {theme: "'.$theme.'",'.$lang.'tabindex: 0});
		});'
		);



		return true;
	}

*/
	protected $autoloadLanguage = true;

	/**
	 * Initialise the captcha
	 *
	 * @param   string  $id  The id of the field.
	 *
	 * @return  Boolean	True on success, false otherwise
	 *
	 * @since  2.5
	 */
	public function onInit($id = null)
	{
		$document = JFactory::getDocument();
		$app      = JFactory::getApplication();

	//	JHtml::_('jquery.framework');

		//$lang   = $this->_getLanguage();
		//$app = JFactory::getApplication();	
		// Initialise variables
		$lang		= $this->_getLanguage();
		$pubkey		= $this->params->get('public_key', $app->getCfg('public_key')); //'6LdsMv4SAAAAADuPo1utMukc_XU-l9rr6AFPCmWB');
	

		if ($pubkey == null || $pubkey == '')
		{
			throw new Exception(JText::_('PLG_NOCAPTCHARECAPTCHA_ERROR_NO_PUBLIC_KEY'));
		}

		return true;
	}






	/**
	 * Gets the challenge HTML
	 *
	 * @return  string  The HTML to be embedded in the form.
	 *
	 * @since  2.5
	 */
	/*
	public function onDisplay($name = null, $id = null, $class = null)
	{
		return '<div id="dynamic_recaptcha_1"></div>';
	}
*/
	public function onDisplay($name = null, $id = 'dynamic_recaptcha_1', $class = '')
	{
		$app = JFactory::getApplication();	
		//return '<div id="' . $id . '" ' . $class . '></div>';
		return '<script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha" data-sitekey="'.$app->getCfg('public_key').'" ></div>';
	}


/*

	public function onDisplay($name, $id = 'dynamic_recaptcha_1', $class = '')
	{
		//return '<div id="' . $id . '" ' . $class . '></div>';
		return '<script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha" data-sitekey="'.$this->params->get('site_key', '').'"></div>';
	}
*/
	/**
	 * Calls an HTTP POST function to verify if the user's guess was correct
	 *
	 * @param   string  $code  Answer provided by user.
	 *
	 * @return  True if the answer is correct, false otherwise
	 *
	 * @since  2.5
	 */
	public function onCheckAnswer($code)
	{
		//$input      = JFactory::getApplication()->input;
		//$privatekey = $this->params->get('secret_key');
		//$remoteip   = $input->server->get('REMOTE_ADDR', '', 'string');
		//$response   = $input->get('g-recaptcha-response', '', 'string');
		
		
		$app = JFactory::getApplication();	

		$privatekey	= $this->params->get('private_key', $app->getCfg('private_key') ); //'6LdsMv4SAAAAADH4DJhKeATK05DnKuvKQG4zfxm9'
		$remoteip	= JRequest::getVar('REMOTE_ADDR', '', 'SERVER');
		//$challenge	= JRequest::getString('recaptcha_challenge_field', '');
		$response	= JRequest::getString('g-recaptcha-response', '');
		

		// Check for secret Key
		if (empty($privatekey))
		{
			$this->_subject->setError(JText::_('PLG_NOCAPTCHARECAPTCHA_ERROR_NO_PRIVATE_KEY'));

			return false;
		}

		// Check for IP
		if (empty($remoteip))
		{
			$this->_subject->setError(JText::_('PLG_NOCAPTCHARECAPTCHA_ERROR_NO_IP'));

			return false;
		}

		// Discard spam submissions
		if ( $response == null || strlen($response) == 0)
		{
			$this->_subject->setError(JText::_('PLG_NOCAPTCHARECAPTCHA_ERROR_EMPTY_SOLUTION'));

			return false;
		}

		$url = "https://www.google.com/recaptcha/api/siteverify?secret=".$privatekey."&response=".$response."&remoteip=".$remoteip;
		
		$status = false;
		$res = '';
		
		if (ini_get('allow_url_fopen')) { 
			
				$response = file_get_contents($url);
				
				$res = json_decode($response, true);
				if($res['success']){
					$status = true;
				} 
				
					
			
		} else { 
		
			if(is_callable('curl_exec')){
				
				$ch = curl_init();
				$timeout = 0; // set to zero for no timeout
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				$response = curl_exec($ch);
								
				curl_close($ch);
			
				$res = json_decode($response, true);
				if($res['success'])
					$status = true;
			} 
		} 
	

		if ($status)
			{
				return true;
		}
		else
		{
			// @todo use exceptions here
			$this->_subject->setError(JText::_('PLG_NOCAPTCHARECAPTCHA_ERROR_' . strtoupper(str_replace('-', '_', $res['error-codes']))));
		
		
			return false;
		}
	}

	/**
	 * Encodes the given data into a query string format.
	 *
	 * @param   array  $data  Array of string elements to be encoded
	 *
	 * @return  string  Encoded request
	 *
	 * @since  2.5
	 */
	private function _nocaptcharecaptcha_qsencode($data)
	{
		$req = "";

		foreach ($data as $key => $value)
		{
			$req .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}

		// Cut the last '&'
		$req = rtrim($req, '&');

		return $req;
	}

	/**
	 * Submits an HTTP POST to a reCAPTCHA server.
	 *
	 * @param   string  $host  Host name to POST to.
	 * @param   string  $path  Path on host to POST to.
	 * @param   array   $data  Data to be POSTed.
	 * @param   int     $port  Optional port number on host.
	 *
	 * @return  array   Response
	 *
	 * @since  2.5
	 */
	private function _nocaptcharecaptcha_http_post($host, $path, $data, $port = 80)
	{
		$req = $this->_nocaptcharecaptcha_qsencode($data);

		$http_request  = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
		$http_request .= "Content-Length: " . strlen($req) . "\r\n";
		$http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
		$http_request .= "\r\n";
		$http_request .= $req;

			$url = $host.$path.$req;
		$response = '';

		if (ini_get('allow_url_fopen')) { 
			
				$response = file_get_contents($url);
				$ok = true;
			
		} 
		
		if (!$ok) { 
			$this->_debug_log("URI couldn't be opened probably ALLOW_URL_FOPEN off");
			if (function_exists('curl_init')) {
				$this->_debug_log("curl_init does exists");
				$ch = curl_init();
				$timeout = 5; // set to zero for no timeout
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				$response = curl_exec($ch);
				curl_close($ch);
			} else
				$this->_debug_log("curl_init doesn't exists");
		}
		/*
		if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false )
		{
			die('Could not open socket');
		}

		fwrite($fs, $http_request);

		while (!feof($fs))
		{
			// One TCP-IP packet
			$response .= fgets($fs, 1160);
		}

		fclose($fs);
		$response = explode("\r\n\r\n", $response, 2);
*/
		return $response;
	}

	/**
	 * Get the language tag or a custom translation
	 *
	 * @return  string
	 *
	 * @since  2.5
	 */
	private function _getLanguage()
	{
		$language = JFactory::getLanguage();

		$tag = explode('-', $language->getTag());
		$tag = $tag[0];
		$available = array('en', 'pt', 'fr', 'de', 'nl', 'ru', 'es', 'tr');

		if (in_array($tag, $available))
		{
			return "lang : '" . $tag . "',";
		}

		// If the default language is not available, let's search for a custom translation
		if ($language->hasKey('PLG_RECAPTCHA_CUSTOM_LANG'))
		{
			$custom[] = 'custom_translations : {';
			$custom[] = "\t" . 'instructions_visual : "' . JText::_('PLG_RECAPTCHA_INSTRUCTIONS_VISUAL') . '",';
			$custom[] = "\t" . 'instructions_audio : "' . JText::_('PLG_RECAPTCHA_INSTRUCTIONS_AUDIO') . '",';
			$custom[] = "\t" . 'play_again : "' . JText::_('PLG_RECAPTCHA_PLAY_AGAIN') . '",';
			$custom[] = "\t" . 'cant_hear_this : "' . JText::_('PLG_RECAPTCHA_CANT_HEAR_THIS') . '",';
			$custom[] = "\t" . 'visual_challenge : "' . JText::_('PLG_RECAPTCHA_VISUAL_CHALLENGE') . '",';
			$custom[] = "\t" . 'audio_challenge : "' . JText::_('PLG_RECAPTCHA_AUDIO_CHALLENGE') . '",';
			$custom[] = "\t" . 'refresh_btn : "' . JText::_('PLG_RECAPTCHA_REFRESH_BTN') . '",';
			$custom[] = "\t" . 'help_btn : "' . JText::_('PLG_RECAPTCHA_HELP_BTN') . '",';
			$custom[] = "\t" . 'incorrect_try_again : "' . JText::_('PLG_RECAPTCHA_INCORRECT_TRY_AGAIN') . '",';
			$custom[] = '},';
			$custom[] = "lang : '" . $tag . "',";

			return implode("\n", $custom);
		}

		// If nothing helps fall back to english
		return '';
	}
}
