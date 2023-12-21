<?php

//namespace NFePHP\Common\Exception;

/**
 * @category   NFePHP
 * @package    NFePHP\Common\Exception
 * @copyright  Copyright (c) 2008-2014
 * @license    http://www.gnu.org/licenses/lesser.html LGPL v3
 * @author     Roberto L. Machado <linux.rlm at gmail dot com>
 * @link       http://github.com/nfephp-org/nfephp for the canonical source repository
 */


require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Common' . DS . 'Exception' . DS . 'ExceptionInterface.php');

class RuntimeException1 extends \RuntimeException implements ExceptionInterface
{
}
