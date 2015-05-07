<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\ObjectValidator;

class ObjectValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, array $properties = [], $onlyGivenProperties = true)
    {
        try
        {
            $success = true;
            $ObjectValidator = new ObjectValidator();
            $ObjectValidator->validate($value, $properties, $onlyGivenProperties);
        }
        catch(\Exception $e)
        {
            p($e->getMEssage());
            $success = false;
        }

        $this->assertTrue($success);
    }

    /**
     * @dataProvider providerTestValidateBad
     */
    public function testValidateBad($value, array $properties = [], $onlyGivenProperties = true)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $ObjectValidator = new ObjectValidator();
        $ObjectValidator->validate($value, $properties, $onlyGivenProperties);
    }

    public function providerTestValidateGood()
    {
        return [
            [(object)[]],
            [new \Exception()],
            [(object)['foo'=>'bar'], ['foo']],
            [(object)['foo'=>'bar', 'foo2'=>'bar2'], ['foo'], false],
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [[]],
            [15],
            [(object)['foo'=>'bar'], ['bar']],
            [(object)['foo'=>'bar', 'foo2'=>'bar2'], ['foo'], true],
        ];
    }
}
