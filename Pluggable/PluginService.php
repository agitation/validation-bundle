<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\ProcessorInterface;

/**
 * Some of the Agit components ship services which can be extended  with
 * additional features by other bundles. For example, a validation service
 * can be extended with new validators.
 *
 * A pluggable service waits for other components to add their component-
 * specific extensions, so that they become available through the pluggable
 * service to third parties.
 */
class PluginService implements CacheWarmerInterface
{
    private $eventDispatcher;

    // our OWN registration tag
    private $rootRegistrationTag = 'agit.pluggable';

    private $processorList = [];

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatchServiceRegistration()
    {
        $this->eventDispatcher->dispatch(
            $this->rootRegistrationTag,
            new PluggableServiceRegistrationEvent($this));
    }

    public function registerProcessor(ProcessorInterface $processor)
    {
        $this->processorList[] = $processor;
    }

    /**
     * Warms up the cache, required by CacheWarmerInterface
     */
    public function warmUp($cacheDir = null)
    {
        $this->dispatchServiceRegistration();

        usort($this->processorList, function($p1, $p2){
            return $p1->getPriority() - $p2->getPriority();
        });

        foreach ($this->processorList as $processor)
        {
            $this->eventDispatcher->dispatch(
                $processor->getRegistrationTag(),
                $processor->createRegistrationEvent());

            $processor->process();
        }
    }

    /**
     * required by CacheWarmerInterface
     */
    public function isOptional()
    {
        return false;
    }

}
