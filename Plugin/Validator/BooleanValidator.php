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
 * @ObjectPlugin(tag="agit.validation", id="boolean")
 */
class BooleanValidator extends AbstractValidator
{
    public function validate($value, $acceptNull = false)
    {
        if ((! is_bool($value) && ! $acceptNull) || ($acceptNull && $value !== null)) {
            throw new InvalidValueException(Translate::t("The value must be a boolean."));
        }
    }
}
