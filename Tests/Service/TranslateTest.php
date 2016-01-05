<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CommonBundle\Tests\Intl;

use Agit\IntlBundle\Translate;

class TranslateTest extends \PHPUnit_Framework_TestCase
{
    public function testT()
    {
        // without a locale and a textdomain loaded,
        // the translation service should just return the original string.
        $this->assertEquals('foobar', Translate::t('foobar'));
    }

    public function testX()
    {
        // without a locale and a textdomain loaded,
        // the translation service should just return the original string.
        $this->assertEquals('foobar', Translate::x('foobar', 'foo'));
    }

    public function testN()
    {
        $this->assertEquals('%s cars', Translate::n('%s car', '%s cars', 0));
        $this->assertEquals('%s car', Translate::n('%s car', '%s cars', 1));
        $this->assertEquals('%s cars', Translate::n('%s car', '%s cars', 2));
    }

    /**
     * @dataProvider providerTestMultilangStringToObject
     */
    public function testMultilangStringToObject($string, $expected)
    {
        $this->assertEquals($expected, Translate::multilangStringToArray($string));

    }

    /**
     * @dataProvider providerTestU
     */
    public function testU($string, $locale, $expected)
    {
        $this->assertEquals($expected, Translate::u($string, $locale));
    }

    public function providerTestMultilangStringToObject()
    {
        return [
            ['[:de]irgendwas[:en]something', ['de' => 'irgendwas', 'en'=>'something']],
            ['[:de][:en]something', ['de' => '', 'en'=>'something']],
            ['[de]irgendwas[:en]something', []],
            ['[deirgendwas[:en]something', []],
            ['[:deirgendwas[:en]something', []],
            ['something', []]
        ];
    }

    public function providerTestU()
    {
        return [
            ['[:de]irgendwas[:en]something', 'de_DE', 'irgendwas'],
            ['[:de]irgendwas', 'de_DE', 'irgendwas'],
            ['[:de][:en]something', 'de_DE', ''],
            ['[de]irgendwas[:en]something', 'de_DE', '[de]irgendwas[:en]something'],
            ['[:de]irgendwas[:en]something', 'fr_FR', 'something'],
            ['[:deirgendwas[:en]something', 'en_GB', '[:deirgendwas[:en]something'],
            ['something', 'de_DE', 'something'],
        ];
    }
}
