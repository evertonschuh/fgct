<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JDocument head renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererHead extends JDocumentRenderer
{
	/**
	 * Renders the document head and returns the results as a string
	 *
	 * @param   string  $head     (unused)
	 * @param   array   $params   Associative array of values
	 * @param   string  $content  The script
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 *
	 * @note    Unused arguments are retained to preserve backward compatibility.
	 */
	public function render($head, $params = array(), $content = null)
	{
		ob_start();
		echo $this->fetchHead($this->_doc);
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

	/**
	 * Generates the head HTML and return the results as a string
	 *
	 * @param   JDocument  &$document  The document for which the head will be created
	 *
	 * @return  string  The head hTML
	 *
	 * @since   11.1
	 */
	public function fetchHead(&$document)
	{
		// Trigger the onBeforeCompileHead event (skip for installation, since it causes an error)
		$app = JFactory::getApplication();
		$app->triggerEvent('onBeforeCompileHead');
		// Get line endings
		$lnEnd = $document->_getLineEnd();
		$tab = $document->_getTab();
		$tagEnd = ' />';
		$buffer = '';

		// Generate base tag (need to happen first)
		$base = $document->getBase();
		if (!empty($base))
		{
			$buffer .= $tab . '<base href="' . $base . '/" />' . $lnEnd;
		}
		
		$documentTitle = $document->getTitle();
		if ($documentTitle)
		{
			$buffer .= $tab . '<title>' . htmlspecialchars($documentTitle, ENT_COMPAT, 'UTF-8') . '</title>' . $lnEnd;
		}
		
		$sitename = $document->getSiteName();
		$fromname = $document->getFromName();
		$documentAuthor = $document->getAuthor();
		
		$buffer .= $tab . '<meta name="viewport" content="initial-scale=1"/>' . $lnEnd;
		$buffer .= $tab . '<meta http-equiv="Cache-control" content="max-age=86400, public">' . $lnEnd;
		
		if($documentAuthor)
		{

			$buffer .= $tab . '<meta name="author" content="' . htmlspecialchars($sitename . ' - ' . $fromname) . '" />' . $lnEnd;
		}
		
		$documentRobots = $document->getRobots();
		if($documentRobots)
		{
			$buffer .= $tab . '<meta name="robots" content="' . htmlspecialchars($documentRobots) . '" />' . $lnEnd;
		}
		
		// Generate META tags (needs to happen as early as possible in the head)
		foreach ($document->_metaTags as $type => $tag)
		{
			foreach ($tag as $name => $content)
			{
				if ($type == 'http-equiv')
				{
					$content .= '; charset=' . $document->getCharset();
					$buffer .= $tab . '<meta http-equiv="' . $name . '" content="' . htmlspecialchars($content) . '" />' . $lnEnd;
				}
				elseif ($type == 'standard' && !empty($content))
				{
					$buffer .= $tab . '<meta name="' . $name . '" content="' . htmlspecialchars($content) . '" />' . $lnEnd;
				}
			}
		}

		// Don't add empty descriptions
		$documentDescription = $document->getDescription();
		if ($documentDescription)
		{
			$buffer .= $tab . '<meta name="description" content="' . htmlspecialchars($documentDescription) . '" />' . $lnEnd;
		}

		// Don't add empty generators
		$generator = $document->getGenerator();
		if ($generator)
		{
			$buffer .= $tab . '<meta name="generator" content="' . htmlspecialchars($generator) . '" />' . $lnEnd;
		}
				
		$documentCustonMetaTags = $document->getCustonMetaTags();
		if ($documentCustonMetaTags)
		{
			$documentCustonMetaTags = explode("\n", $documentCustonMetaTags);
			foreach($documentCustonMetaTags as $documentCustonMetaTag)
				$buffer .= $tab . $documentCustonMetaTag . $lnEnd;
		}	

		/*		
		$documentMetaFace = $document->getMetaFace();
		if ($documentMetaFace)
		{
			$buffer .= $tab . '<!-- Open Graph - Facebook -->' . $lnEnd;
			if($sitename)	
				$buffer .= $tab . '<meta property="og:title" content="' . $sitename . '" />' . $lnEnd;
	
			if($fromname)
				$buffer .= $tab . '<meta property="og:site_name" content="' . $fromname . '" />' . $lnEnd;
	
			$buffer .= $tab . '<meta property="og:type" content="website" />' . $lnEnd;
		
			if($base)
				$buffer .= $tab . '<meta property="og:url" content="'. $base . '" />' . $lnEnd;
				
			$documentLogo = $document->getLogo();
			if($documentLogo)	
				$buffer .= $tab . '<meta property="og:image" content="'. $base . '/views/system/images/'. $documentLogo . '" />' . $lnEnd;
			
			if($documentDescription)
				$buffer .= $tab . '<meta property="og:description" content="' . htmlspecialchars($documentDescription) . '" />' . $lnEnd;
				
			$buffer .= $tab . '<!-- Close Graph - Facebook -->' . $lnEnd;
		}
		*/
		

		$documentBreadcrumb = $document->getBreadcrumb();
		if ($documentBreadcrumb)
		{
			$buffer .= $tab . '<meta name="breadcrumb" content="' . htmlspecialchars($documentBreadcrumb) . '" />' . $lnEnd;
		}
		
		$documentBreadcrumbURL = $document->getBreadcrumbURL();
		if ($documentBreadcrumbURL)
		{
			$buffer .= $tab . '<meta name="breadcrumbURL" content="' . htmlspecialchars($documentBreadcrumbURL) . '" />' . $lnEnd;
		}
		
		$documentCanonical = $document->getCanonical();
		if ($documentCanonical)
		{
			$buffer .= $tab . '<meta name="canonical" content="' . htmlspecialchars($documentCanonical) . '" />' . $lnEnd;
			$buffer .= $tab . '<link rel="canonical" href="' . htmlspecialchars($documentCanonical) . '" />' . $lnEnd;
		}	
		
		
		// Generate link declarations
		foreach ($document->_links as $link => $linkAtrr)
		{
			$buffer .= $tab . '<link href="' . $link . '" ' . $linkAtrr['relType'] . '="' . $linkAtrr['relation'] . '"';
			if ($temp = JArrayHelper::toString($linkAtrr['attribs']))
			{
				$buffer .= ' ' . $temp;
			}
			$buffer .= ' />' . $lnEnd;
		}

		// Generate stylesheet links
		foreach ($document->_styleSheets as $strSrc => $strAttr)
		{
			//if( $document->datauri == true)
			//	$strSrc = 'data:text/css;base64,'. base64_encode ( file_get_contents ( $strSrc ) );
			
			$buffer .= $tab . '<link rel="stylesheet" href="' . $strSrc . '" type="' . $strAttr['mime'] . '"';
			if (!is_null($strAttr['media']))
			{
				$buffer .= ' media="' . $strAttr['media'] . '" ';
			}
			if ($temp = JArrayHelper::toString($strAttr['attribs']))
			{
				$buffer .= ' ' . $temp;
			}
			$buffer .= $tagEnd . $lnEnd;
		}

		// Generate stylesheet declarations
		foreach ($document->_style as $type => $content)
		{
			$buffer .= $tab . '<style type="' . $type . '">' . $lnEnd;
			
			// This is for full XHTML support.
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . '<![CDATA[' . $lnEnd;
			}

			$buffer .= $content . $lnEnd;

			// See above note
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . ']]>' . $lnEnd;
			}
			$buffer .= $tab . '</style>' . $lnEnd;
		}
		/*
		// Generate script file links
		foreach ($document->_scripts as $strSrc => $strAttr)
		{
			//if( $document->datauri == true)
			//	$strSrc = 'data:text/javascript;base64,'. base64_encode ( file_get_contents ( $strSrc ) );
			
			$buffer .= $tab . '<script src="' . $strSrc . '"';
			if (!is_null($strAttr['mime']))
			{
				$buffer .= ' type="' . $strAttr['mime'] . '"';
			}
			if ($strAttr['defer'])
			{
				$buffer .= ' defer="defer"';
			}
			if ($strAttr['async'])
			{
				$buffer .= ' async="async"';
			}
			$buffer .= '></script>' . $lnEnd;
		}

		// Generate script declarations
		foreach ($document->_script as $type => $content)
		{
			$buffer .= $tab . '<script type="' . $type . '">' . $lnEnd;

			// This is for full XHTML support.
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . '<![CDATA[' . $lnEnd;
			}

			$buffer .= $content . $lnEnd;

			// See above note
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . ']]>' . $lnEnd;
			}
			$buffer .= $tab . '</script>' . $lnEnd;
		}

		// Generate script language declarations.
		if (count(JText::script()))
		{
			$buffer .= $tab . '<script type="text/javascript">' . $lnEnd;
			$buffer .= $tab . $tab . '(function() {' . $lnEnd;
			$buffer .= $tab . $tab . $tab . 'var strings = ' . json_encode(JText::script()) . ';' . $lnEnd;
			$buffer .= $tab . $tab . $tab . 'if (typeof Joomla == \'undefined\') {' . $lnEnd;
			$buffer .= $tab . $tab . $tab . $tab . 'Joomla = {};' . $lnEnd;
			$buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText = strings;' . $lnEnd;
			$buffer .= $tab . $tab . $tab . '}' . $lnEnd;
			$buffer .= $tab . $tab . $tab . 'else {' . $lnEnd;
			$buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText.load(strings);' . $lnEnd;
			$buffer .= $tab . $tab . $tab . '}' . $lnEnd;
			$buffer .= $tab . $tab . '})();' . $lnEnd;
			$buffer .= $tab . '</script>' . $lnEnd;
		}
		*/
		foreach ($document->_custom as $custom)
		{
			$buffer .= $tab . $custom . $lnEnd;
		}

		return $buffer;
	}

	
	
	
}
