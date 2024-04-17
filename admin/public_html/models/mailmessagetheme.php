<?php
/**
 * @site		Site
 * @developer	Everton Alexandre Schuh
 * @copyright	Copyright (C) 2014, All rights reserved. 
 */

defined( '_JEXEC' ) or die;

jimport( 'joomla.application.component.modellist' );

class EASistemasModelMailMessageTheme extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_db = null;
	var $_app = null;	
	var $_isRoot = null;
	var $_user = null;
	var $_userAdmin = null;
	
	function __construct()
	{	
		parent::__construct();
		
		$this->_db	= JFactory::getDBO();
		$this->_user = JFactory::getUser();
		$this->_app = JFactory::getApplication();
		
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
				$link = 'index.php?view=mailmessagethemes';
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
			$query->from($this->_db->quoteName('#__intranet_mailmessage_theme'));
			$query->where( $this->_db->quoteName('id_mailmessage_theme') . '=' . $this->_db->quote( $this->_id ) );
			$this->_db->setQuery($query);
			if(!(boolean) $this->_data = $this->_db->loadObject()){
				$this->addTablePath(JPATH_BASE.'/tables');
				$this->_data = $this->getTable('mailmessagetheme');	
			}
		}
		return $this->_data;
	}

	
	function store() 
	{


		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('mailmessagetheme');
		$data = JRequest::get( 'post' );
		
		
		$data['theme_mailmessage_theme'] = JRequest::getVar('theme_mailmessage_theme', null, 'default', 'none', JREQUEST_ALLOWRAW);

		//$data['theme_mailmessage_theme'] = str_replace('href="../', 'href="https://admin.fgct.com.br/', $data['theme_mailmessage_theme']);

		
		if($this->_id)
			$row->load($this->_id);
		
			
		$keysSwitch = array('status_mailmessage_theme');
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
			$this->setId( $row->get('id_mailmessage_theme') ); 			
			$textLog = 'new item';
		endif;

		JRequest::setVar( 'cid', $this->_id );

		jimport('joomla.log.log');
		JLog::addLogger(array( 'text_file' => 'log.mailmessagetheme.php'));
		JLog::add($this->_user->get('id') . ' - ' . $textLog.' -  id item ('.$this->_id.')', JLog::INFO, 'mailmessagetheme');
		
		
		return true;
	}
	
	function sendmail()
	{
		if (empty($this->_data))
			$this->getItem();	
		
		$recipient = JRequest::getVar('destinatario', '', 'post');  
		$subject = 'Teste de Envio - Tema de Mensagem';
        
		$mailer = JFactory::getMailer(); 
		
		$mailer->addRecipient($recipient);

        $fromname	= $this->_app->getCfg('fromname');
        $mailfrom	= $this->_app->getCfg('mailfrom');
        $sitename	= $this->_app->getCfg('sitename');
        $mailer->setSender( array( $mailfrom, $fromname ) );


		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setSubject($subject);
        
        
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
		
		$mailer->setBody($emailBody);
		
		$mailer->Send();
		
		return true;
	}

	
	
	function isCheckedOut()
	{		
		$this->addTablePath(JPATH_BASE.'/tables');
	    $row = $this->getTable('mailmessagetheme');
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
	    $row = $this->getTable('mailmessagetheme');
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
	    $row = $this->getTable('mailmessagetheme');
		
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
