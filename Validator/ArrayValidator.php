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

class ArrayValidator extends AbstractValidator
{
    public function validate($value, $minLength = null, $maxLength = null)
    {
        if (!is_array($value))
            throw new InvalidValueException($this->translate->t("The value must be an array."));

        if (is_int($minLength) && count($value) < $minLength)
            throw new InvalidValueException(sprintf($this->translate->t("The list is too short, it must have at least %s elements."), $minLength));

        if (is_int($maxLength) && count($value) > $maxLength)
            throw new InvalidValueException(sprintf($this->translate->t("The list is too long, it must have at most %s elements."), $maxLength));
    }
}