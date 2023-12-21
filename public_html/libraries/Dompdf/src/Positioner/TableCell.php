<?php
/**
 * @package dompdf
 * @link    http://dompdf.github.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

//namespace Dompdf\Positioner;

//use Dompdf\FrameDecorator\AbstractFrameDecorator;
//use Dompdf\FrameDecorator\Table;

require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'FrameDecorator' .DS. 'AbstractFrameDecorator.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'FrameDecorator' .DS. 'Table.php');


/**
 * Positions table cells
 *
 * @package dompdf
 */
class TableCellPositioner extends AbstractPositioner
{

    /**
     * @param AbstractFrameDecorator $frame
     */
    function position(AbstractFrameDecorator $frame)
    {
        $table = TableFrameDecorator::find_parent_table($frame);
        $cellmap = $table->get_cellmap();
        $frame->set_position($cellmap->get_frame_position($frame));
    }
}
