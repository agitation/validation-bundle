<?php
declare(strict_types=1);
/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\BooleanValidator;

class BooleanValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     * @param mixed $value
     * @param mixed $allowNull
     */
    public function testValidateGood($value, $allowNull)
    {
        try
        {
            $success = true;
            $booleanValidator = new BooleanValidator();
            $booleanValidator->validate($value, $allowNull);
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
     * @param mixed $allowNull
     */
    public function testValidateBad($value, $allowNull)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $booleanValidator = new BooleanValidator();
        $booleanValidator->validate($value, $allowNull);
    }

    public function providerTestValidateGood()
    {
        return [
            [true, false],
            [false, false],
            [null, true]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['foo', false],
            [15, false],
            [null, false]
        ];
    }
}
