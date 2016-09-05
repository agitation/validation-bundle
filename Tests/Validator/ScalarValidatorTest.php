<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Validator;

use Agit\BaseBundle\Validation\ScalarValidator;

class ScalarValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try {
            $success = true;
            $scalarValidator = new ScalarValidator();
            $scalarValidator->validate($value);
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
        $scalarValidator = new ScalarValidator();
        $scalarValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [1],
            [0.12],
            [-0.12],
            ['potato'],
            [false]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [null],
            [[]],
            [new \stdClass()],
            [function () {}]
        ];
    }
}
