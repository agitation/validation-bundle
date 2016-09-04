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
use Agit\BaseBundle\Tool\Translate;

/**
 * @ObjectPlugin(tag="agit.validation", id="latitude")
 */
class LatitudeValidator extends AbstractValidator
{
    public function validate($value)
    {
        if ((!is_float($value) && !is_int($value)) || $value <  -90 || $value > 90)
            throw new InvalidValueException(Translate::t("The latitude is invalid."));
    }

}
