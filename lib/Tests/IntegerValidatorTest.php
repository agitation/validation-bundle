<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\IntegerValidator;

class IntegerValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     * @param mixed      $value
     * @param null|mixed $min
     * @param null|mixed $max
     */
    public function testValidateGood($value, $min = null, $max = null)
    {
        try
        {
            $success = true;
            $integerValidator = new IntegerValidator();
            $integerValidator->validate($value, $min, $max);
        }
        catch (\Exception $e)
        {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     * @param mixed      $value
     * @param null|mixed $min
     * @param null|mixed $max
     */
    public function testValidateBad($value, $min = null, $max = null)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $integerValidator = new IntegerValidator();
        $integerValidator->validate($value, $min, $max);
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
