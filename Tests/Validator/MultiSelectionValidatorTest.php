<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\MultiSelectionValidator;

class MultiSelectionValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, array $possibleValues = [])
    {
        try
        {
            $success = true;
            $multiSelectionValidator = new MultiSelectionValidator();
            $multiSelectionValidator->validate($value, $possibleValues);
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
    public function testValidateBad($value, array $possibleValues = [])
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $multiSelectionValidator = new MultiSelectionValidator();
        $multiSelectionValidator->validate($value, $possibleValues);
    }

    public function providerTestValidateGood()
    {
        return [
            [['abc'], ['abc', 'def', 'ghi']],
            [['ghi', 'def'], ['abc', 'def', 'ghi']]
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['abc', ['abc', 'def']],
            [['ghi'], ['abc', 'def']]

        ];
    }
}
