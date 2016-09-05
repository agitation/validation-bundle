<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Plugin\Validator;

use Agit\BaseBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Pluggable\Object\ObjectPlugin;
use Agit\BaseBundle\Tool\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="regex")
 */
class RegexValidator extends AbstractValidator
{
    public function validate($value, $regex = '|^.|')
    {
        if (! preg_match($regex, $value)) {
            throw new InvalidValueException(Translate::t("The value doesn't match the required pattern."));
        }
    }
}
