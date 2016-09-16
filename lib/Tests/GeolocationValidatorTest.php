<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\ArrayValidator;
use Agit\ValidationBundle\Validator\GeolocationValidator;
use Agit\ValidationBundle\Validator\LatitudeValidator;
use Agit\ValidationBundle\Validator\LongitudeValidator;

class GeolocationValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try {
            $success = true;
            $geolocationValidator = new GeolocationValidator();
            $geolocationValidator->setValidator('array', new ArrayValidator());
            $geolocationValidator->setValidator('longitude', new LongitudeValidator());
            $geolocationValidator->setValidator('latitude', new LatitudeValidator());
            $geolocationValidator->validate($value);
        } catch (\Exception $e) {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     */
    public function testValidateBad($value)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $geolocationValidator = new GeolocationValidator();
        $geolocationValidator->setValidator('array', new ArrayValidator());
        $geolocationValidator->setValidator('longitude', new LongitudeValidator());
        $geolocationValidator->setValidator('latitude', new LatitudeValidator());
        $geolocationValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [[12.232343534524352, 12.0345345345343]],
            [[1, 50]],
            [[50, -1]]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [10],
            [[100, 100]],
            [[80, 200]],
            [[10, 'potato']],
            [[1, 1]], // this must be rejected, because it is obviously not on land
        ];
    }
}
