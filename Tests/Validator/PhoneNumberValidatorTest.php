<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\PhoneNumberValidator;
use Agit\ValidationBundle\Validator\RegexValidator;

class PhoneNumberValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $PhoneNumberValidator = new PhoneNumberValidator();
            $PhoneNumberValidator->setValidator('regex', new RegexValidator());
            $PhoneNumberValidator->validate($value);
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
        $PhoneNumberValidator = new PhoneNumberValidator();
        $PhoneNumberValidator->setValidator('regex', new RegexValidator());
        $PhoneNumberValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            ['+49-234-23423-3345'],
            ['+235-122-23423345'],
            ['+235-1220-2323']
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['49-234-23423-345'],
            ['00235-1220-23423345'],
            ['+235-1220-23423-24343-35345']
        ];
    }
}
