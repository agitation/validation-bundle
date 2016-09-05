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

class MultiSelectionValidator extends AbstractValidator
{
    public function validate($value, $possibleValues = [])
    {
        if (! is_array($value)) {
            throw new InvalidValueException(Translate::t("The value must be an array."));
        }

        foreach ($value as $val) {
            if (! in_array($val, $possibleValues)) {
                throw new InvalidValueException(sprintf(
                    Translate::t("The value must be one of the following: “%s”."),
                    implode(Translate::x("quotation inside collection", "”, “"),
                    $possibleValues)
                ));
            }
        }
    }
}
