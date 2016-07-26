<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\IntlBundle\Tests\Service;

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
}
