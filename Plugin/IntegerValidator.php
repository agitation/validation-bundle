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
use Agit\PluggableBundle\Strategy\Object\ObjectPlugin;

/**
 * @ObjectPlugin(tag="agit.validation", id="integer")
 */
class IntegerValidator extends AbstractValidator
{
    public function validate($value, $min=null, $max=null)
    {
        if (!is_int($value))
            throw new InvalidValueException($this->translate->t("The value must be an integer."));

        if (is_int($min) && $value < $min)
            throw new InvalidValueException(sprintf($this->translate->t("The value is too low, it must be higher than %s."), $min));

        if (is_int($max) && $value > $max)
            throw new InvalidValueException(sprintf($this->translate->t("The value is too high, it must be lower than %s."), $max));
    }
}
