<?php
//namespace Dompdf\Frame;

//use Dompdf\Frame;
//use IteratorAggregate;
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Frame.php');
require_once(JPATH_LIBRARIES .DS. 'Dompdf' .DS. 'src' .DS. 'Frame' .DS. 'FrameListIterator.php');

/**
 * Linked-list IteratorAggregate
 *
 * @access private
 * @package dompdf
 */
class FrameList implements IteratorAggregate
{
    /**
     * @var Frame
     */
    protected $_frame;

    /**
     * @param Frame $frame
     */
    function __construct($frame)
    {
        $this->_frame = $frame;
    }

    /**
     * @return FrameListIterator
     */
    function getIterator()
    {
        return new FrameListIterator($this->_frame);
    }
}
