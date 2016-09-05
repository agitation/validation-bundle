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
 * @ObjectPlugin(tag="agit.validation", id="multiSelection")
 */
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
