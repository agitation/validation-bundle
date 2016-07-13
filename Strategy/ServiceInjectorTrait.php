<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy;

use Agit\CommonBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ServiceAwarePluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait ServiceInjectorTrait
{
    // expects $this->container to be set. Override this method if you're
    // getting the container from somewhere else.
    protected function getContainer()
    {
        if (!isset($this->container) || !($this->container instanceof ContainerInterface))
            throw new InternalErrorException(sprintf("The %s class must either have a `container` property or override the `getContainer` method.", __CLASS__));

        return $this->container;
    }

    final protected function injectServices($instance, $dependencies)
    {
        if (is_array($dependencies) && count($dependencies))
        {
            if (!($instance instanceof ServiceAwarePluginInterface))
                throw new InternalErrorException(sprintf("The %s plugin has defined dependencies and thus must implement the ServiceAwarePluginInterface.", get_class($instance)));

            foreach ($dependencies as $dep)
            {
                if ($dep[0] === "@")
                {
                    $dep = substr($dep, 1);
                    $instance->setService($dep, $this->getContainer()->get($dep));
                }
                elseif ($dep[0] === "%")
                {
                    $dep = substr($dep, 1, -1);
                    $instance->setParameter($dep, $this->getContainer()->getParameter($dep));
                }
                else
                {
                    throw new InternalErrorException("Invalid dependency: $dep.");
                }
            }
        }
    }
}
