<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\ValidationBundle\Exception\InvalidValueException;

class BooleanValidator extends AbstractValidator
{
    public function validate($value, $acceptNull = false)
    {
        if ( (!is_bool($value) && !$acceptNull) || ($acceptNull && $value !== null))
            throw new InvalidValueException($this->translate->t("The value must be a boolean."));
    }
}