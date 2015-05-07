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

class AbstractPluggableServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterPluginWorks()
    {
//         $PluggableService = $this->createMockPluggableService(new EventDispatcher(), new ArrayCache());
//         $RegistrationData = $this->createRegistrationData();
//
//         // as we have just a dummy processor, the output equals the input
//         $this->assertEquals(
//             $RegistrationData->getRegistrationValues(),
//             $PluggableService->register($RegistrationData));
    }

    private function createMockPluggableService($EventDispatcher, $CacheProvider)
    {
//         $PluggableService = $this->getMockBuilder('\Agit\CoreBundle\Pluggable\AbstractPluggableService')
//             ->setMethods(['getServiceTag', 'register'])
//             ->getMock();
//
//         $PluggableService->expects($this->any())
//             ->method('getServiceTag')
//             ->will($this->returnValue('agit.test.pluggable'));
//
//         $PluggableService->expects($this->any())
//             ->method('register')
//             ->will($this->returnValue((object)['foo' => 'bar']));
//
//         $PluggableService->setEventDispatcher($EventDispatcher);
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
