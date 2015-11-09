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
use Agit\IntlBundle\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="longitude")
 */
class LongitudeValidator extends AbstractValidator
{
    public function validate($value)
    {
        if ((!is_float($value) && !is_int($value)) || $value < -180 || $value > 180)
            throw new InvalidValueException(Translate::t("The longitude is invalid."));
    }
}
