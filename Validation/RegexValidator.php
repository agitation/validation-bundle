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

class RegexValidator extends AbstractValidator
{
    public function validate($value, $regex = '|^.|')
    {
        if (! preg_match($regex, $value)) {
            throw new InvalidValueException(Translate::t("The value doesn't match the required pattern."));
        }
    }
}
