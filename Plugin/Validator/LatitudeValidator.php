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
 * @ObjectPlugin(tag="agit.validation", id="latitude")
 */
class LatitudeValidator extends AbstractValidator
{
    public function validate($value)
    {
        if ((! is_float($value) && ! is_int($value)) || $value <  -90 || $value > 90) {
            throw new InvalidValueException(Translate::t("The latitude is invalid."));
        }
    }
}
