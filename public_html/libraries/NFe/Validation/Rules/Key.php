<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

//namespace Respect\Validation\Rules;

//use Respect\Validation\Exceptions\ComponentException;
//use Respect\Validation\Validatable;

require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Validation' . DS . 'Exceptions' . DS . 'ComponentException.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Validation' . DS . 'Validatable.php');
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Validation' . DS . 'Rules' . DS . 'AbstractRelated.php');

class Key extends AbstractRelated
{
    public function __construct($reference, Validatable $referenceValidator = null, $mandatory = true)
    {
        if (!is_scalar($reference) || '' === $reference) {
            throw new ComponentException('Invalid array key name');
        }
        parent::__construct($reference, $referenceValidator, $mandatory);
    }

    public function getReferenceValue($input)
    {
        return $input[$this->reference];
    }

    public function hasReference($input)
    {
        return is_array($input) && array_key_exists($this->reference, $input);
    }
}
