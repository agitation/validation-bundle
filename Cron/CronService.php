<?php
/**
 * @package    agitation/cron
 * @link       http://github.com/agitation/AgitCronBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CronBundle\Cron;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CronBundle\Event\CronjobRegistrationEvent;

class CronService
{
    private $EventDispatcher;

    private $eventRegistrationTag = 'agit.cron.register';

    private $serviceList = [];

    private $ranges = [[0, 59], [0, 23], [1, 31], [1, 12], [0, 6]];

    private $now;

    public function __construct(EventDispatcher $EventDispatcher)
    {
        $this->EventDispatcher = $EventDispatcher;
        $this->setDate(new \DateTime());
    }

    public function setDate(\DateTime $DateTime)
    {
        $this->now = [
            (int)$DateTime->format('i'),
            (int)$DateTime->format('H'),
            (int)$DateTime->format('d'),
            (int)$DateTime->format('m'),
            (int)$DateTime->format('w')
        ];
    }

    public function run()
    {
        $this->EventDispatcher->dispatch($this->eventRegistrationTag, new CronjobRegistrationEvent($this));
        $this->executeCronjobs();
    }

    public function registerCronjob(CronAwareInterface $service, $cronTime)
    {
        if ($this->cronApplies($cronTime))
            $this->serviceList[] = $service;
    }

    public function parseCronTime($cronTime)
    {
        $cronParts = preg_split('|\s+|', $cronTime, null, PREG_SPLIT_NO_EMPTY);

        if (count($cronParts) !== 5)
            throw new InternalErrorException("Invalid cron time.");

        $parsedParts = [];

        foreach ($cronParts as $pos => $value)
        {

            if ($value === '*')
            {
                $parsedParts[$pos] = null;
            }
            else
            {
                $elements = [];

                if (preg_match('|^\*/\d+$|', $value))
                {
                    $step = (int)substr($value, 2);

                    for ($i = $this->ranges[$pos][0]; $i < $this->ranges[$pos][1]; $i+=$step)
                        $elements[] = $i;
                }
                elseif (preg_match('|^\d+(,\d+)*$|', $value))
                {
                    $elements = array_map('intval', explode(',', $value));
                }
                else
                {
                    throw new InternalErrorException("Invalid cron time parameter at position $pos.");
                }

                foreach ($elements as $element)
                    if ($element < $this->ranges[$pos][0] || $element > $this->ranges[$pos][1])
                        throw new InternalErrorException("Invalid cron time parameter at position $pos.");

                $parsedParts[$pos] = $elements;
            }
        }

        return $parsedParts;
    }

    public function cronApplies($cronTime)
    {
        $cronParts = $this->parseCronTime($cronTime);
        $applies = true;

        foreach ($cronParts as $pos => $value)
        {
            if ($value !== null && !in_array($this->now[$pos], $value))
            {
                $applies = false;
                break;
            }
        }

        return $applies;
    }

    private function executeCronjobs()
    {
        foreach ($this->serviceList as $service)
            $service->cronjobExecute();
    }
}
