<?php
declare(strict_types=1);
/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\ArrayValidator;

class ArrayValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     * @param mixed $value
     * @param mixed $minLength
     * @param mixed $maxLength
     */
    public function testValidateGood($value, $minLength, $maxLength)
    {
        try
        {
            $success = true;
            $arrayValidator = new ArrayValidator();
            $arrayValidator->validate($value, $minLength, $maxLength);
        }
        catch (\Exception $e)
        {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     * @param mixed $value
     * @param mixed $minLength
     * @param mixed $maxLength
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
            [['1', '2', '3', '4', '5', '6'], null, null],
            [['1', '2', '3', '4', '5', '6'], 6, 10],
            [['1', '2', '3', '4', '5', '6'], 3, 6],
            [[], null, null]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['foo', null, null],                    // strings are not allowed
            [15, null, null],                       // same with integers
            [['1', '2', '3', '4'], 5, null, false],    // too short
            [['1', '2', '3', '4'], null, 3, false],    // too long
            [new \stdClass(), null, null]           // and objects
        ];
    }
}
