<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Validator;

use Agit\BaseBundle\Validation\IntegerValidator;

class IntegerValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $min = null, $max = null)
    {
        try {
            $success = true;
            $integerValidator = new IntegerValidator();
            $integerValidator->validate($value, $min, $max);
        } catch (\Exception $e) {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     */
    public function testValidateBad($value, $min = null, $max = null)
    {
        $this->setExpectedException('\Agit\BaseBundle\Exception\InvalidValueException');
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
