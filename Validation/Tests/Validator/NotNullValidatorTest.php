<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\NotNullValidator;

class NotNullValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value)
    {
        try
        {
            $success = true;
            $notNullValidator = new NotNullValidator();
            $notNullValidator->validate($value);
        }
        catch(\Exception $e)
        {
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
        $notNullValidator = new NotNullValidator();
        $notNullValidator->validate($value);
    }

    public function providerTestValidateGood()
    {
        return [
            [false],
            [true],
            ['potato'],
            [0]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            [null]
        ];
    }
}
