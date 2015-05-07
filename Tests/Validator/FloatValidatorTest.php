<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\FloatValidator;

class FloatValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $min = null, $max = null)
    {
        try
        {
            $success = true;
            $FloatValidator = new FloatValidator();
            $FloatValidator->validate($value, $min = null, $max = null);
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
    public function testValidateBad($value, $min, $max)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $FloatValidator = new FloatValidator();
        $FloatValidator->validate($value, $min, $max);
    }

    public function providerTestValidateGood()
    {
        return [
            [.12, 0, 1],
            [-.12, -1, 0],
            [0, null, null],
            [1, 0, 2]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['foo', null, null],
            [.1, .2, .3],
            [.1, 3, 2]
        ];
    }
}
