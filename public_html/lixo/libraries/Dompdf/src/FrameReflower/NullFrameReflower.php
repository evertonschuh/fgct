<?php
/**
 * @package dompdf
 * @link    http://dompdf.github.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

//namespace Dompdf\FrameReflower;

//use Dompdf\Frame;
//use Dompdf\FrameDecorator\Block as BlockFrameDecorator;

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Frame.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS.  'FrameDecorator' .DS.'Block.php');


/**
 * Dummy reflower
 *
 * @package dompdf
 */
class NullFrameReflower extends AbstractFrameReflower
{

    /**
     * NullFrameReflower constructor.
     * @param Frame $frame
     */
    function __construct(Frame $frame)
    {
        parent::__construct($frame);
    }

    /**
     * @param BlockFrameDecorator|null $block
     */
    function reflow(BlockFrameDecorator $block = null)
    {
        return;
    }

}
