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
 * @ObjectPlugin(tag="agit.validation", id="numeric")
 */
class NumericValidator extends AbstractValidator
{
    public function validate($value, $positiveInt = true)
    {
        if (! is_numeric($value)) {
            throw new InvalidValueException(Translate::t("The value must be numeric."));
        }

        if ($positiveInt === true && preg_match('|[^\d]|', $value)) {
            throw new InvalidValueException(Translate::t("The value must be a positive integer number."));
        }
    }
}
