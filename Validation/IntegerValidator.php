<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Validation;

use Agit\BaseBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Tool\Translate;

class IntegerValidator extends AbstractValidator
{
    public function validate($value, $min = null, $max = null)
    {
        if (! is_int($value)) {
            throw new InvalidValueException(Translate::t("The value must be an integer."));
        }

        if (is_int($min) && $value < $min) {
            throw new InvalidValueException(sprintf(Translate::t("The value is too low, it must be higher than %s."), $min));
        }

        if (is_int($max) && $value > $max) {
            throw new InvalidValueException(sprintf(Translate::t("The value is too high, it must be lower than %s."), $max));
        }
    }
}
