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

class SelectionValidator extends AbstractValidator
{
    public function validate($value, array $possibleValues = [])
    {
        if (! in_array($value, $possibleValues)) {
            throw new InvalidValueException(sprintf(
                Translate::t("The value must be one of the following: “%s”."),
                implode(
                    Translate::x("quotation inside collection", "”, “"),
                    $possibleValues)
            ));
        }
    }
}
