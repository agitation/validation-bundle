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
    private $EventDispatcher;

    // our OWN registration tag
    private $rootRegistrationTag = 'agit.pluggable';

    private $ProcessorList = [];

    public function __construct(EventDispatcher $EventDispatcher)
    {
        $this->EventDispatcher = $EventDispatcher;
    }

    public function dispatchServiceRegistration()
    {
        $this->EventDispatcher->dispatch(
            $this->rootRegistrationTag,
            new PluggableServiceRegistrationEvent($this));
    }

    public function registerProcessor(ProcessorInterface $Processor)
    {
        $this->ProcessorList[] = $Processor;
    }

    /**
     * Warms up the cache, required by CacheWarmerInterface
     */
    public function warmUp($cacheDir = null)
    {
        $this->dispatchServiceRegistration();

        usort($this->ProcessorList, function($P1, $P2){
            return $P1->getPriority() - $P2->getPriority();
        });

        foreach ($this->ProcessorList as $Processor)
        {
            $this->EventDispatcher->dispatch(
                $Processor->getRegistrationTag(),
                $Processor->createRegistrationEvent());

            $Processor->process();
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
