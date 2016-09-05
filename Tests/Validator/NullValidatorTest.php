<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Validator;

use Agit\BaseBundle\Validation\NullValidator;

class NullValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try {
            $success = true;
            $nullValidator = new NullValidator();
            $nullValidator->validate($value);
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
