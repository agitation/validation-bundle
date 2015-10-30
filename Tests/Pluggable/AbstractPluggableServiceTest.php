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
//         $pluggableService = $this->createMockPluggableService(new EventDispatcher(), new ArrayCache());
//         $registrationData = $this->createRegistrationData();
//
//         // as we have just a dummy processor, the output equals the input
//         $this->assertEquals(
//             $registrationData->getRegistrationValues(),
//             $pluggableService->register($registrationData));
    }

    private function createMockPluggableService($eventDispatcher, $cacheProvider)
    {
//         $pluggableService = $this->getMockBuilder('\Agit\CoreBundle\Pluggable\AbstractPluggableService')
//             ->setMethods(['getServiceTag', 'register'])
//             ->getMock();
//
//         $pluggableService->expects($this->any())
//             ->method('getServiceTag')
//             ->will($this->returnValue('agit.test.pluggable'));
//
//         $pluggableService->expects($this->any())
//             ->method('register')
//             ->will($this->returnValue((object)['foo' => 'bar']));
//
//         $pluggableService->setEventDispatcher($eventDispatcher);
//
//         return $pluggableService;
    }

    private function createRegistrationData()
    {
//         $registrationData = new RegistrationData();
//         $registrationData->set('foo', 'bar');
//
//         return $registrationData;
    }
}
