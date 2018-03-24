<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\NumericValidator;

class NumericValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     * @param mixed $value
     * @param mixed $positiveInt
     */
    public function testValidateGood($value, $positiveInt = true)
    {
        try
        {
            $success = true;
            $numericValidator = new NumericValidator();
            $numericValidator->validate($value, $positiveInt);
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
     * @param mixed $positiveInt
     */
    public function testValidateBad($value, $positiveInt = true)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
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
