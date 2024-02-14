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
 * Dummy positioner
 *
 * @package dompdf
 */
class NullPositioner extends AbstractPositioner
{

    /**
     * @param AbstractFrameDecorator $frame
     */
    function position(AbstractFrameDecorator $frame)
    {
        return;
    }
}
