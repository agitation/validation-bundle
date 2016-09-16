<?php

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\NotRegexValidator;

class NotRegexValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $regex)
    {
        try {
            $success = true;
            $notRegexValidator = new NotRegexValidator();
            $notRegexValidator->validate($value, $regex);
        } catch (\Exception $e) {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     */
    public function testValidateBad($value, $regex)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $notRegexValidator = new NotRegexValidator();
        $notRegexValidator->validate($value, $regex);
    }

    public function providerTestValidateGood()
    {
        return [
            ['abc0', '|^[a-z]*$|'],
            ['abc', '|\d|'],
            [mb_convert_encoding('öäü', 'ISO-8859-1', 'UTF-8'), '|\pL|u']
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['abc', '|^[a-z]*$|'],
            ['20', '/\d$/'],
            ['öäü', '|\pL|u']
        ];
    }
}
