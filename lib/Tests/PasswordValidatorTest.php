<?php
declare(strict_types=1);
/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\PasswordValidator;

class PasswordValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     * @param mixed      $value
     * @param null|mixed $value2
     */
    public function testValidateGood($value, $value2 = null)
    {
        try
        {
            $success = true;
            $passwordValidator = new PasswordValidator();
            $passwordValidator->validate($value, $value2);
        }
        catch (\Exception $e)
        {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     * @param mixed      $value
     * @param null|mixed $value2
     */
    public function testValidateBad($value, $value2 = null)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $passwordValidator = new PasswordValidator();
        $passwordValidator->validate($value, $value2);
    }

    public function providerTestValidateGood()
    {
        return [
            ['abcABC12'],
            ['ABc-S2x4'],
            ['A4/[]c3x', 'A4/[]c3x']
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['ABc-S2x4', 'Abc12345'],   // passwords don't match
            ['Abc123'],                 // too short
            ['abcdefgh'],               // no numbers
            ['12345678'],               // no letters
            ['<Abc123>'],               // invalid characters
        ];
    }
}
