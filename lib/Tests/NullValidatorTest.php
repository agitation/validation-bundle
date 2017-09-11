<?php
declare(strict_types=1);
/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\NullValidator;

class NullValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     * @param mixed $value
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $nullValidator = new NullValidator();
            $nullValidator->validate($value);
        }
        catch (\Exception $e)
        {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     * @param mixed $value
     */
    public function testValidateBad($value)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $nullValidator = new NullValidator();
        $nullValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [null]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [false],
            [true],
            ['potato'],
            [0]
        ];
    }
}
