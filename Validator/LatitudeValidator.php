<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Tool\Translate;

class LatitudeValidator extends AbstractValidator
{
    public function validate($value)
    {
        if ((! is_float($value) && ! is_int($value)) || $value <  -90 || $value > 90) {
            throw new InvalidValueException(Translate::t("The latitude is invalid."));
        }
    }
}
