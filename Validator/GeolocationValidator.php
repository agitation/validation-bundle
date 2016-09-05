<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Validator;

use Agit\ValidationBundle\Exception\InvalidValueException;
use Agit\BaseBundle\Tool\Translate;

class GeolocationValidator extends AbstractValidator
{
    private $arrayValidator;

    private $latitudeValidator;

    private $longitudeValidator;

    public function __construct(ArrayValidator $arrayValidator, LatitudeValidator $latitudeValidator, LongitudeValidator $longitudeValidator)
    {
        $this->arrayValidator = $arrayValidator;
        $this->latitudeValidator = $latitudeValidator;
        $this->longitudeValidator = $longitudeValidator;
    }

    public function validate($value)
    {
        try {
            $this->arrayValidator->validate($value, 2);

            $lat = reset($value);
            $lon = end($value);

            $this->latitudeValidator->validate($lat);
            $this->longitudeValidator->validate($lon);

            if ($lat > -2 && $lat < 2 && $lon > -2 && $lon < 2) {
                throw new InvalidValueException(Translate::t("The location is off-shore."));
            }
        } catch (InvalidValueException $e) {
            throw new InvalidValueException(sprintf(Translate::t("The geographical location is invalid: %s"), $e->getMessage()));
        }
    }
}
