<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Validator;

use Agit\BaseBundle\Plugin\Validator\BooleanValidator;

class BooleanValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $allowNull)
    {
        try {
            $success = true;
            $booleanValidator = new BooleanValidator();
            $booleanValidator->validate($value, $allowNull);
        } catch (\Exception $e) {
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     */
    public function testValidateBad($value, $allowNull)
    {
        $this->setExpectedException('\Agit\BaseBundle\Exception\InvalidValueException');
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
