<?php
/**
 * @package    agitation/cron
 * @link       http://github.com/agitation/AgitCronBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CronBundle\Cron;

use Agit\CronBundle\Event\CronjobRegistrationEvent;

interface CronAwareInterface
{
    public function cronjobRegistration(CronjobRegistrationEvent $CronjobRegistrationEvent);

    public function cronjobExecute();
}
