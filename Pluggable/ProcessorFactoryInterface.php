<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable;

interface ProcessorFactoryInterface
{
    public function createPluggableService(array $attributes);

    public function createProcessor(PluggableServiceInterface $pluggableService);
}
