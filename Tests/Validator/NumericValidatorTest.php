<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Validator;

use Agit\BaseBundle\Validation\NumericValidator;

class NumericValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $positiveInt = true)
    {
        try {
            $success = true;
            $numericValidator = new NumericValidator();
            $numericValidator->validate($value, $positiveInt);
        } catch (\Exception $e) {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     */
    public function testValidateBad($value, $positiveInt = true)
    {
        $this->setExpectedException('\Agit\BaseBundle\Exception\InvalidValueException');
        $numericValidator = new NumericValidator();
        $numericValidator->validate($value, $positiveInt);
    }

    public function providerTestValidateGood()
    {
        return [
            [12],
            [-15, false],
            [-.01, false],
            ['12'],
            ['-15', false]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [false],
            ['potato'],
            [new \stdClass()],
            [-10, true]
        ];
    }
}
