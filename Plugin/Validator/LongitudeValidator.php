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
 * @ObjectPlugin(tag="agit.validation", id="longitude")
 */
class LongitudeValidator extends AbstractValidator
{
    public function validate($value)
    {
        if ((! is_float($value) && ! is_int($value)) || $value < -180 || $value > 180) {
            throw new InvalidValueException(Translate::t("The longitude is invalid."));
        }
    }
}
