<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\StringValidator;

class StringValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $minLength, $maxLength, $noCtl)
    {
        try
        {
            $success = true;
            $StringValidator = new StringValidator();
            $StringValidator->validate($value, $minLength, $maxLength, $noCtl);
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
    public function testValidateBad($value, $minLength, $maxLength, $noCtl)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $StringValidator = new StringValidator();
        $StringValidator->validate($value, $minLength, $maxLength, $noCtl);
    }

    public function providerTestValidateGood()
    {
        return [
            ['string', null, null, true],
            ['string', 6, 10, true],
            ['string', 3, 6, true],
            ["\tstring\n", null, null, false]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [['foo'], null, null, false],       // arrays are not allowed
            [15, null, null, false],            // same with integers
            ["foo", 6, 20, false],              // string too short
            ["bar", 1, 2, false],               // string too long
            ["\n", null, null, true],           // noCtl is true, so newline is not allowed
            ["\0", null, null, false]           // null bytes are always evil

        ];
    }
}
