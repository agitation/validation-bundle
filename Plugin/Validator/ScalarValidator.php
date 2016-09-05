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
 * @ObjectPlugin(tag="agit.validation", id="scalar")
 */
class ScalarValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (! is_scalar($value)) {
            throw new InvalidValueException(Translate::t("The value must be scalar."));
        }
    }
}
