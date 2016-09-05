<?php

namespace Agit\BaseBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Agit\BaseBundle\Service\CronService;

class CronjobRegistrationEvent extends Event
{
    private $cronService;

    public function __construct(CronService $cronService)
    {
        $this->cronService = $cronService;
    }

    public function registerCronjob($cronTime, Callable $callback)
    {
        if ($this->cronService->cronApplies($cronTime)
            call_user_func($callback);
    }
}
