<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\ArrayValidator;

class ArrayValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $minLength, $maxLength)
    {
        try
        {
            $success = true;
            $arrayValidator = new ArrayValidator();
            $arrayValidator->validate($value, $minLength, $maxLength);
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
    public function testValidateBad($value, $minLength, $maxLength)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $arrayValidator = new ArrayValidator();
        $arrayValidator->validate($value, $minLength, $maxLength);
    }

    public function providerTestValidateGood()
    {
        return [
            [['1','2','3','4','5','6'], null, null],
            [['1','2','3','4','5','6'], 6, 10],
            [['1','2','3','4','5','6'], 3, 6],
            [[], null, null]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['foo', null, null],                    // strings are not allowed
            [15, null, null],                       // same with integers
            [['1','2','3','4'], 5, null, false],    // too short
            [['1','2','3','4'], null, 3, false],    // too long
            [new \stdClass(), null, null]           // and objects
        ];
    }
}
