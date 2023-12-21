<?php
/**
 * @version		4.7.0
 * @package		AllVideos (plugin)
 * @author    	JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2015 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
if (version_compare(JVERSION, '2.5.0', 'ge')) {
	jimport('joomla.html.parameter');
}

class plgContentAllvideos extends JPlugin {

	// JoomlaWorks reference parameters
	var $plg_name					= "allvideos";
	var $plg_copyrights_start		= "\n\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.7.0) starts here -->\n";
	var $plg_copyrights_end			= "\n<!-- JoomlaWorks \"AllVideos\" Plugin (v4.7.0) ends here -->\n\n";

	function plgContentAllvideos( $subject, $params ) {
		parent::__construct( $subject, $params );
		// Define the DS constant under Joomla! 3.0+
		if (!defined('DS')) {
			define('DS', DIRECTORY_SEPARATOR);
		}
	}


	// Joomla! 2.5+
	function onContentPrepare($row, $params, $page = 0){
			
		// Check if plugin is enabled
		if (JPluginHelper::isEnabled('content',$this->plg_name)==false) return;
			
		// API
		jimport('joomla.filesystem.file');
		$app = JFactory::getApplication();
		$document  = JFactory::getDocument();

		// Assign paths
		$sitePath = JPATH_SITE;
		$siteUrl  = JURI::root(true);
		$siteUrl  = str_replace('/admin','', $siteUrl);
		$pluginLivePath = $siteUrl.'/plugins/content/'.$this->plg_name.'/'.$this->plg_name;	


		// Load the plugin language file the proper way
		//JPlugin::loadLanguage('plg_content_'.$this->plg_name, JPATH_ADMINISTRATOR);

		// Includes
		require_once(dirname(__FILE__).DS.$this->plg_name.DS.'includes'.DS.'helper.php');
		require(dirname(__FILE__).DS.$this->plg_name.DS.'includes'.DS.'sources.php');



		// Simple performance check to determine whether plugin should process further
		$grabTags = strtolower(implode(array_keys($tagReplace),"|"));

		if (preg_match("#{(".$grabTags.")}#is", $row->text)==false) return;


		/* Video Parameters */
		$playerTemplate					= $this->params->get('playerTemplate','responsive');
		$vfolder 						= $this->params->get('vfolder','videos');
		$vwidth 						= $this->params->get('vwidth',400);
		$vheight 						= $this->params->get('vheight',300);
		$transparency 					= $this->params->get('transparency','transparent');
		$background 					= $this->params->get('background','#010101');
		$backgroundQT					= $this->params->get('backgroundQT','black');
		$controls 						= $this->params->get('controls',1);
		/* Audio Parameters */
		$afolder 						= $this->params->get('afolder','audio');
		$awidth 						= $this->params->get('awidth',480);
		$aheight 						= $this->params->get('aheight',24);
		$abackground 					= $this->params->get('abackground','#010101');
		$afrontcolor 					= $this->params->get('afrontcolor','#FFFFFF');
		$alightcolor 					= $this->params->get('alightcolor','#00ADE3');
		$allowAudioDownloading			= $this->params->get('allowAudioDownloading',0);
		/* Global Parameters */
		$autoplay 						= $this->params->get('autoplay',0);
		$loop 							= $this->params->get('loop',0);
		/* Performance Parameters */
		$gzipScripts					= $this->params->get('gzipScripts',0);
		/* Advanced */
		$jwPlayerLoading				= $this->params->get('jwPlayerLoading','local'); // local | cdn
		$jwPlayerAPIKey					= $this->params->get('jwPlayerAPIKey','plXkZcoHeQXVlRo0nD6AUscwEXmFJCmIpGL3kw==');
		$jwPlayerCDNUrl					= $this->params->get('jwPlayerCDNUrl','http://jwpsrv.com/library/n9Po9gncEeOKaBIxOUCPzg.js');

		// Variable cleanups for K2
		if (JRequest::getCmd('format')=='raw') {
			$this->plg_copyrights_start = '';
			$this->plg_copyrights_end = '';
		}

		// Assign the AllVideos helper class
		$AllVideosHelper = new AllVideosHelper;
		


		// ----------------------------------- Render the output -----------------------------------

		// Append head includes only when the document is in HTML mode
		if (JRequest::getCmd('format')=='html' || JRequest::getCmd('format')=='') {

			// CSS
			$avCSS = $AllVideosHelper->getTemplatePath($this->plg_name,'css/template.css',$playerTemplate);
			$avCSS = $avCSS->http;
			$avCSS  = str_replace('/admin','', $avCSS);
			$document->addStyleSheet($avCSS);


			// JS
			JHtml::_('behavior.framework');

			if ($gzipScripts) {
				$document->addScript($pluginLivePath.'/includes/js/jwp.js.php?v=4.7.0');
			} else {
				$document->addScript($pluginLivePath.'/includes/js/behaviour.js?v=4.7.0');
				$document->addScript($pluginLivePath.'/includes/js/wmvplayer/silverlight.js?v=4.7.0');
				$document->addScript($pluginLivePath.'/includes/js/wmvplayer/wmvplayer.js?v=4.7.0');
				$document->addScript($pluginLivePath.'/includes/js/quicktimeplayer/ac_quicktime.js?v=4.7.0');
			}

			if($jwPlayerLoading=='local'){
				$document->addScript($pluginLivePath.'/includes/js/jwplayer/jwplayer.js?v=4.7.0');
				$document->addScriptDeclaration('
					/* JW Player API Key */
					jwplayer.key="'.$jwPlayerAPIKey.'";
				');
			} else {
				$document->addScript($jwPlayerCDNUrl);
			}

		}

		// Loop throught the found tags
		$tagReplace = array_change_key_case($tagReplace, CASE_LOWER);
		foreach ($tagReplace as $plg_tag => $value) {
			
			$cloned_plg_tag = $plg_tag;
			$plg_tag = strtolower($plg_tag);

			// expression to search for
			$regex = "#{".$plg_tag."}.*?{/".$plg_tag."}#is";

			// process tags
			if (preg_match_all($regex, $row->text, $matches, PREG_PATTERN_ORDER)) {
				
				if ($params['type']=='list')
				{
					$row->text = preg_replace($regex, '' , $row->text);
					return $row->text;
				}

				
				// start the replace loop
				foreach ($matches[0] as $key => $match) {

					$tagcontent 	= preg_replace("/{.+?}/", "", $match);
					$tagcontent		= str_replace(array('"','\'','`'), array('&quot;','&apos;','&#x60;'), $tagcontent); // Address potential XSS attacks
					$tagparams 		= explode('|',$tagcontent);
					$tagsource 		= trim(strip_tags($tagparams[0]));

					// Prepare the HTML
					$output = new JObject;

					// Width/height/source folder split per media type
					if (in_array($plg_tag, array(
						'mp3',
						'mp3remote',
						'aac',
						'aacremote',
						'm4a',
						'm4aremote',
						'ogg',
						'oggremote',
						'wma',
						'wmaremote',
						'soundcloud'
					))) {
						$final_awidth 	= (@$tagparams[1]) ? $tagparams[1] : $awidth;
						$final_aheight 	= (@$tagparams[2]) ? $tagparams[2] : $aheight;

						$output->playerWidth = $final_awidth;
						$output->playerHeight = $final_aheight;
						$output->folder = $afolder;

						if ($plg_tag=='soundcloud') {
							if (strpos($tagsource,'/sets/')!==false) {
								$output->mediaTypeClass = ' avSoundCloudSet';
							} else {
								$output->mediaTypeClass = ' avSoundCloudSong';
							}
							$output->mediaType = '';
						} else {
							$output->mediaTypeClass = ' avAudio';
							$output->mediaType = 'audio';
						}

						if (in_array($plg_tag, array('mp3','aac','m4a','ogg','wma'))) {
							$output->source = "$siteUrl/$afolder/$tagsource.$plg_tag";
						} elseif (in_array($plg_tag, array('mp3remote','aacremote','m4aremote','oggremote','wmaremote'))) {
							$output->source = $tagsource;
						} else {
							$output->source = '';
						}
					} else {
						$final_vwidth 	= (@$tagparams[1]) ? $tagparams[1] : $vwidth;
						$final_vheight 	= (@$tagparams[2]) ? $tagparams[2] : $vheight;

						$output->playerWidth = $final_vwidth;
						$output->playerHeight = $final_vheight;
						$output->folder = $vfolder;
						$output->mediaType = 'video';
						$output->mediaTypeClass = ' avVideo';
					}

					
					

					// Autoplay
					$final_autoplay = (@$tagparams[3]) ? $tagparams[3] : $autoplay;
					$final_autoplay	= ($final_autoplay) ? 'true' : 'false';

					// Loop
					$final_loop = (@$tagparams[4]) ? $tagparams[4] : $loop;
					$final_loop	= ($final_loop) ? 'true' : 'false';

					// Special treatment for specific video providers
					if ($plg_tag=="dailymotion") {
						$tagsource = preg_replace("~(http|https):(.+?)dailymotion.com\/video\/~s","",$tagsource);
						$tagsourceDailymotion = explode('_',$tagsource);
						$tagsource = $tagsourceDailymotion[0];
						if ($final_autoplay=='true') {
							if (strpos($tagsource,'?')!==false) {
								$tagsource = $tagsource.'&amp;autoplay=1';
							} else {
								$tagsource = $tagsource.'?autoplay=1';
							}
						}
					}

					if ($plg_tag=="ku6") {
						$tagsource = str_replace('.html','',$tagsource);
					}

					if ($plg_tag=="metacafe" && substr($tagsource,-1,1)=='/') {
						$tagsource = substr($tagsource,0,-1);
					}

					if ($plg_tag=="sevenload") {
						$tagsource = parse_url($tagsource);
						$tagsource = explode('-',$tagsource['query']);
						$tagsource = array_reverse($tagsource);
						$tagsource = $tagsource[0];
					}

					if ($plg_tag=="sohu") {
						$tagsource = parse_url($tagsource);
						$tagsource = explode('/',$tagsource['query']);
						$tagsource = array_reverse($tagsource);
						$tagsource = str_replace('.shtml','',$tagsource[0]);
					}

					if ($plg_tag=="vidiac") {
						$tagsourceVidiac = explode(';',$tagsource);
						$tagsource = $tagsourceVidiac[0];
					}

					if ($plg_tag=="vimeo") {
						$tagsource = preg_replace("~(http|https):(.+?)vimeo.com\/~s","",$tagsource);
						if (strpos($tagsource,'?')!==false) {
							$tagsource = $tagsource.'&amp;portrait=0';
						} else {
							$tagsource = $tagsource.'?portrait=0';
						}
						if ($final_autoplay=='true') {
							$tagsource = $tagsource.'&amp;autoplay=1';
						}
						if ($final_loop=='true') {
							$tagsource = $tagsource.'&amp;loop=1';
						}
					}

					if ($plg_tag=="vine") {
						if (strpos($tagsource,'http')===false) {
							$tagsource = 'https://vine.co/v/'.$tagsource;
						}
					}

					if ($plg_tag=="yfrog") {
						$tagsource = preg_replace("~(http|https):(.+?)yfrog.com\/~s","",$tagsource);
					}

					if ($plg_tag=="youmaker") {
						$tagsourceYoumaker = explode('-',str_replace('.html','',$tagsource));
						$tagsource = $tagsourceYoumaker[1];
					}

					if ($plg_tag=="youku") {
						$tagsource = str_replace('.html','',$tagsource);
						$tagsource = substr($tagsource,3);
					}

					if ($plg_tag=="youtube") {

						// Check the presence of fully pasted URLs
						if (strpos($tagsource,'youtube.com')!==false || strpos($tagsource,'youtu.be')!==false) {
							$ytQuery = parse_url($tagsource, PHP_URL_QUERY);
							$ytQuery = str_replace('&amp;', '&', $ytQuery);
						} else {
							$ytQuery = $tagsource;
						}

						// Process string
						if (strpos($ytQuery,'&')!==false) {
							$ytQuery = explode('&',$ytQuery);
							$ytParams = array();
							foreach($ytQuery as $ytParam) {
								$ytParam = explode('=',$ytParam);
								$ytParams[$ytParam[0]] = $ytParam[1];
							}
							if(array_key_exists('v', $ytParams)){
								$tagsource = $ytParams['v'];
							} elseif(array_key_exists('list', $ytParams)){
								$tagsource = 'videoseries?list='.$ytParams['list'];
							}
						} elseif (strpos($ytQuery,'=')!==false) {
							$ytQuery = explode('=',$ytQuery);
							$ytParams = array();
							$ytParams[$ytQuery[0]] = $ytQuery[1];
							if(array_key_exists('v', $ytParams)){
								$tagsource = $ytParams['v'];
							} elseif(array_key_exists('list', $ytParams)){
								$tagsource = 'videoseries?list='.$ytParams['list'];
							}
						} else {
							if(substr($tagsource, 0, 2)=="PL"){
								$tagsource = 'videoseries?list='.$tagsource;
							}
						}

						if (strpos($tagsource,'?')!==false) {
							$tagsource = $tagsource.'&amp;rel=0&amp;fs=1&amp;wmode=transparent';
						} else {
							$tagsource = $tagsource.'?rel=0&amp;fs=1&amp;wmode=transparent';
						}

						// Additional playback parameters
						if ($final_autoplay=='true') {
							$tagsource = $tagsource.'&amp;autoplay=1';
						}
						if ($final_loop=='true') {
							$tagsource = $tagsource.'&amp;loop=1';
						}

					}

					// Poster frame
					$ifolder =  $vfolder;
					$posterFramePath = $sitePath.DS.str_replace('/',DS,$ifolder);
					//echo 'asda';
					
					if (JFile::exists($posterFramePath.DS.$tagsource.'.jpg')) {
						$output->posterFrame = $siteUrl.'/'.$ifolder.'/'.$tagsource.'.jpg';
					} elseif (JFile::exists($posterFramePath.DS.$tagsource.'.png')) {
						$output->posterFrame = $siteUrl.'/'.$ifolder.'/'.$tagsource.'.png';
					} elseif (JFile::exists($posterFramePath.DS.$tagsource.'.gif')) {
						$output->posterFrame = $siteUrl.'/'.$ifolder.'/'.$tagsource.'.gif';
					} else {
						$output->posterFrame = '';
					}



					
					// Poster frame (remote)
					$output->posterFrameRemote = substr($tagsource,0,-3).'jpg';

					// Set a unique ID
					$output->playerID = 'AVPlayerID_'.$key.'_'.md5($tagsource);

					// Placeholder elements
					$findAVparams = array(
						"{SOURCE}",
						"{SOURCEID}",
						"{FOLDER}",
						"{WIDTH}",
						"{HEIGHT}",
						"{PLAYER_AUTOPLAY}",
						"{PLAYER_LOOP}",
						"{PLAYER_TRANSPARENCY}",
						"{PLAYER_BACKGROUND}",
						"{PLAYER_BACKGROUNDQT}",
						"{JWPLAYER_CONTROLS}",
						"{SITEURL}",
						"{SITEURL_ABS}",
						"{FILE_EXT}",
						"{PLUGIN_PATH}",
						"{PLAYER_POSTER_FRAME}",
						"{PLAYER_POSTER_FRAME_REMOTE}",
						"{PLAYER_ABACKGROUND}",
						"{PLAYER_AFRONTCOLOR}",
						"{PLAYER_ALIGHTCOLOR}"
					);

					// Replacement elements
					$replaceAVparams = array(
						$tagsource,
						$output->playerID,
						$output->folder,
						$output->playerWidth,
						$output->playerHeight,
						$final_autoplay,
						$final_loop,
						$transparency,
						$background,
						$backgroundQT,
						$controls,
						$siteUrl,
						substr(JURI::root(false),0,-1),
						$plg_tag,
						$pluginLivePath,
						$output->posterFrame,
						$output->posterFrameRemote,
						$abackground,
						$afrontcolor,
						$alightcolor
					);

					// Do the element replace
					$output->player = JFilterOutput::ampReplace(str_replace($findAVparams, $replaceAVparams, $tagReplace[$cloned_plg_tag]));
					
					// Fetch the template
					ob_start();
					$getTemplatePath = $AllVideosHelper->getTemplatePath($this->plg_name,'default.php',$playerTemplate);
					$getTemplatePath = $getTemplatePath->file;
					include($getTemplatePath);
					$getTemplate = $this->plg_copyrights_start.ob_get_contents().$this->plg_copyrights_end;
					ob_end_clean();
					//print_r($output);
					$style = '.avPlayerWrapper {'
								. 'max-width:{WIDTH}px;'
								. 'max-height:{HEIGHT}px;'
								. 'margin: 0 auto;'
								. '}'; 
								
					$style = JFilterOutput::ampReplace(str_replace($findAVparams, $replaceAVparams, $style));			
					$document->addStyleDeclaration( $style );
					
					
					// Output
					$row->text = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#is", $getTemplate , $row->text);
				
					return $row->text;
				} // End second foreach

			} // End if

		} // End first foreach

	} // End function

} // End class
