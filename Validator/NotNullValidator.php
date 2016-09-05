<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\BaseBundle\Tool\Translate;
use Agit\ValidationBundle\Exception\InvalidValueException;

class NotNullValidator extends AbstractValidator
{
    public function validate($value)
    {
        if (is_null($value)) {
            throw new InvalidValueException(Translate::t("The value must not be NULL."));
        }
    }
}
