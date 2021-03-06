<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\LongitudeValidator;

class LongitudeValidatorTest extends \PHPUnit_Framework_TestCase
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
            $longitudeValidator = new LongitudeValidator();
            $longitudeValidator->validate($value);
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
        $longitudeValidator = new LongitudeValidator();
        $longitudeValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [0],
            [180],
            [-180],
            [90],
            [-90]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['potato'],
            [181],
            [-181]
        ];
    }
}
