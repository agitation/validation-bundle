<?php
/**
 * @package    agitation/cron
 * @link       http://github.com/agitation/AgitCronBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CronBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Agit\CronBundle\Cron\CronService;
use Agit\CronBundle\Cron\CronAwareInterface;

class CronjobRegistrationEvent extends Event
{
    private $cronService;

    public function __construct(CronService $cronService)
    {
        $this->cronService = $cronService;
    }

    public function registerCronjob(CronAwareInterface $cronAwareService, $cronTime)
    {
        $this->cronService->parseCronTime($cronTime);
        $this->cronService->registerCronjob($cronAwareService, $cronTime);
    }
}
