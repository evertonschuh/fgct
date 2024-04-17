<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class EASistemasModelMailMessage extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;
	var $_urlbase = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();

		$this->_app = JFactory::getApplication();
		$this->_urlbase = $this->_app->getCfg('urlbase');

		$this->_isRoot		= $this->_user->get('isRoot');	
		$this->_userAdmin	= $this->_user->get('id');

		if( JRequest::getVar( 'task' )  != 'add')
		{
			$array  	= JRequest::getVar( 'cid', array(0), '', 'array');
			JRequest::setVar( 'cid', $array[0] );
			$this->setId( (int) $array[0] );
		
			if (!$this->isCheckedOut() )
			{
				$this->checkout();		
			}
			else
			{
				$tipo = 'alert-warning';
				$msg = JText::_( 'JGLOBAL_CONTROLLER_CHECKIN_ITEM' );
				$link = 'index.php?view=mailmessages';
				$this->_app->redirect($link, $msg, $tipo);
			}
		}
		
	}
	
	function setId( $id ) 
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getItem()
	{	
		if (empty($this->_data))
		{			
			$query = $this->_db->getQuery(true);	
			$query->select('*');	
			$query->from($this->_db->quoteName('#__intranet_mailmessage'));
			$query->leftJoin($this->_db->quoteName('#__intranet_mailmessage_theme') . ' USING ('.$this->_db->quoteName('id_mailmessage_theme').')');
			$query->leftJoin($this->_db->quoteName('#__intranet_mailmessage_occurrence') . ' USING ('.$this->_db->quoteName('id_mailmessage_occurrence').')');
			$query->where( $this->_db->quoteName('id_mailmessage') . '=' . $this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()){
				$this->addTablePath(JPATH_BASE.'/tables');
				$this->_data = $this->getTable('mailmessage');	
			}
		}
		return $this->_data;
	}
	
    function getThemes()
    {
		$query = $this->_db->getQuery(true);	
        $query->select('id_mailmessage_theme as value, name_mailmessage_theme as text');
		$query->from($this->_db->quoteName('#__intranet_mailmessage_theme'));
		$query->where($this->_db->quoteName('status_mailmessage_theme').'='.$this->_db->quote('1') );
		$query->order($this->_db->quoteName('name_mailmessage_theme'));
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
        
    }

	function getOccurrences()
	{		
		$query = $this->_db->getQuery(true);
		$query->select('id_mailmessage_occurrence as value, name_mailmessage_occurrence as text');
		$query->from('#__intranet_mailmessage_occurrence');
		$query->where( $this->_db->quoteName('status_mailmessage_occurrence').'='.$this->_db->quote('1') );
		$query->order($this->_db->quoteName('name_mailmessage_occurrence'));

		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
		
	}

	function store() 
	{

	//	$config   = JFactory::getConfig();
	//	$siteOffset = $config->getValue('offset');

		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('mailmessage');
		$data = JRequest::get( 'post' );
		
		
		$data['mensagem_mailmessage'] = JRequest::getVar('mensagem_mailmessage', null, 'default', 'none', JREQUEST_ALLOWHTML);
		$data['mensagem_mailmessage'] = str_replace('href="../', 'href="'.$this->_urlbase.'/', $data['mensagem_mailmessage']);

		
		if($this->_id)
			$row->load($this->_id);
		
		
		$keysSwitch = array('status_mailmessage');
		foreach($keysSwitch as $key => $value)
			$data[$value] = isset($data[$value]) ? $data[$value] : '0';


		if ( !$row->bind($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}
			
		if ( !$row->check($data)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;	
		}	
			
		if ( !$row->store(TRUE) ) 
		{	
			$this->setError( $this->_db->getErrorMsg());
			return false;	
		}
		

		if($this->_id):
			$row->checkin($this->_id);
			$textLog = 'edit item';
		else:
			$this->setId( $row->get('id_mailmessage') ); 			
			$textLog = 'new item';
		endif;

		JRequest::setVar( 'cid', $this->_id );

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.mailmessage.php'));
		JLog::add($this->_user->get('id') . ' - ' . $textLog.' -  id item ('.$this->_id.')', JLog::INFO, 'mailmessage');
		
		
		return true;
	}
	
	function sendmail()
	{

        
		if (empty($this->_data))
			$this->getItem();	

		$mailer = JFactory::getMailer(); 
		
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';

        $emailBody = $this->_data->theme_mailmessage_theme;
             
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
                    
                   $mailer->AddEmbeddedImage( JPATH_BASE .DS. str_replace('/', DS, $imgUrl) , 'image'.$i, 'image'.$i.'.jpg', 'base64', 'image/jpg' );                   
                   $newkey = str_replace($imgUrl, 'cid:image'.$i, $key);
                   $emailBody =  str_replace($key, $newkey, $emailBody);
                }  
            }  
        }
        
        $emailBody = str_replace('{{MENSAGEM}}', $this->_data->mensagem_mailmessage, $emailBody);
		$mailer->setBody($emailBody);
        
		$subject = $this->_data->subject_mailmessage;
		$mailer->setSubject($subject);
        
		$recipient = JRequest::getVar('destinatario', '', 'post');
		$mailer->addRecipient($recipient);
		
		if($this->_data->account_mailmessage && $this->_data->password_mailmessage): 
			$mailer->useSMTP('1', 'smtp.gmail.com', $this->_data->account_mailmessage, $this->_data->password_mailmessage, 'ssl', '465');
			$mailer->setSender( array( $this->_data->account_mailmessage, $this->_data->name_account_mailmessage) );
		else:
			$fromname	= $this->_app->getCfg('fromname');
			$mailfrom	= $this->_app->getCfg('mailfrom');
			$mailer->setSender( array( $mailfrom, $fromname ) );
		endif;		
		
		if(!empty($this->_data->replyto_mailmessage))
			$mailer->addReplyTo($this->_data->replyto_mailmessage);
		
		$mailer->Send();
		
		return true;
	}

	
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('mailmessage');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if (!$row->isCheckedOut( $this->_userAdmin ) )
			{
				return false;
			}
			return true;
		}
		return false;
	}
	
	function checkout()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('mailmessage');
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if(! $row->checkout( $this->_userAdmin ) ) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}
	   
	function checkin()
	{	
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('mailmessage');
		
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$cid = $cid[0];
		if ( $row->load( $cid ) ) 
		{	
			if(! $row->checkin( $cid ) ) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}

}
