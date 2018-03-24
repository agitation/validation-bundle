<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\NotNullValidator;

class NotNullValidatorTest extends \PHPUnit_Framework_TestCase
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
            $notNullValidator = new NotNullValidator();
            $notNullValidator->validate($value);
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
        $notNullValidator = new NotNullValidator();
        $notNullValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [false],
            [true],
            ['potato'],
            [0]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [null]
        ];
    }
}
