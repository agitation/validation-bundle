<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\IntegerValidator;

class IntegerValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $min = null, $max = null)
    {
        try
        {
            $success = true;
            $IntegerValidator = new IntegerValidator();
            $IntegerValidator->validate($value, $min, $max);
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
    public function testValidateBad($value, $min = null, $max = null)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $IntegerValidator = new IntegerValidator();
        $IntegerValidator->validate($value, $min, $max);
    }

    public function providerTestValidateGood()
    {
        return [
            [1],
            [12],
            [5, 4, 6],
            [5, null, 5],
            [1, 1, null]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['foo', null, null],
            [.10, null, null],
            [1, 2, null],
            [2, null, 1]
        ];
    }
}
