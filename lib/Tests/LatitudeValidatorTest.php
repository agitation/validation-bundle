<?php
declare(strict_types=1);
/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\LatitudeValidator;

class LatitudeValidatorTest extends \PHPUnit_Framework_TestCase
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
            $latitudeValidator = new LatitudeValidator();
            $latitudeValidator->validate($value);
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
        $latitudeValidator = new LatitudeValidator();
        $latitudeValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [0],
            [90],
            [-90],
            [40],
            [-40]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['potato'],
            [91],
            [-91]
        ];
    }
}
