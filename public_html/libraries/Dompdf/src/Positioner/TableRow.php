<?php
/**
 * @package dompdf
 * @link    http://dompdf.github.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

//namespace Dompdf\Positioner;

//use Dompdf\FrameDecorator\AbstractFrameDecorator;

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'FrameDecorator' .DS. 'AbstractFrameDecorator.php');

/**
 * Positions table rows
 *
 * @package dompdf
 */
class TableRowPositioner extends AbstractPositioner
{

    /**
     * @param AbstractFrameDecorator $frame
     */
    function position(AbstractFrameDecorator $frame)
    {
        $cb = $frame->get_containing_block();
        $p = $frame->get_prev_sibling();

        if ($p) {
            $y = $p->get_position("y") + $p->get_margin_height();
        } else {
            $y = $cb["y"];
        }
        $frame->set_position($cb["x"], $y);
    }
}
