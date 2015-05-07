<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable;

use Symfony\Component\EventDispatcher\Event;
use Agit\CoreBundle\Pluggable\Strategy\ProcessorInterface;

class PluggableServiceRegistrationEvent extends Event
{
    private $PluginService;

    public function __construct(PluginService $PluginService)
    {
        $this->PluginService = $PluginService;
    }

    public function registerProcessor(ProcessorInterface $Processor)
    {
        return $this->PluginService->registerProcessor($Processor);
    }
}
