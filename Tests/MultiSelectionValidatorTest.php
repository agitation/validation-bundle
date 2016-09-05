<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests;

use Agit\ValidationBundle\Validator\MultiSelectionValidator;

class MultiSelectionValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, array $possibleValues = [])
    {
        try {
            $success = true;
            $multiSelectionValidator = new MultiSelectionValidator();
            $multiSelectionValidator->validate($value, $possibleValues);
        } catch (\Exception $e) {
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
