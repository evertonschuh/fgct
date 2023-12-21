<?php
/**
 * @package dompdf
 * @link    http://dompdf.github.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
//namespace Dompdf\Frame;

//use Dompdf\Css\Style;
//use Dompdf\Dompdf;
//use Dompdf\Exception;
//use Dompdf\Frame;
//use Dompdf\FrameDecorator\AbstractFrameDecorator;
//use DOMXPath;
//use Dompdf\FrameDecorator\Page as PageFrameDecorator;
//use Dompdf\FrameReflower\Page as PageFrameReflower;
//use Dompdf\Positioner\AbstractPositioner;


require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'Css' .DS.'Style.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Dompdf.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Exception.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Frame.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'AbstractFrameDecorator.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'Page.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'Page.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'Positioner' .DS.'AbstractPositioner.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'Block.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'Text.php');

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'Table.php');

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'TableRowGroup.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'TableRowGroup.php');

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'TableRow.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'TableRow.php');

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'TableCell.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'TableCell.php');

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'ListBullet.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'ListBullet.php');


require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'NullFrameDecorator.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'NullFrameReflower.php');

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'Inline.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'Inline.php');

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'Image.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameReflower' .DS.'Image.php');

/**
 * Contains frame decorating logic
 *
 * This class is responsible for assigning the correct {@link AbstractFrameDecorator},
 * {@link AbstractPositioner}, and {@link AbstractFrameReflower} objects to {@link Frame}
 * objects.  This is determined primarily by the Frame's display type, but
 * also by the Frame's node's type (e.g. DomElement vs. #text)
 *
 * @access  private
 * @package dompdf
 */
class Factory
{

     /**
     * Array of positioners for specific frame types
     *
     * @var AbstractPositioner[]
     */
    protected static $_positioners;

    /**
     * Decorate the root Frame
     *
     * @param $root   Frame The frame to decorate
     * @param $dompdf Dompdf The dompdf instance
     *
     * @return PageFrameDecorator
     */
    static function decorate_root(Frame $root, Dompdf $dompdf)
    {
        $frame = new PageFrameDecorator($root, $dompdf);
        $frame->set_reflower(new PageFrameReflower($frame));
        $root->set_decorator($frame);

        return $frame;
    }

    /**
     * Decorate a Frame
     *
     * @param Frame $frame   The frame to decorate
     * @param Dompdf $dompdf The dompdf instance
     * @param Frame $root    The frame to decorate
     *
     * @throws Exception
     * @return AbstractFrameDecorator
     * FIXME: this is admittedly a little smelly...
     */
    static function decorate_frame(Frame $frame, Dompdf $dompdf, Frame $root = null)
    {
        if (is_null($dompdf)) {
            throw new Exception("The DOMPDF argument is required");
        }

        $style = $frame->get_style();

        // Floating (and more generally out-of-flow) elements are blocks
        // http://coding.smashingmagazine.com/2007/05/01/css-float-theory-things-you-should-know/
        if (!$frame->is_in_flow() && in_array($style->display, Style::$INLINE_TYPES)) {
            $style->display = "block";
        }

        $display = $style->display;

        switch ($display) {

            case "flex": //FIXME: display type not yet supported 
            case "table-caption": //FIXME: display type not yet supported
            case "block":
                $positioner = "BlockPositioner";
                $decorator = "BlockFrameDecorator";
                $reflower = "BlockFrameReflower";
                break;

            case "inline-flex": //FIXME: display type not yet supported 
            case "inline-block":
                $positioner = "InlinePositioner";
                $decorator = "BlockFrameDecorator";
                $reflower = "BlockFrameReflower";
                break;

            case "inline":
                $positioner = "InlinePositioner";
                if ($frame->is_text_node()) {
                    $decorator = "TextFrameDecorator";
                    $reflower = "TextFrameReflower";
                } else {
                    if ($style->float !== "none") {
                       $decorator = "BlockFrameDecorator";
                        $reflower = "BlockFrameReflower";
                    } else {
                        $decorator = "InlineFrameDecorator";
                        $reflower = "InlineFrameReflower";
                    }
                }
                break;

            case "table":
                $positioner = "BlockPositioner";
                $decorator = "TableFrameDecorator";
                $reflower = "TableFrameReflower";
                break;

            case "inline-table":
                $positioner = "InlinePositioner";
                $decorator = "TableFrameDecorator";
                $reflower = "TableFrameReflower";
                break;

            case "table-row-group":
            case "table-header-group":
            case "table-footer-group":
                $positioner = "NullPositioner";
                $decorator = "TableRowGroupFrameDecorator";
                $reflower = "TableRowGroupFrameReflower";
                break;

            case "table-row":
                $positioner = "NullPositioner";
                $decorator = "TableRowFrameDecorator";
                $reflower = "TableRowFrameReflower";
                break;

            case "table-cell":
                $positioner = "TableCellPositioner";
                $decorator = "TableCellFrameDecorator";
                $reflower = "TableCellFrameReflower";
                break;

            case "list-item":
                $positioner = "BlockPositioner";
                $decorator = "BlockFrameDecorator";
                $reflower = "BlockFrameReflower";
                break;

            case "-dompdf-list-bullet":
                if ($style->list_style_position === "inside") {
                    $positioner = "InlinePositioner";
                } else {
                    $positioner = "ListBulletPositioner";
                }

                if ($style->list_style_image !== "none") {
                    $decorator = "ListBulletImageFrameDecorator";
                } else {
                    $decorator = "ListBulletFrameDecorator";
                }

                $reflower = "ListBulletFrameReflower";
                break;

            case "-dompdf-image":
                $positioner = "InlinePositioner";
                $decorator = "ImageFrameDecorator";
                $reflower = "ImageFrameReflower";
                break;

            case "-dompdf-br":
                $positioner = "InlinePositioner";
                $decorator = "InlineFrameDecorator";
                $reflower = "InlineFrameReflower";
                break;

            default:
                // FIXME: should throw some sort of warning or something?
            case "none":
                if ($style->_dompdf_keep !== "yes") {
                    // Remove the node and the frame
                    $frame->get_parent()->remove_child($frame);
                    return;
                }

                $positioner = "NullPositioner";
                $decorator = "NullFrameDecorator";
                $reflower = "NullFrameReflower";
                break;
        }

        // Handle CSS position
        $position = $style->position;

        if ($position === "absolute") {
            $positioner = "AbsolutePositioner";
        } else {
            if ($position === "fixed") {
                $positioner = "FixedPositioner";
            }
        }

        $node = $frame->get_node();

        // Handle nodeName
        if ($node->nodeName === "img") {
            $style->display = "-dompdf-image";
            $decorator = "ImageFrameDecorator";
            $reflower = "ImageFrameReflower";
        }

        $decorator  = "$decorator";
        $reflower   = "$reflower";

 ;
        if(!$frame)
        return;

		if(empty($positioner))
		{
			echo $display;
		}

        /** @var AbstractFrameDecorator $deco */
        $deco = new $decorator($frame, $dompdf);

        $deco->set_positioner(self::getPositionerInstance($positioner));
        $deco->set_reflower(new $reflower($deco, $dompdf->getFontMetrics()));

        if ($root) {
            $deco->set_root($root);
        }

        if ($display === "list-item") {
            // Insert a list-bullet frame
            $xml = $dompdf->getDom();
            $bullet_node = $xml->createElement("bullet"); // arbitrary choice
            $b_f = new Frame($bullet_node);

            $node = $frame->get_node();
            $parent_node = $node->parentNode;

            if ($parent_node) {
                if (!$parent_node->hasAttribute("dompdf-children-count")) {
                    $xpath = new DOMXPath($xml);
                    $count = $xpath->query("li", $parent_node)->length;
                    $parent_node->setAttribute("dompdf-children-count", $count);
                }

                if (is_numeric($node->getAttribute("value"))) {
                    $index = intval($node->getAttribute("value"));
                } else {
                    if (!$parent_node->hasAttribute("dompdf-counter")) {
                        $index = ($parent_node->hasAttribute("start") ? $parent_node->getAttribute("start") : 1);
                    } else {
                        $index = (int)$parent_node->getAttribute("dompdf-counter") + 1;
                    }
                }

                $parent_node->setAttribute("dompdf-counter", $index);
                $bullet_node->setAttribute("dompdf-counter", $index);
            }

            $new_style = $dompdf->getCss()->create_style();
            $new_style->display = "-dompdf-list-bullet";
            $new_style->inherit($style);
            $b_f->set_style($new_style);

            $deco->prepend_child(Factory::decorate_frame($b_f, $dompdf, $root));
        }

        return $deco;
    }

    /**
     * Creates Positioners
     *
     * @param string $type type of positioner to use
     * @return AbstractPositioner
     */
    protected static function getPositionerInstance($type)
    {
        if (!isset(self::$_positioners[$type])) {
			
			if(str_replace('Positioner', '', $type) != '' && $type != 'NullPositioner' && $type != 'AbstractPositioner')
				$file = str_replace('Positioner', '', $type);
			else
				$file = $type;
				
				
			require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Positioner' .DS. $file.'.php');
            $class = $type;
            self::$_positioners[$type] = new $class();
        }
        return self::$_positioners[$type];
    }
}
