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
 * @ObjectPlugin(tag="agit.validation", id="geolocation")
 */
class GeolocationValidator extends AbstractValidator
{
    public function validate($value)
    {
        try
        {
            $this->getValidator('array')->validate($value, 2);

            $lat = reset($value);
            $lon = end($value);

            $this->getValidator('latitude')->validate($lat);
            $this->getValidator('longitude')->validate($lon);

            if ($lat > -2 && $lat < 2 && $lon > -2 && $lon < 2)
                throw new InvalidValueException($this->translate->t("The location is off-shore."));
        }
        catch (InvalidValueException $e)
        {
            throw new InvalidValueException(sprintf($this->translate->t("The geographical location is invalid: %s"), $e->getMessage()));
        }
    }
}
