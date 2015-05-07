<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\LatitudeValidator;

class LatitudeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $LatitudeValidator = new LatitudeValidator();
            $LatitudeValidator->validate($value);
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
        $LatitudeValidator = new LatitudeValidator();
        $LatitudeValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [0],
            [90],
            [-90],
            [40],
            [-40]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['potato'],
            [91],
            [-91]
        ];
    }
}
