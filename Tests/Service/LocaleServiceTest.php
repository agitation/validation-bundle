<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Tests\Intl;

use Agit\IntlBundle\Service\LocaleService;

class LocaleServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefaultLocale()
    {
        $LocaleService = $this->createLocaleService();
        $this->assertEquals('en_GB', $LocaleService->getDefaultLocale());
    }

    public function testGetAvailableLocales()
    {
        $LocaleService = $this->createLocaleService();
        $this->assertEquals(['en_GB', 'de_DE'], $LocaleService->getAvailableLocales());
    }

    public function testSetLocale()
    {
        $LocaleService = $this->createLocaleService();
        $LocaleService->setLocale('de_DE');
        $this->assertEquals('de_DE', $LocaleService->getLocale());
    }


    public function testSetLocaleThrowsException()
    {
        $this->setExpectedException('\Agit\CoreBundle\Exception\InternalErrorException');
        $LocaleService = $this->createLocaleService();
        $LocaleService->setLocale('nl_NL');
    }

    private function createLocaleService()
    {
        return new LocaleService(['en_GB', 'de_DE'], '/tmp/does/not/exist', 'foobar');
    }
}
