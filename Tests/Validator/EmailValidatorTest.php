<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\EmailValidator;

class EmailValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $EmailValidator = new EmailValidator();
            $EmailValidator->validate($value);
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
        $EmailValidator = new EmailValidator();
        $EmailValidator->validate($value);
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
