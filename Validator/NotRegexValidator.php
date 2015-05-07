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

class NotRegexValidator extends AbstractValidator
{
    public function validate($value, $regex = '|*|')
    {
        if (preg_match($regex, $value))
            throw new InvalidValueException($this->translate->t("The value doesn’t match the required pattern."));
    }
}