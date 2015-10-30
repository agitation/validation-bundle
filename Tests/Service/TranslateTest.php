<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Tests\Intl;

use Agit\IntlBundle\Service\Translate;

class TranslateTest extends \PHPUnit_Framework_TestCase
{
    public function testT()
    {
        $translate = new Translate();

        // without a locale and a textdomain loaded,
        // the translation service should just return the original string.
        $this->assertEquals('foobar', $translate->t('foobar'));
    }

    public function testX()
    {
        $translate = new Translate();

        // without a locale and a textdomain loaded,
        // the translation service should just return the original string.
        $this->assertEquals('foobar', $translate->x('foobar', 'foo'));
    }

    public function testN()
    {
        $translate = new Translate();

        $this->assertEquals('%s cars', $translate->n('%s car', '%s cars', 0));
        $this->assertEquals('%s car', $translate->n('%s car', '%s cars', 1));
        $this->assertEquals('%s cars', $translate->n('%s car', '%s cars', 2));
    }

    /**
     * @dataProvider providerTestMultilangStringToObject
     */
    public function testMultilangStringToObject($string, $expected)
    {
        $translate = new Translate();
        $this->assertEquals($expected, $translate->multilangStringToObject($string));

    }

    /**
     * @dataProvider providerTestU
     */
    public function testU($string, $locale, $expected)
    {
        $translate = new Translate();
        $this->assertEquals($expected, $translate->u($string, $locale));
    }

    public function providerTestMultilangStringToObject()
    {
        return [
            ['[:de]irgendwas[:en]something', (object)['de' => 'irgendwas', 'en'=>'something']],
            ['[:de][:en]something', (object)['de' => '', 'en'=>'something']],
            ['[de]irgendwas[:en]something', (object)[]],
            ['[deirgendwas[:en]something', (object)[]],
            ['[:deirgendwas[:en]something', (object)[]],
            ['something', (object)[]]
        ];
    }

    public function providerTestU()
    {
        return [
            ['[:de]irgendwas[:en]something', 'de_DE', 'irgendwas'],
            ['[:de][:en]something', 'de_DE', ''],
            ['[de]irgendwas[:en]something', 'de_DE', '[de]irgendwas[:en]something'],
            ['[:de]irgendwas[:en]something', 'fr_FR', 'something'],
            ['[:deirgendwas[:en]something', 'en_GB', '[:deirgendwas[:en]something'],
            ['something', 'de_DE', 'something'],
        ];
    }
}
