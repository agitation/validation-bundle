<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\NotRegexValidator;

class NotRegexValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $regex)
    {
        try
        {
            $success = true;
            $notRegexValidator = new NotRegexValidator();
            $notRegexValidator->validate($value, $regex);
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
