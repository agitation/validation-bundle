<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Validator;

use Agit\BaseBundle\Plugin\Validator\LongitudeValidator;

class LongitudeValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try {
            $success = true;
            $longitudeValidator = new LongitudeValidator();
            $longitudeValidator->validate($value);
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
        $this->setExpectedException('\Agit\BaseBundle\Exception\InvalidValueException');
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
