<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\ScalarValidator;

class ScalarValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $scalarValidator = new ScalarValidator();
            $scalarValidator->validate($value);
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
    public function testValidateBad($value)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $scalarValidator = new ScalarValidator();
        $scalarValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [1],
            [0.12],
            [-0.12],
            ['potato'],
            [false]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [null],
            [[]],
            [new \stdClass()],
            [function(){}]
        ];
    }
}
