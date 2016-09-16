<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\FloatValidator;

class FloatValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $min = null, $max = null)
    {
        try {
            $success = true;
            $floatValidator = new FloatValidator();
            $floatValidator->validate($value, $min = null, $max = null);
        } catch (\Exception $e) {
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
        $floatValidator = new FloatValidator();
        $floatValidator->validate($value, $min, $max);
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
