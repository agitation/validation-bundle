<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Tests\Pluggable;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\Common\Cache\ArrayCache;
use Agit\CoreBundle\Pluggable\AbstractPluggableService;
use Agit\CoreBundle\Pluggable\RegistrationData;

class AbstractCacheAwarePluggableServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testWarmUpValues()
    {
//         $CacheProvider = new ArrayCache();
//         $EventDispatcher = new EventDispatcher();
//         $PluggableService = $this->createMockPluggableService($EventDispatcher, $CacheProvider);
//
//         // as we can't interact with the EventDispatcher during the warm-up,
//         // we simply set the extension manually and call warmUp afterwards.
//         $PluggableService->register($this->createRegistrationData());
//         $PluggableService->warmUp('/we/dont/care');
//
//         $this->assertEquals(
//             [(object)['foo' => 'bar']],
//             $CacheProvider->fetch('agit.test.pluggable'));
    }

    private function createMockPluggableService($EventDispatcher, $CacheProvider)
    {
//         $PluggableService = $this->getMockBuilder('\Agit\CoreBundle\Pluggable\AbstractCacheAwarePluggableService')
//             ->setMethods(['getServiceTag'])
//             ->getMock();
//
//         $PluggableService->expects($this->any())
//             ->method('getServiceTag')
//             ->will($this->returnValue('agit.test.pluggable'));
//
//         $PluggableService->setEventDispatcher($EventDispatcher);
//         $PluggableService->setCacheProvider($CacheProvider);
//
//         return $PluggableService;
    }

    private function createRegistrationData()
    {
//         $RegistrationData = new RegistrationData();
//         $RegistrationData->set('foo', 'bar');
//
//         return $RegistrationData;
    }
}
