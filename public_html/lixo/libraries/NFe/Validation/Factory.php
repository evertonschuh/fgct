<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

//namespace Respect\Validation;

//use ReflectionClass;
//use Respect\Validation\Exceptions\ComponentException;
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'Validation' . DS . 'Exceptions' . DS . 'ComponentException.php');

class RFactory
{
    protected $rulePrefixes = array( JPATH_LIBRARIES . DS . 'NFe'. DS . 'Validation' . DS . 'Rules' . DS );

    public function getRulePrefixes()
    {
        return $this->rulePrefixes;
    }

    private function filterRulePrefix($rulePrefix)
    {
        $namespaceSeparator = DS;
        $rulePrefix = rtrim($rulePrefix, $namespaceSeparator);

        return $rulePrefix.$namespaceSeparator;
    }

    public function appendRulePrefix($rulePrefix)
    {
        array_push($this->rulePrefixes, $this->filterRulePrefix($rulePrefix));
    }

    public function prependRulePrefix($rulePrefix)
    {
        array_unshift($this->rulePrefixes, $this->filterRulePrefix($rulePrefix));
    }

    public function rule($ruleName, array $arguments = array())
    {
        if ($ruleName instanceof Validatable) {
            return $ruleName;
        }

        foreach ($this->getRulePrefixes() as $prefix) {
            $className = $prefix.ucfirst($ruleName);
			
			if(file_exists($className). '.php')
				require_once($className . '.php');
			
            if (!class_exists(ucfirst($ruleName))) {
                continue;
            }
			
            $reflection = new ReflectionClass(ucfirst($ruleName));
            if (!$reflection->isSubclassOf('Validatable')) {
                throw new ComponentException(sprintf('"%s" is not a valid respect rule', $className));
            }

            return $reflection->newInstanceArgs($arguments);
        }

        throw new ComponentException(sprintf('"%s" is not a valid rule name', $ruleName));
    }
}
