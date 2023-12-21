<?php
/**
* @package		Joomla.Framework
* @subpackage	HTML
* @author    nullbarriere <nullbarriere@hyperjoint.com>
* @version   1.0
* @date      13 Dec 2007
*/

/**
 * Utility class for creating HTML input fields
 *
 * @static
 * @package 	Joomla.Framework
 * @subpackage	HTML
 * @since		1.5
 */
class JHTMLInput
  {
	/**
	 * Output HTML for input elements
	 *
	 * @param	string	The type of the field
	 * @param	string	The name of the field
	 * @param	string	The initial value of the field
	 * @param	array  HTML attributes,
	 * containg name/value pairs like id(!), class, css, onclick ...
	 * @param	mixed	The label of the field as string or array(text,pos[left|right])
	 * @returns string  The HTML of the specified input field (plus label if any)
	 */
	function _input($type, $name, $value='', $attribs='', $label='')
    {
    if($label)
      {
      if(!is_array($label))$label=array(text=>$label);
      if(!$attribs[id])$attribs[id]="id_$name";
      $text=trim(JText::_($label[text]));
      $label[text]="<label for=\"$attribs[id]\">$text</label>";
      }

    $attribs=JArrayHelper::toString($attribs);
    $input="<input type=\"$type\" name=\"$name\" value=\"$value\" $attribs />";
    return $label[pos]==right?"$input$label[text]":"$label[text]$input";
    }
	/**
	 * Creates a hidden input field
	 *
	 * @param	string	The name of the field
	 * @param	string	The value of the field
	 * @param	string	The id of the field (optional)
	 * @returns string   The HTML of the specified hidden input field
	 */
	function hidden($name, $value='', $id='')
  	{
  	$attribs=array(); if($id)$attribs[id]=$id;
  	return JHTMLInput::_input('hidden', $name, $value, $attribs);
	  }
	/**
	 * Creates an input type text field
	 *
	 * @param	string	The name of the field
	 * @param	string	The initial value of the field (optional)
	 * @param	mixed  Additional HTML attributes (optional,
	 * either type string which makes for an id,
	 * or an indexed array of name/value pairs)
	 * @param	string	The label of the field (very optional)
	 * @returns string  The HTML of the specified input field (plus label if any)
	 */
	function text($name, $value='', $attribs='', $label='')
    {
    //presume id was given instead of attrib array
    if($attribs!=''&&is_string($attribs))$attribs=array(id=>$attribs);
    //default input class if none given
    if(!$attribs['class'])$attribs['class']=$attribs[readonly]?'readonly':'inputbox';

    return JHTMLInput::_input('text', $name, $value, $attribs, $label) ;
	  }
	/**
	 * Bogey input.input function creates an input type text field too,
	 * see input.text
	 */
	function input($name, $value='', $attribs='', $label='')
    {
    return JHTMLInput::text($name, $value, $attribs, $label);
    }
	/**
	 * Creates a radio box
	 *
	 * @param	string	The name of the field
	 * @param	string	The value of the field (optional)
	 * @param	array  Additional HTML attributes (optional,
	 * either type string 'checked' or any other string as id,
	 * or an indexed array of name/value pairs)
	 * @param	string	The label of the field (very optional)
	 * @returns string  The HTML of the specified input field (plus label if any)
	 */
	function radio($name, $value='', $attribs='', $label='')
    {
    //presume checked or id was given instead of attrib array
    if($attribs!=''&&is_string($attribs))$attribs=$attribs=='checked'?array(checked=>$attribs):array(id=>$attribs);
    //default input class if none given
    if(!$attribs['class'])$attribs['class']='radio';

    return JHTMLInput::_input('radio', $name, $value, $attribs, array(text=>$label,pos=>'right')) ;
	  }
	/**
	 * Creates a checkbox
	 *
	 * @param	string	The name of the field
	 * @param	string	The value of the field (optional)
	 * @param	array  Additional HTML attributes (optional,
	 * either type string 'checked' or any other string as id,
	 * or an indexed array of name/value pairs)
	 * @param	string	The label of the field (very optional)
	 * @returns string  The HTML of the specified input field (plus label if any)
	 */
	function checkbox($name, $value='', $attribs='', $label='')
    {
    //presume checked or id was given instead of attrib array
    if($attribs!=''&&is_string($attribs))$attribs=$attribs=='checked'?array(checked=>$attribs):array(id=>$attribs);
    //default input class if none given
    if(!$attribs['class'])$attribs['class']='radio';

    return JHTMLInput::_input('checkbox', $name, $value, $attribs, array(text=>$label,pos=>'right')) ;
	  }
	/**
	 * Creates an input type text field
	 *
	 * @param	string	The name of the field
	 * @param	string	The initial value of the field (optional)
	 * @param	mixed  Additional HTML attributes (optional,
	 * either type string which makes for an id,
	 * or an indexed array of name/value pairs)
	 * @param	string	The label of the field (very optional)
	 * @returns string  The HTML of the specified input field (plus label if any)
	 */
	function textarea($name, $value='', $attribs='', $label='')
    {
    //presume id was given instead of attrib array
    if($attribs!=''&&is_string($attribs))$attribs=array(id=>$attribs);
    //default input class if none given
    if(!$attribs['class'])$attribs['class']=$attribs[readonly]?'readonly':'inputbox';

    $attribs=JArrayHelper::toString($attribs);
    return "$label<textarea name=\"$name\" $attribs>$value</textarea>";
	  }
}
