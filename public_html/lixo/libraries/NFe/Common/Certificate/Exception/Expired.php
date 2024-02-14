<?php
/*
namespace NFePHP\Common\Certificate\Exception;

use NFePHP\Common\Certificate;
use NFePHP\Common\Exception\ExceptionInterface;
use NFePHP\Common\Exception\RuntimeException;
*/

require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Certificate.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Exception' . DS . 'ExceptionInterface.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Exception' . DS . 'RuntimeException.php');


class Expired extends RuntimeException implements ExceptionInterface
{
    public function __construct(Certificate $certificate)
    {
        $invalidDate = $certificate->getValidFrom()->format('Y-m-d H:i');
        parent::__construct('Certificate invalid from '.$invalidDate, 0, null);
    }
}
