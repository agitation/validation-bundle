<?php
declare(strict_types=1);
/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\ScalarValidator;

class ScalarValidatorTest extends \PHPUnit_Framework_TestCase
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
            $scalarValidator = new ScalarValidator();
            $scalarValidator->validate($value);
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
            [function () {
            }]
        ];
    }
}
