<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\EmailValidator;

class EmailValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try {
            $success = true;
            $emailValidator = new EmailValidator();
            $emailValidator->validate($value);
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
        $emailValidator = new EmailValidator();
        $emailValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            ['john@example.com'],
            ['x@y.z'],
            ['123@example.com']
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['foo'],
            ['I.LOVE.SHOUTING@example.com'],
            ['john@' . str_repeat('a', 300) . '.example.com'],
            ['johnathan.james.the.first.duke.of.johnsbury.is.an.outstanding.lover@example.com'],
            [new \stdClass()]
        ];
    }
}
