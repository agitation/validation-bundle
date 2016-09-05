<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Plugin;

use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Pluggable\Object\ObjectPlugin;
use Agit\BaseBundle\Tool\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="float")
 */
class FloatValidator extends AbstractValidator
{
    public function validate($value, $min = null, $max = null)
    {
        if (!is_float($value) && !is_int($value))
            throw new InvalidValueException(Translate::t("The value must be a number."));

        if ((is_float($min) || is_int($min)) && $value < $min)
            throw new InvalidValueException(sprintf(Translate::t("The value is too low, it must be higher than %s."), $min));

        if ((is_float($max) || is_int($max)) && $value > $max)
            throw new InvalidValueException(sprintf(Translate::t("The value is too high, it must be lower than %s."), $max));
    }
}
