<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\LongitudeValidator;

class LongitudeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $LongitudeValidator = new LongitudeValidator();
            $LongitudeValidator->validate($value);
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
        $LongitudeValidator = new LongitudeValidator();
        $LongitudeValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [0],
            [180],
            [-180],
            [90],
            [-90]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['potato'],
            [181],
            [-181]
        ];
    }
}
