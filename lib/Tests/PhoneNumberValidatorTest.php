<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\PhoneNumberValidator;
use Agit\ValidationBundle\Validator\RegexValidator;

class PhoneNumberValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try {
            $success = true;
            $phoneNumberValidator = new PhoneNumberValidator();
            $phoneNumberValidator->setValidator('regex', new RegexValidator());
            $phoneNumberValidator->validate($value);
        } catch (\Exception $e) {
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
        $phoneNumberValidator = new PhoneNumberValidator();
        $phoneNumberValidator->setValidator('regex', new RegexValidator());
        $phoneNumberValidator->validate($value);
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
