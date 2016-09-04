<?php
/**
 * @package    agitation/intl
 * @link       http://github.com/agitation/AgitIntlBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Tests\Intl;

use Agit\IntlBundle\Service\LocaleService;

class LocaleServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDefaultLocale()
    {
        $localeService = $this->createLocaleService();
        $this->assertEquals('en_GB', $localeService->getDefaultLocale());
    }

    public function testGetAvailableLocales()
    {
        $localeService = $this->createLocaleService();
        $this->assertEquals(['en_GB', 'de_DE'], $localeService->getAvailableLocales());
    }

    public function testSetLocale()
    {
        $localeService = $this->createLocaleService();
        $localeService->setLocale('de_DE');
        $this->assertEquals('de_DE', $localeService->getLocale());
    }


    public function testSetLocaleThrowsException()
    {
        $this->setExpectedException('\Agit\BaseBundle\Exception\InternalErrorException');
        $localeService = $this->createLocaleService();
        $localeService->setLocale('nl_NL');
    }

    private function createLocaleService()
    {
        return new LocaleService(['en_GB', 'de_DE'], '/tmp/does/not/exist', 'foobar');
    }
}
