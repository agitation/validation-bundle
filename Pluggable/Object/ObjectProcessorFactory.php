<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Object;

use Agit\BaseBundle\Pluggable\PluggableServiceInterface;
use Agit\BaseBundle\Pluggable\ProcessorFactoryInterface;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Processes registered objects.
 */
class ObjectProcessorFactory implements ProcessorFactoryInterface
{
    private $cacheProvider;

    public function __construct(CacheProvider $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    public function createPluggableService(array $attributes)
    {
        return new ObjectPluggableService($attributes);
    }

    public function createProcessor(PluggableServiceInterface $pluggableService)
    {
        return new ObjectProcessor($this->cacheProvider, $pluggableService);
    }
}
