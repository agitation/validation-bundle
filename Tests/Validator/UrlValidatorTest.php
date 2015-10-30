<?php
/**
 * @package    agitation/validation
 * @link       http://github.com/agitation/AgitValidationBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\ValidationBundle\Tests\Validator;

use Agit\ValidationBundle\Validator\UrlValidator;

class UrlValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerTestValidateGood
     */
    public function testValidateGood($value, $strict = true)
    {
        try
        {
            $success = true;
            $urlValidator = new UrlValidator();
            $urlValidator->validate($value, $strict);
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
    public function testValidateBad($value, $strict = true)
    {
        $this->setExpectedException('\Agit\ValidationBundle\Exception\InvalidValueException');
        $urlValidator = new UrlValidator();
        $urlValidator->validate($value, $strict);
    }

    public function providerTestValidateGood()
    {
        return [
            ['https://www.example.com'],
            ['https://foobar@example.com', false],
            // ['https://123.example.com/'], // actually, this should validate, but it doesn't, due to a bug in filter_var
            ['https://x123.example.com/'],
            ['https://example.com/foobar'],
            ['https://example.com/foobar?key=value'],
            ['https://example.com/foobar?key=value#goto']
        ];
    }

    public function providerTestValidateBad()
    {
        return [
            ['https://foobar@example.com', true],
            ['www.example.com'],
            ['/path/to/file'],
            ['/path/to/file?key=value']
        ];
    }
}
