<?php
/**
 * @package    agitation/cron
 * @link       http://github.com/agitation/AgitCronBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CronBundle\Tests\Cron;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Agit\CronBundle\Cron\CronService;

class CronServiceIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testEventRegistration()
    {
        $CronAwareService = $this->getMockBuilder('\Agit\CronBundle\Cron\CronAwareInterface')
            ->setMethods(['cronjobRegistration', 'cronjobExecute'])
            ->getMock();

        $CronAwareService->expects($this->any())
            ->method('cronjobExecute')
            ->will($this->throwException(new \Exception("Cronjob execution triggered.")));

        $CronService = new CronService(new EventDispatcher());
        $CronService->setDate(new \DateTime("2015-09-30 12:15"));

        // usually, cronjob registration is triggered by run(),
        // but here we must call it directly to have our service registered
        $CronService->registerCronjob($CronAwareService, '* * * * *');

        try
        {
            $CronService->run();
        }
        catch(\Exception $e)
        {
            $this->assertEquals("Cronjob execution triggered.", $e->getMessage());
        }
    }
}
