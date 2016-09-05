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

class NotRegexValidator extends AbstractValidator
{
    public function validate($value, $regex = '|*|')
    {
        if (preg_match($regex, $value)) {
            throw new InvalidValueException(Translate::t("The value doesn’t match the required pattern."));
        }
    }
}
