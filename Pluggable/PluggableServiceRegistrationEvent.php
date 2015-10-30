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
    private $pluginService;

    public function __construct(PluginService $pluginService)
    {
        $this->pluginService = $pluginService;
    }

    public function registerProcessor(ProcessorInterface $processor)
    {
        return $this->pluginService->registerProcessor($processor);
    }
}
