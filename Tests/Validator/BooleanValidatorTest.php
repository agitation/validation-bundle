<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\BooleanValidator;

class BooleanValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $allowNull)
    {
        try
        {
            $success = true;
            $booleanValidator = new BooleanValidator();
            $booleanValidator->validate($value, $allowNull);
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
    public function testValidateBad($value, $allowNull)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $booleanValidator = new BooleanValidator();
        $booleanValidator->validate($value, $allowNull);
    }

    public function providerTestValidateGood()
    {
        return [
            [true, false],
            [false, false],
            [null, true]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['foo', false],
            [15, false],
            [null, false]
        ];
    }
}
