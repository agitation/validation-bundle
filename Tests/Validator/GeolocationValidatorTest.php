<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\GeolocationValidator;
use Agit\ValidationBundle\Validator\ArrayValidator;
use Agit\ValidationBundle\Validator\LongitudeValidator;
use Agit\ValidationBundle\Validator\LatitudeValidator;

class GeolocationValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $GeolocationValidator = new GeolocationValidator();
            $GeolocationValidator->setValidator('array', new ArrayValidator());
            $GeolocationValidator->setValidator('longitude', new LongitudeValidator());
            $GeolocationValidator->setValidator('latitude', new LatitudeValidator());
            $GeolocationValidator->validate($value);
        }
        catch(\Exception $e)
        {
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
        $GeolocationValidator = new GeolocationValidator();
        $GeolocationValidator->setValidator('array', new ArrayValidator());
        $GeolocationValidator->setValidator('longitude', new LongitudeValidator());
        $GeolocationValidator->setValidator('latitude', new LatitudeValidator());
        $GeolocationValidator->validate($value);
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
