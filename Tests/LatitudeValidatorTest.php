<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\LatitudeValidator;

class LatitudeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try {
            $success = true;
            $latitudeValidator = new LatitudeValidator();
            $latitudeValidator->validate($value);
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
