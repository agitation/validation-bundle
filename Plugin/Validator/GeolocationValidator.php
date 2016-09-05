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
 * @ObjectPlugin(tag="agit.validation", id="geolocation")
 */
class GeolocationValidator extends AbstractValidator
{
    public function validate($value)
    {
        try {
            $this->getValidator('array')->validate($value, 2);

            $lat = reset($value);
            $lon = end($value);

            $this->getValidator('latitude')->validate($lat);
            $this->getValidator('longitude')->validate($lon);

            if ($lat > -2 && $lat < 2 && $lon > -2 && $lon < 2) {
                throw new InvalidValueException(Translate::t("The location is off-shore."));
            }
        } catch (InvalidValueException $e) {
            throw new InvalidValueException(sprintf(Translate::t("The geographical location is invalid: %s"), $e->getMessage()));
        }
    }
}
