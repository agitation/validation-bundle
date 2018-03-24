<?php
declare(strict_types=1);

/*
 * @package    agitation/validation-bundle
 * @link       http://github.com/agitation/validation-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\ObjectValidator;

class ObjectValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     * @param mixed $value
     * @param mixed $onlyGivenProperties
     */
    public function testValidateGood($value, array $properties = [], $onlyGivenProperties = true)
    {
        try
        {
            $success = true;
            $objectValidator = new ObjectValidator();
            $objectValidator->validate($value, $properties, $onlyGivenProperties);
        }
        catch (\Exception $e)
        {
            p($e->getMEssage());
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     * @param mixed $value
     * @param mixed $onlyGivenProperties
     */
    public function testValidateBad($value, array $properties = [], $onlyGivenProperties = true)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $objectValidator = new ObjectValidator();
        $objectValidator->validate($value, $properties, $onlyGivenProperties);
    }

    public function providerTestValidateGood()
    {
        return [
            [(object) []],
            [new \Exception()],
            [(object) ['foo' => 'bar'], ['foo']],
            [(object) ['foo' => 'bar', 'foo2' => 'bar2'], ['foo'], false],
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [[]],
            [15],
            [(object) ['foo' => 'bar'], ['bar']],
            [(object) ['foo' => 'bar', 'foo2' => 'bar2'], ['foo'], true],
        ];
    }
}
