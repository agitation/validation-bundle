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

class CronServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerValidCronTimes
     */
    public function testParseCronTime($cronTime, $expectedResult)
    {
        $CronService = new CronService(new EventDispatcher());
        $this->assertEquals($expectedResult, $CronService->parseCronTime($cronTime));
    }

    /**
     * @dataProvider providerInvalidCronTimes
     */
    public function testParseCronTimeException($cronTime)
    {
        $this->setExpectedException('Agit\CoreBundle\Exception\InternalErrorException');

        $CronService = new CronService(new EventDispatcher());
        $CronService->parseCronTime($cronTime);
    }

    /**
     * @dataProvider providerCronAppliesTrue
     */
    public function testCronAppliesTrue($cronTime)
    {
        $CronService = new CronService(new EventDispatcher());
        $CronService->setDate(new \DateTime("2015-09-30 12:15"));

        $this->assertTrue($CronService->cronApplies($cronTime));
    }

    /**
     * @dataProvider providerCronAppliesFalse
     */
    public function testCronAppliesFalse($cronTime)
    {
        $CronService = new CronService(new EventDispatcher());
        $CronService->setDate(new \DateTime("2015-09-30 12:15"));

        $this->assertFalse($CronService->cronApplies($cronTime));
    }

    public function providerValidCronTimes()
    {
        return [
            ['* * * * *', [null, null, null, null, null]],
            ['*/15 * * * *', [[0,15,30,45], null, null, null, null]],
            ['* 6,12,18 * * *', [null, [6, 12, 18], null, null, null]]
        ];
    }

    public function providerInvalidCronTimes()
    {
        return [
            ['* * * *'],
            ['** * * * *'],
            ['* 40 * * *'],
            ['* ,1,2 * * *'],
            ['* 1,2, * * *'],
            ['* -3 * * *'],
            ['* .5 * * *'],
            ['* * 0 * *'],
            ['**/10 * * * *']
        ];
    }

    public function providerCronAppliesTrue()
    {
        return [
            ['* * * * *'],
            ['*/15 * * * *'],
            ['* 6,12,18 * * *'],
            ['*/15 12 30 9 *'],
            ['* * * * 3'],
        ];
    }

    public function providerCronAppliesFalse()
    {
        return [
            ['* * * * 5'],
            ['*/23 * * * *'],
            ['1 2,3 * * 3']
        ];
    }
}
