<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\NumericValidator;

class NumericValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $positiveInt = true)
    {
        try
        {
            $success = true;
            $NumericValidator = new NumericValidator();
            $NumericValidator->validate($value, $positiveInt);
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
    public function testValidateBad($value, $positiveInt = true)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $NumericValidator = new NumericValidator();
        $NumericValidator->validate($value, $positiveInt);
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
