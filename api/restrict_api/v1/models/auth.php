<?php

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );

class EASistemasModelAuth extends JModel
{

	var $_db = null;
	var $_app = null;	
	var $_total = null;	
	var $_data = null;

	function __construct()
	{		
		$this->_db	= JFactory::getDBO();
		$this->_app = JFactory::getApplication();
		//$this->_db = JFactory::getDboClient();
		parent::__construct();
	}

	public function getAuth()
	{	

		$password = JRequest::getVar('password', '', 'POST');
		$email = JRequest::getVar('email', '', 'POST');

		if($this->_db){
			
			$query = $this->_db->getQuery(true);
			$query->select($this->_db->quoteName(array('id','name','email', 'password', 'username', 'activation')));
			$query->select($this->_db->quoteName('image_pf') . ' as avatar');
			$query->from($this->_db->quoteName('#__users'));
			$query->innerJoin($this->_db->quoteName('#__intranet_pf') . 'ON(' . $this->_db->quoteName('id_user') . '='. $this->_db->quoteName('id') . ')');
			$query->where( $this->_db->quoteName('username') . '=' . $this->_db->quote( $email ) );

			$query->where( $this->_db->quoteName('block') . '=' . $this->_db->quote( '0' ) );
			$this->_db->setQuery($query);
			if( !(boolean) $result = $this->_db->loadObject())
				return false;

						
			if(empty($result->password))
				return false;

			$match = JUserHelper::verifyPassword($password, $result->password, $result->id);

				
			if($match !== true){
				return false;
			}
			else{
				unset($result->password);
			}
			
			return $result;

		}

		return false;
	}

	public function resetPassword(){

		$activation = JRequest::getVar('activation', '', 'POST');
		$password = JRequest::getVar('password', '', 'POST');
		$query_rest = JRequest::getVar('query_rest', array());

		if(isset($query_rest[2])) {
			if($this->_db){
				$new_password = $this->createhHashPassword($password);
				$query	= $this->_db->getQuery(true);
				$query->set($this->_db->quoteName('password_corredor') . '=' . $this->_db->quote($new_password));
				$query->set($this->_db->quoteName('activation_corredor') . '=' . $this->_db->quote(''));

				$query->update($this->_db->quoteName('#__corredor'));
				$query->where($this->_db->quoteName('id_corredor') . '=' . $this->_db->quote($query_rest[2]));
				$query->where($this->_db->quoteName('activation_corredor') . '=' . $this->_db->quote($activation));
				$this->_db->setQuery($query);
				if (!$this->_db->query()) 
					return false;

				return $query_rest[2];
			}
		}
	}

	public static function createhHashPassword($password)
	{
		require_once(JPATH_LIBRARIES . DS . 'phpass' . DS . 'PasswordHash.php');
		$phpass = new PasswordHash(10, true);
		return $phpass->HashPassword($password);
		
	}

	public static function verifyPassword($password, $hash)
	{
		$rehash = false;
		$match = false;

		if (strpos($hash, '$P$') === 0) {

			require_once(JPATH_LIBRARIES . DS . 'phpass' . DS . 'PasswordHash.php');
			// Use PHPass's portable hashes with a cost of 10.
			$phpass = new PasswordHash(10, true);

			$match = $phpass->CheckPassword($password, $hash);

			$rehash = false;
		} else {
			// Check the password
			$parts = explode(':', $hash);
			$crypt = $parts[0];
			$salt  = @$parts[1];

			$rehash = true;

			$testcrypt = md5($password . $salt) . ($salt ? ':' . $salt : '');

			$match = JCrypt::timingSafeCompare($hash, $testcrypt);
		}

		return $match;
	}

}

