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

class NullValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (! is_null($value)) {
            throw new InvalidValueException(Translate::t("The value must be NULL."));
        }
    }
}
