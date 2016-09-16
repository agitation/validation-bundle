<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\IntlBundle\Tool\Translate;
use Agit\ValidationBundle\Exception\InvalidValueException;

class FloatValidator extends AbstractValidator
{
    public function validate($value, $min = null, $max = null)
    {
        if (! is_float($value) && ! is_int($value)) {
            throw new InvalidValueException(Translate::t("The value must be a number."));
        }

        if ((is_float($min) || is_int($min)) && $value < $min) {
            throw new InvalidValueException(sprintf(Translate::t("The value is too low, it must be higher than %s."), $min));
        }

        if ((is_float($max) || is_int($max)) && $value > $max) {
            throw new InvalidValueException(sprintf(Translate::t("The value is too high, it must be lower than %s."), $max));
        }
    }
}
