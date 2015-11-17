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

trait ServiceInjectorTrait
{
    abstract protected function getContainer();

    protected function injectServices($instance, $dependencies)
    {
        if (is_array($dependencies) && count($dependencies))
        {
            if (!($instance instanceof ServiceAwarePluginInterface))
                throw new InternalErrorException("The $class plugin has defined dependencies and thus must implement the ServiceAwarePluginInterface.");

            foreach ($dependencies as $serviceName)
                $instance->setService($serviceName, $this->getContainer()->get($serviceName));
        }
    }
}
